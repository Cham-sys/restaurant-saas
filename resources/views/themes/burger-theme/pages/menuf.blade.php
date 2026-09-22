<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة الطعام | {{ $table->restaurant->name ?? 'المطعم' }} - طاولة {{ $table->number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-orange-50/50 font-sans p-3 md:p-8 min-h-screen">

    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-orange-100" x-data="{
        discountPercent: 10,
        items: {
            // === الشوربات ===
            1: { name: 'شوربة الفطر', desc: 'فطر طازج مطبوخ مع الكريمة الغنية والأعشاب العطرية', price: 20, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=300' },
            2: { name: 'شوربة الدجاج', desc: 'قطع دجاج طرية مع الخضار ومرق الدجاج الغني', price: 18, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=300' },
            3: { name: 'شوربة البصل', desc: 'بصل كراميل مطهو ببطء يعلوه خبز محمص وجبن مذاب', price: 22, oldPrice: 28, offer: 'عرض خاص 🔥', qty: 0, img: 'https://images.unsplash.com/photo-1583085221782-938ba1b40283?w=300' },
            4: { name: 'شوربة العدس', desc: 'عدس أصفر مطحون مع الكمون والليمون وزيت الزيتون', price: 15, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=300' },
            5: { name: 'شوربة البطاطا مع الدجاج', desc: 'بطاطا كريمية مع قطع الدجاج والتوابل الخاصة', price: 24, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=300' },

            // === الأطباق الرئيسية والمأكولات ===
            6: { name: 'طبق المندي', desc: 'دجاج متبل مشوي مع أرز بسمتي بالبهارات والمكسرات المقرمشة', price: 45, oldPrice: 55, offer: 'الأكثر طلباً ⭐', qty: 0, img: 'https://images.unsplash.com/photo-1544025162-d76694265947?w=300' },
            7: { name: 'شاورما دجاج', desc: 'شرائح دجاج محمرة مع ثومية متميزة ومخلل داخل خبز صاج طازج', price: 25, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1561651823-34feb02250e4?w=300' },
            8: { name: 'كبة مقلية', desc: 'كرات الجريش واللحم المفروم المحشوة باللحم والمكسرات والرمان', price: 30, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?w=300' },
            9: { name: 'فتوش سوري', desc: 'خضار طازجة مع الخبز المحمص ودبس الرمان وزيت الزيتون', price: 18, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300' },
            10: { name: 'تبولة', desc: 'بقدونس مفروم ناعم مع الطماطم والبرغل والليمون الطبيعي', price: 18, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300' },
            11: { name: 'مشاوي مشكلة', desc: 'تشكيلة من كباب اللحم، شش طاووق، وقطع اللحم المشوية على الفحم', price: 65, oldPrice: 80, offer: 'خصم مميز 🏷️', qty: 0, img: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=300' },
            12: { name: 'حمص باللحمة', desc: 'حمص بطحينة ناعم يعلوه لحم بلدي محمر بالسمن والمكسرات', price: 22, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1577968897966-3d4325b36b61?w=300' },
            13: { name: 'يلنجي (ورق عنب)', desc: 'ورق عنب محشو بالأرز والخضار بزيت الزيتون ودبس الرمان', price: 20, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1541544741938-0af808871cc0?w=300' },
            14: { name: 'فلافل مشكلة', desc: 'أقراص فلافل مقرمشة مع الطحينة والمخللات والخبز الطازج', price: 15, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1593001874117-c99c800e3eb7?w=300' },

            // === المشروبات ===
            15: { name: 'ماء معدني', desc: 'زجاجة مياه نقية باردة (500 مل)', price: 3, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=300' },
            16: { name: 'كأس ثلج', desc: 'كأس مليء بقطع الثلج المنعشة', price: 1, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=300' },
            17: { name: 'قهوة عربية / تركية', desc: 'قهوة مخمرة ومحضرة على الأصول مع الهيل', price: 8, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=300' },
            18: { name: 'شاي أحمر / أخضر', desc: 'شاي ساخن مخمر مع أوراق النعناع الطازجة', price: 5, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=300' },
            19: { name: 'لبن عيران', desc: 'لبن زبادي طازج مخفوق مع القليل من الملح والنعناع', price: 7, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1626078436896-f08988e0b65f?w=300' },
            20: { name: 'كوكاكولا / كولا', desc: 'مشروب غازي منعش يقدم بارداً', price: 6, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300' },
            21: { name: 'سفن اب', desc: 'مشروب غازي بنكهة الليمون المنعشة', price: 6, oldPrice: 0, offer: false, qty: 0, img: 'https://images.unsplash.com/photo-1625772299848-391b6a87d7b3?w=300' }
        },
        get subtotal() {
            return Object.values(this.items).reduce((sum, item) => sum + (item.qty * item.price), 0);
        },
        get discountAmount() {
            return (this.subtotal * this.discountPercent) / 100;
        },
        get grandTotal() {
            return this.subtotal - this.discountAmount;
        }
    }">
        <!-- الهيدر الرئيسي باللون البرتقالي الجذاب -->
        <div class="bg-gradient-to-r from-orange-500 to-amber-500 text-white p-6 rounded-b-3xl text-center shadow-md relative">
            <div class="inline-block bg-white/20 backdrop-blur-md px-4 py-1 rounded-full text-xs font-semibold mb-2 text-orange-100">
                🎉 خصم خاص <span x-text="discountPercent + '%'"></span> على جميع الطلبات
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-wide">{{ $table->restaurant->name ?? 'مطعمنا' }}</h1>
            <p class="text-sm text-orange-100 mt-1">أهلاً بك! أنت الآن تطلب من <span class="font-bold underline decoration-amber-200">طاولة رقم ({{ $table->number }})</span></p>
        </div>

        <div class="p-4 md:p-6 space-y-10">

            <!-- قسم الشوربات -->
            <section>
                <div class="flex items-center gap-3 mb-5 border-b-2 border-orange-200 pb-2">
                    <span class="bg-orange-500 text-white p-2 rounded-xl text-lg shadow-sm">🥣</span>
                    <h2 class="text-xl font-bold text-gray-800">الشوربات الساخنة</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="(item, id) in Object.fromEntries(Object.entries(items).slice(0, 5))" :key="id">
                        <div class="bg-white border border-orange-100 rounded-2xl p-3 flex gap-3 shadow-sm hover:shadow-md transition relative overflow-hidden">
                            <template x-if="item.offer">
                                <span class="absolute top-2 left-2 bg-orange-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow" x-text="item.offer"></span>
                            </template>
                            <img :src="item.img" class="w-24 h-24 object-cover rounded-xl flex-shrink-0">
                            <div class="flex flex-col justify-between flex-grow">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm" x-text="item.name"></h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed" x-text="item.desc"></p>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <div>
                                        <span class="font-bold text-orange-600 text-sm" x-text="item.price + ' ر.س'"></span>
                                        <template x-if="item.oldPrice > 0">
                                            <span class="text-xs text-gray-400 line-through mr-1" x-text="item.oldPrice + ' ر.س'"></span>
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-2 bg-orange-50 border border-orange-200 px-2 py-1 rounded-lg">
                                        <button @click="if(item.qty > 0) item.qty--" class="text-orange-600 font-bold text-base hover:scale-110 active:scale-95 transition">-</button>
                                        <span class="text-sm font-bold text-gray-800 min-w-[1rem] text-center" x-text="item.qty"></span>
                                        <button @click="item.qty++" class="text-orange-600 font-bold text-base hover:scale-110 active:scale-95 transition">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- قسم الأطباق والمأكولات -->
            <section>
                <div class="flex items-center gap-3 mb-5 border-b-2 border-orange-200 pb-2">
                    <span class="bg-orange-500 text-white p-2 rounded-xl text-lg shadow-sm">🍲</span>
                    <h2 class="text-xl font-bold text-gray-800">الأطباق والوجبات الرئيسية</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="(item, id) in Object.fromEntries(Object.entries(items).slice(5, 14))" :key="id">
                        <div class="bg-white border border-orange-100 rounded-2xl p-3 flex gap-3 shadow-sm hover:shadow-md transition relative overflow-hidden">
                            <template x-if="item.offer">
                                <span class="absolute top-2 left-2 bg-orange-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow" x-text="item.offer"></span>
                            </template>
                            <img :src="item.img" class="w-24 h-24 object-cover rounded-xl flex-shrink-0">
                            <div class="flex flex-col justify-between flex-grow">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm" x-text="item.name"></h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed" x-text="item.desc"></p>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <div>
                                        <span class="font-bold text-orange-600 text-sm" x-text="item.price + ' ر.س'"></span>
                                        <template x-if="item.oldPrice > 0">
                                            <span class="text-xs text-gray-400 line-through mr-1" x-text="item.oldPrice + ' ر.س'"></span>
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-2 bg-orange-50 border border-orange-200 px-2 py-1 rounded-lg">
                                        <button @click="if(item.qty > 0) item.qty--" class="text-orange-600 font-bold text-base hover:scale-110 active:scale-95 transition">-</button>
                                        <span class="text-sm font-bold text-gray-800 min-w-[1rem] text-center" x-text="item.qty"></span>
                                        <button @click="item.qty++" class="text-orange-600 font-bold text-base hover:scale-110 active:scale-95 transition">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- قسم المشروبات -->
            <section class="pb-16">
                <div class="flex items-center gap-3 mb-5 border-b-2 border-orange-200 pb-2">
                    <span class="bg-orange-500 text-white p-2 rounded-xl text-lg shadow-sm">🥤</span>
                    <h2 class="text-xl font-bold text-gray-800">المشروبات والإنعاش</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="(item, id) in Object.fromEntries(Object.entries(items).slice(14))" :key="id">
                        <div class="bg-white border border-orange-100 rounded-2xl p-3 flex gap-3 shadow-sm hover:shadow-md transition relative">
                            <img :src="item.img" class="w-20 h-20 object-cover rounded-xl flex-shrink-0">
                            <div class="flex flex-col justify-between flex-grow">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm" x-text="item.name"></h3>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed" x-text="item.desc"></p>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="font-bold text-orange-600 text-sm" x-text="item.price + ' ر.س'"></span>
                                    <div class="flex items-center gap-2 bg-orange-50 border border-orange-200 px-2 py-1 rounded-lg">
                                        <button @click="if(item.qty > 0) item.qty--" class="text-orange-600 font-bold text-base hover:scale-110 active:scale-95 transition">-</button>
                                        <span class="text-sm font-bold text-gray-800 min-w-[1rem] text-center" x-text="item.qty"></span>
                                        <button @click="item.qty++" class="text-orange-600 font-bold text-base hover:scale-110 active:scale-95 transition">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

        </div>

        <!-- شريط الحساب الإجمالي العائم السفلي -->
        <div class="fixed bottom-0 left-0 right-0 max-w-4xl mx-auto bg-white/95 backdrop-blur-md border-t border-orange-200 p-3 md:p-4 shadow-2xl flex items-center justify-between z-50 rounded-t-2xl">
            <div class="flex flex-col">
                <template x-if="subtotal > 0">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span>المجموع: <span class="line-through" x-text="subtotal + ' ر.س'"></span></span>
                        <span class="bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded font-bold" x-text="'خصم ' + discountPercent + '%'"></span>
                    </div>
                </template>
                <div class="flex items-baseline gap-1">
                    <span class="text-xs text-gray-600 font-bold">الصافي:</span>
                    <span class="text-xl md:text-2xl font-black text-orange-600" x-text="grandTotal.toFixed(2) + ' ر.س'"></span>
                </div>
            </div>
            <button class="bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg transition active:scale-95 flex items-center gap-2 text-sm">
                <span>إرسال الطلب</span>
                <span>🚀</span>
            </button>
        </div>

    </div>

</body>
</html>