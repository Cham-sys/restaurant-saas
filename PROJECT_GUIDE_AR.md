# دليل مشروع منصة المطاعم

هذا الملف هو نقطة العودة السريعة للمشروع بعد انقطاع طويل عن التطوير. اقرأه بالترتيب في أول جلسة عمل، ثم حدّث قسم المشكلات والقرارات كلما تغيّر السلوك الأساسي.

## 1. ما هو المشروع؟

المشروع SaaS لإدارة مطاعم متعددة. لكل مطعم:

- صفحة عامة يمكن للعميل فتحها عبر `/{slug}`.
- قائمة أصناف وتصنيفات.
- سلة مشتريات محفوظة في Session.
- Checkout لإنشاء الطلب.
- فاتورة، صفحة نجاح، وتتبع بالـ tracking code.
- تقييمات وعروض وكوبونات وPWA.
- ثيم قابل للاختيار وإعدادات مخصصة.

يوجد أيضًا نوعان من لوحات Filament:

- `/restaurant`: لوحة صاحب المطعم وموظفيه.
- `/sham`: لوحة الإدارة العامة.

## 2. التقنيات والإصدارات

المشروع مبني على:

- PHP `^8.3`، والبيئة المقصودة في إرشادات المشروع PHP 8.4.
- Laravel `^13.17`.
- Filament 5.
- Livewire 4 وFlux 2.
- Laravel Fortify للمصادقة و2FA وPasskeys.
- Laravel Reverb/Echo للتجهيزات الفورية.
- Tailwind CSS 4 وVite 8.
- Pest 4 للاختبارات.
- Larastan 3 وLaravel Pint.
- قاعدة البيانات الافتراضية في `.env.example`: SQLite.

مصدر الإصدارات الفعلي هو `composer.json` و`package.json`، وليس هذا الملف.

## 3. تشغيل المشروع محليًا

### المتطلبات

- PHP 8.3 أو أحدث.
- Composer.
- Node.js وnpm.
- امتدادات PHP المطلوبة من Laravel، وعلى الأقل SQLite إذا استُخدمت قاعدة SQLite.

### تشغيل أول مرة

من جذر المشروع:

```powershell
composer run setup
```

هذا الأمر يثبت Composer، ينشئ `.env` عند عدم وجوده، يولد `APP_KEY`، يشغل الهجرات، يثبت npm، ويبني ملفات الواجهة.

إذا كان `.env` موجودًا، راجع خصوصًا `APP_KEY` و`DB_CONNECTION` و`APP_URL` قبل التشغيل.

### التشغيل أثناء التطوير

```powershell
composer run dev
```

يشغل عادةً ثلاثة processos متوازية:

- Laravel server على `php artisan serve`.
- Queue worker عبر `php artisan queue:listen --tries=1`.
- Vite عبر `npm run dev`.

بديل يدوي عند الحاجة:

```powershell
php artisan serve
npm run dev
php artisan queue:listen --tries=1
```

### أوامر يومية مفيدة

```powershell
php artisan route:list --except-vendor
php artisan migrate:status
php artisan migrate
php artisan optimize:clear
npm run build
php artisan test --compact
vendor/bin/pint --dirty --format agent
vendor/bin/phpstan analyse
```

لا تشغّل `migrate:fresh` على بيانات مهمة؛ فهو يحذف الجداول والبيانات.

## 4. خريطة الملفات

### منطق التطبيق

- `app/Models`: نماذج Eloquent والعلاقات والتحويلات.
- `app/Http/Controllers`: واجهة HTTP العامة مثل المطعم والسلة والطلب والفاتورة والتقييم.
- `app/Filament`: موارد وصفحات لوحتي Filament.
- `app/Livewire`: صفحات الحساب التفاعلية مثل الملف الشخصي والأمان.
- `app/Services`: منطق أكبر من أن يوضع داخل Controller، وأهمه اكتشاف ورفع الثيمات.
- `app/Helpers/ThemeHelper.php`: اختيار View الثيم وقراءة متغيراته.
- `app/Providers`: تسجيل Laravel وFilament.

### الواجهة

- `resources/views/themes`: قوالب واجهة العميل، حاليًا `burger-theme`.
- `resources/views/filament`: صفحات مخصصة داخل Filament.
- `resources/views/livewire`: قوالب Livewire.
- `resources/css`: CSS الخاص بالتطبيق ولوحتي Filament.
- `resources/js`: JavaScript وتهيئة Vite/Echo.

### البيانات والاختبارات

- `database/migrations`: مخطط قاعدة البيانات.
- `database/factories`: بيانات الاختبار.
- `database/seeders`: البيانات الأولية.
- `tests/Feature`: اختبارات التدفقات HTTP وFilament والمصادقة.
- `tests/Unit`: الاختبارات الوحدية.

## 5. نموذج البيانات والعلاقات

العلاقات الأساسية:

```text
User -> Restaurant
Restaurant -> Categories -> Products
Restaurant -> Orders -> Order_items
Order -> Invoice
Restaurant -> Offers -> Products (pivot offer_product)
Restaurant -> Coupons
Restaurant -> Theme -> RestaurantThemeSetting
Order -> Review
```

النقاط المهمة:

- `Restaurant.slug` هو معرف المطعم في URLs العامة.
- `Product` يستخدم Soft Deletes، ويملك `is_available` للتحكم في ظهوره وقابليته للطلب.
- `Order_item` يحفظ اسم المنتج وسعره وقت الطلب، لذلك لا تعتمد الفاتورة على سعر المنتج الحالي.
- `Order` يحفظ subtotal والضريبة/التوصيل والخصومات والمبلغ النهائي وحالة الدفع وحالة الطلب.
- `Theme` يربط المطعم بمجلد تحت `resources/views/themes`.
- إعدادات الثيم الافتراضية في `theme.json`، وإعدادات المطعم المخصصة في `restaurant_theme_settings`.

## 6. تدفق العميل الكامل

1. يزور العميل `/{slug}`، ويجلب `RestaurantController` المطعم والثيم.
2. يزور `/{slug}/menu` لعرض التصنيفات والمنتجات المتاحة.
3. يضيف منتجًا عبر `POST /{slug}/cart/add`.
4. تحفظ السلة في Session تحت المفتاح `cart`، وبنيتها تقريبًا:

```php
[
    12 => ['qty' => 2],
]
```

5. تعرض `CartController` السلة وتعيد حساب الإجمالي من أسعار قاعدة البيانات.
6. يعرض `OrderController@checkout` Checkout بعد التأكد من وجود المنتجات وتبعيتها للمطعم وتوفرها.
7. يرسل العميل `POST /{slug}/checkout`، فيعاد حساب الأسعار من قاعدة البيانات، ثم ينشأ:
   - الطلب.
   - عناصر الطلب.
   - الفاتورة.
8. تفرغ السلة، ثم ينتقل العميل إلى `/order/success/{code}`.
9. يمكن تتبع الطلب عبر `/track/{code}` أو فتح الفاتورة والتقييم.

لا تعتمد على قيم السعر القادمة من المتصفح؛ المصدر الصحيح للسعر هو المنتج في قاعدة البيانات.

## 7. العروض والكوبونات

- `apply-offer` و`apply-coupon` مساران AJAX للتحقق قبل الإرسال.
- العرض مرتبط بالمطعم، وله فترة نشاط وحد أدنى وحد استخدام.
- الكوبون مرتبط بالمطعم، وله كود وصلاحية وحد أدنى وقواعد استخدام.
- عند التخزين يعاد التحقق من العرض والكوبون، لأن فحص AJAX وحده لا يكفي.
- لا تضع ثقة في `offer_id` أو `coupon_id` أو `subtotal` القادمة من العميل؛ يجب أن تطابق ما يحسبه الخادم من السلة.

## 8. الثيمات

كل ثيم صالح يحتاج مجلدًا تحت `resources/views/themes` وملف `theme.json`، إضافة إلى القوالب التي يستدعيها التدفق.

الثيم الحالي `burger-theme` يحتوي على:

- `layout.blade.php`.
- صفحات home/menu/product/offers.
- قوالب cart وcheckout وsuccess.
- قوالب التتبع والفاتورة.
- قوالب التقييمات.

يكتشف `ThemeDiscoveryService` المجلدات التي تحتوي `theme.json` ويزامنها مع جدول `themes`. يستخدم `ThemeHelper` مسار الثيم الحالي لبناء اسم View ديناميكيًا.

عند إضافة ثيم جديد:

1. أنشئ مجلدًا باسم واضح.
2. أضف `theme.json` صالحًا.
3. وفّر كل القوالب التي تحتاجها المسارات العامة، لا الصفحة الرئيسية فقط.
4. شغّل مزامنة الثيمات بالطريقة المستخدمة في المشروع أو راجع Seeder/Filament إذا لم تكن هناك Command مخصصة.
5. اختبر home وmenu وcart وcheckout وsuccess وtrack وinvoice وreviews.

## 9. المصادقة والصلاحيات

- Fortify يوفّر تسجيل الدخول والتسجيل وإعادة تعيين كلمة المرور والتحقق من البريد و2FA وPasskeys.
- لوحة `/restaurant` محمية بـ Filament authentication، وتستخدم `restaurant_id` للمستخدم لعزل بيانات المطعم.
- لوحة `/sham` مخصصة للإدارة العامة، ويجب التأكد من أن Gate/Policy يميز صلاحيات الإدارة عن مستخدم المطعم.
- إعدادات الحساب تحت `/settings` محمية بـ `auth`، والأمان يضيف `password.confirm`.
- صفحات العميل العامة لا تحتاج تسجيل دخول لإضافة الطلب، لذلك يجب تدقيق كل معرف عام وصلاحية عرض بياناته.

## 10. الاختبارات الحالية

الاختبارات الموجودة تغطي بدرجة جيدة:

- المصادقة والتسجيل وإعادة تعيين كلمة المرور والتحقق والبصمة.
- الملف الشخصي والأمان.
- اختبارات لوحة المطعم والعزل والصلاحيات.
- رفع الثيمات وإعدادات الثيم.

التغطية الناقصة الأهم هي التدفق التجاري: `cart -> checkout -> order -> items -> invoice`، مع الأسعار والخصومات والتزامن وفشل الحفظ.

قبل كل تغيير:

```powershell
php artisan test --compact --filter=اسم_الاختبار
```

وبعد التغيير شغّل الاختبار المتأثر، ثم Pint، ثم مجموعة الاختبارات الأوسع عند الحاجة.

## 11. مشكلات معروفة حاليًا

### مؤكدة من الاختبارات

آخر تشغيل للاختبارات في بيئة هذا الدليل: `41` اختبارًا، منها `38` ناجحًا و`3` فاشلة.

1. `tests/Feature/DashboardTest.php` يتوقع أن المسار `dashboard` موجود ويعيد Redirect للضيف و200 للمستخدم، لكن النتيجة الحالية 404 في الحالتين. راجع تعريف المسار والاختبار؛ المسار الحالي في `routes/web.php` يستخدم View `dashboard`.
2. `tests/Feature/Filament/RestaurantPanelTest.php` يفشل بحالة 403 عند محاولة المستخدم المصادق عليه فتح Dashboard المطعم. راجع دور المستخدم وPolicy/تحديد المطعم قبل تعديل الاختبار.
3. الأمر `php artisan types:check` غير موجود رغم وجود Script بالاسم نفسه في `composer.json`. الفحص الصحيح المتاح مباشرة هو `vendor/bin/phpstan analyse`، أو يجب إصلاح Script ليشير إلى الأمر المناسب.

### مخاطر واضحة من قراءة الكود وتحتاج إصلاحًا واختبارًا

1. **مسارات إدارة الثيمات بلا حماية ظاهرة:** مسارات `themes` وعمليات activate/clone/reset موجودة في `routes/web.php` خارج `auth`، و`ThemeController` لا يفرض Authorization داخليًا. هذا قد يسمح لزائر باستدعاء عمليات إدارية.
2. **قيمة `total_amount` غير صحيحة:** إنشاء الطلب يحفظ `total_amount` كـ `$subtotal`، بينما المبلغ بعد الضريبة والتوصيل والخصومات محفوظ في `final_amount`. يجب توضيح معنى العمودين ثم اختبار القيم والفاتورة.
3. **إنشاء الطلب ليس داخل Transaction:** قد يُحفظ Order دون عناصر أو Invoice إذا فشل أي Insert لاحق. استخدم Transaction بعد تثبيت الاختبارات المطلوبة.
4. **احتمال التلاعب بقيم الخصم:** `store` يعتمد على `offer_id` و`coupon_id`، ويقبل `coupon_code` من الطلب، بينما يجب مطابقة الكود والسلة وإعادة الحساب من الخادم في عملية ذرية.
5. **تطبيق العرض على مستوى الإجمالي فقط:** توجد علاقة offer-product، لكن مسار التخزين الظاهر يتحقق من المطعم والنشاط والحد الأدنى ولا يثبت أن منتجات السلة مشمولة بالعرض.
6. **السلة عامة داخل Session:** لا يوجد معرف مطعم مستقل محفوظ مع السلة. توجد حماية عند الإضافة، لكن يجب جعل سلوك الانتقال بين مطعمين صريحًا واختباره.
7. **السلة قد تعرض منتجات غير متاحة:** `CartController@index` يجلب المنتج دون شرط `is_available`; المنع يظهر لاحقًا في checkout. الأفضل عرض حالة واضحة أو تنظيف العنصر.
8. **`Appearance` غير مكتمل:** مسار إعدادات المظهر موجود، لكن مكوّن `app/Livewire/Settings/Appearance.php` لا يقدم سلوكًا فعليًا بحسب القراءة الحالية.
9. **كود CRUD قديم غير مستخدم:** بعض Controllers مثل `ProductController` تبدو Stubs بينما الإدارة الفعلية في Filament. لا تحذفها قبل التأكد من عدم وجود روابط أو API تعتمد عليها.
10. **تغييرات محلية غير ملتزم بها:** توجد تعديلات كثيرة في Controllers وModels وSeeder و`burger-theme/layout.blade.php`، إضافة إلى Factories جديدة واسم ملف غريب `origin)`. راجعها قبل الدمج أو البناء حتى لا تختلط إصلاحات قديمة بتطوير جديد.

## 12. ترتيب مقترح لاستئناف التطوير

1. احفظ نسخة من الحالة الحالية، ثم راجع `git diff` لكل ملف معدل؛ لا تفقد تغييراتك المحلية.
2. أصلح فشل Dashboard و403 في اختبار لوحة المطعم، لأنهما يعيقان الثقة في البيئة الأساسية.
3. أصلح Script فحص الأنواع أو وثّق الأمر الصحيح، ثم شغّل Pint وPHPStan.
4. أضف اختبارات checkout والطلب والفاتورة والخصم قبل إعادة هيكلة `OrderController`.
5. ضع إنشاء الطلب والعناصر والفاتورة وتحديث استخدام العرض/الكوبون داخل Transaction مناسبة.
6. أغلق مسارات إدارة الثيمات بـ middleware وصلاحيات واضحة، ثم أضف اختبار زائر ومستخدم مطعم ومدير.
7. راجع السلة عند اختلاف المطعم أو حذف/تعطيل منتج.
8. بعد استقرار المنطق، أكمل `Appearance` واحذف أو اعزل الـ stubs غير المستخدمة.
9. اختبر كل ثيم على كل المسارات العامة، ثم شغّل `npm run build`.

## 13. طريقة العمل الآمنة

- ابدأ دائمًا بـ `git status` و`git diff`؛ توجد تغييرات محلية يجب احترامها.
- ابحث عن المسار أو Model أو Resource المسؤول قبل تعديل ملفات كثيرة.
- غيّر أصغر سطح ممكن، ثم شغّل اختبارًا يثبت السلوك.
- لا تثق ببيانات الأسعار والخصومات القادمة من المتصفح.
- استخدم Policies وmiddleware للحدود الأمنية، لا الاعتماد على إخفاء زر في الواجهة.
- عند تغيير Schema، أضف Migration جديدة بدل تعديل Migration مطبقة.
- بعد تعديل PHP شغّل `vendor/bin/pint --dirty --format agent`.
- لا تستخدم بيانات الإنتاج لتجارب `migrate:fresh` أو Seeders.

## 14. أول جلسة عملية مقترحة

```powershell
git status --short
php artisan migrate:status
php artisan route:list --except-vendor
php artisan test --compact
vendor/bin/phpstan analyse
npm run build
```

بعد ذلك ابدأ من قسم **مشكلات معروفة حاليًا**، وسجّل في هذا الملف ما تم إصلاحه مع اختبار يثبت الإصلاح.