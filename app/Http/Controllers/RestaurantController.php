<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة الطعام</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 p-3 md:p-8 min-h-screen pb-32">

    <div class="max-w-3xl mx-auto space-y-8">

        <!-- 1. قسم السندويشات -->
        <section class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <span class="text-3xl">🥪</span>
                <h2 class="text-xl md:text-2xl font-extrabold text-slate-800">1. قائمة السندويشات</h2>
            </div>
            
            <div class="space-y-4">
                <!-- سندويش شاورما دجاج -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1529006557810-274b9b2fc783?w=150&auto=format&fit=crop&q=80" alt="سندويش شاورما دجاج" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-base">سندويش شاورما دجاج</h3>
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2 py-0.5 rounded-full">الأكثر طلباً ⭐</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">شرائح دجاج متبلة بالبهارات الخاصة مع الثومية والمخلل والبطاطس.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">18 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('s1', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-s1" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('s1', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('سندويش شاورما دجاج', 's1')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- سندويش شاورما لحم -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1561651823-34feb02250e4?w=150&auto=format&fit=crop&q=80" alt="سندويش شاورما لحم" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">سندويش شاورما لحم</h3>
                            <p class="text-xs text-slate-500 mt-1">شرائح لحم بلدي طازج مع البقدونس والبصل والطحينة ودبس الرمان.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">22 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('s2', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-s2" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('s2', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('سندويش شاورما لحم', 's2')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- سندويش برغر لحم كلاسيك -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=150&auto=format&fit=crop&q=80" alt="برغر لحم كلاسيك" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-base">سندويش برغر لحم كلاسيك</h3>
                                <span class="bg-red-100 text-red-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">خصم 10% 🏷️</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">شريحة لحم بقر مشوية على الفحم مع الجبنة الذائبة والصوص الخاص.</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-extrabold text-orange-600 text-sm">25.2 ر.س</span>
                                <span class="text-xs text-slate-400 line-through">28 ر.س</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('s3', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-s3" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('s3', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('برغر لحم كلاسيك', 's3')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- سندويش شيش طاووق -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=150&auto=format&fit=crop&q=80" alt="سندويش شيش طاووق" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">سندويش شيش طاووق</h3>
                            <p class="text-xs text-slate-500 mt-1">قطع دجاج متبلة ومشوية مع البطاطس المقلية الثومية والمخلل.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">22 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('s4', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-s4" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('s4', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('سندويش شيش طاووق', 's4')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- سندويش فلافل سوبر -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1593001874117-c99c800e3eb7?w=150&auto=format&fit=crop&q=80" alt="سندويش فلافل سوبر" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">سندويش فلافل سوبر</h3>
                            <p class="text-xs text-slate-500 mt-1">أقراص فلافل مقرمشة مع الطحينة والسلطة العربية والباذنجان.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">12 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('s5', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-s5" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('s5', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('سندويش فلافل سوبر', 's5')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. قسم الوجبات الرئيسية -->
        <section class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <span class="text-3xl">🍱</span>
                <h2 class="text-xl md:text-2xl font-extrabold text-slate-800">2. الوجبات الرئيسية</h2>
            </div>
            
            <div class="space-y-4">
                <!-- وجبة مندي دجاج كاملة -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1633964913295-ceb43826e7c9?w=150&auto=format&fit=crop&q=80" alt="وجبة مندي دجاج" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-base">وجبة مندي دجاج كاملة</h3>
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2 py-0.5 rounded-full">العرض الذهبي 🔥</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">نصف دجاجة مندي طرية مع الأرز البسمتي المعطر والدقوس.</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-extrabold text-orange-600 text-sm">42.5 ر.س</span>
                                <span class="text-xs text-slate-400 line-through">50 ر.س</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('m1', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-m1" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('m1', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('وجبة مندي دجاج', 'm1')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- وجبة مشاوي مشكلة -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=150&auto=format&fit=crop&q=80" alt="وجبة مشاوي مشكلة" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">وجبة مشاوي مشكلة</h3>
                            <p class="text-xs text-slate-500 mt-1">تشكيلة 5 أسياخ من الكباب والشيش طاووق واللحم المشوي.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">75 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('m2', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-m2" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('m2', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('وجبة مشاوي مشكلة', 'm2')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- وجبة شاورما عربي دجاج -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1529006557810-274b9b2fc783?w=150&auto=format&fit=crop&q=80" alt="وجبة شاورما عربي دجاج" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">وجبة شاورما عربي دجاج</h3>
                            <p class="text-xs text-slate-500 mt-1">قطع شاورما صاج تقدم مع البطاطس المقلية والثومية والمخلل.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">35 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('m3', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-m3" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('m3', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('وجبة شاورما عربي', 'm3')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- وجبة كبسة لحم غنم -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=150&auto=format&fit=crop&q=80" alt="وجبة كبسة لحم" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">وجبة كبسة لحم غنم</h3>
                            <p class="text-xs text-slate-500 mt-1">قطع لحم طرية ومطهوة بعناية مع الأرز الأحمر المتبل.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">65 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('m4', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-m4" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('m4', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('وجبة كبسة لحم', 'm4')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- وجبة كريسبي دجاج -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=150&auto=format&fit=crop&q=80" alt="وجبة كريسبي دجاج" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-base">وجبة كريسبي دجاج</h3>
                                <span class="bg-green-100 text-green-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">عرض التوفير 💥</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">5 قطع دجاج مقرمشة مع البطاطس المقلية وسلطة الكولسلو.</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-extrabold text-orange-600 text-sm">32 ر.س</span>
                                <span class="text-xs text-slate-400 line-through">38 ر.س</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('m5', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-m5" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('m5', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('وجبة كريسبي دجاج', 'm5')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. قسم الشوربات -->
        <section class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <span class="text-3xl">🥣</span>
                <h2 class="text-xl md:text-2xl font-extrabold text-slate-800">3. قسم الشوربات</h2>
            </div>
            
            <div class="space-y-4">
                <!-- شوربة الفطر بالكريمة -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1547592166-23ac45744acd?w=150&auto=format&fit=crop&q=80" alt="شوربة الفطر" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">شوربة الفطر بالكريمة</h3>
                            <p class="text-xs text-slate-500 mt-1">فطر طازج مطبوخ مع كريمة الطبخ الغنية والأعشاب.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">20 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('so1', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-so1" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('so1', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('شوربة الفطر', 'so1')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- شوربة الدجاج بالخضار -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=150&auto=format&fit=crop&q=80" alt="شوربة الدجاج" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">شوربة الدجاج بالخضار</h3>
                            <p class="text-xs text-slate-500 mt-1">قطع دجاج طرية مطهوة مع الخضار الطازجة والمرق.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">18 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('so2', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-so2" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('so2', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('شوربة الدجاج بالخضار', 'so2')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- شوربة العدس الأصفر -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1541544741938-0af808871cc0?w=150&auto=format&fit=crop&q=80" alt="شوربة العدس" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">شوربة العدس الأصفر</h3>
                            <p class="text-xs text-slate-500 mt-1">عدس أصفر مطحون ومتبل تقدم مع الخبز المحمص.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">15 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('so3', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-so3" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('so3', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('شوربة العدس الأصفر', 'so3')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- شوربة البصل الفرنسية -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1583032015879-e5022cb87c3b?w=150&auto=format&fit=crop&q=80" alt="شوربة البصل" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-base">شوربة البصل الفرنسية</h3>
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full">جديد 🥣</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">بصل مكرمل ببطء يعلوه التوست والجبن المذاب.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">22 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('so4', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-so4" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('so4', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('شوربة البصل الفرنسية', 'so4')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- شوربة حريرة مغربية -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1547592166-23ac45744acd?w=150&auto=format&fit=crop&q=80" alt="شوربة حريرة" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">شوربة حريرة مغربية</h3>
                            <p class="text-xs text-slate-500 mt-1">شوربة تقليدية غنية باللحم، الحمص، والعدس.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">24 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('so5', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-so5" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('so5', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('شوربة حريرة مغربية', 'so5')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. قسم الحلويات -->
        <section class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <span class="text-3xl">🍰</span>
                <h2 class="text-xl md:text-2xl font-extrabold text-slate-800">4. قسم الحلويات</h2>
            </div>
            
            <div class="space-y-4">
                <!-- كنافة بالمكسرات والقشطة -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1579372786545-d24232daf58c?w=150&auto=format&fit=crop&q=80" alt="كنافة" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-base">كنافة بالمكسرات والقشطة</h3>
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2 py-0.5 rounded-full">الأكثر طلباً 🍰</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">كنافة ذهبية محشوة بالقشطة الطازجة ورشة فستق.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">25 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('sw1', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-sw1" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('sw1', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('كنافة بالقشطة', 'sw1')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- أم علي بالمكسرات -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1587314168485-3236d6710814?w=150&auto=format&fit=crop&q=80" alt="أم علي" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">أم علي بالمكسرات</h3>
                            <p class="text-xs text-slate-500 mt-1">رقائق البيف باستري بالحليب الدافئ مع المكسرات والزبيب.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">20 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('sw2', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-sw2" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('sw2', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('أم علي بالمكسرات', 'sw2')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- كيكة الشوكولاتة الذائبة -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=150&auto=format&fit=crop&q=80" alt="مولتن كيك" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">كيكة الشوكولاتة الذائبة</h3>
                            <p class="text-xs text-slate-500 mt-1">كيك كاكاو دافئ بحشوة الشوكولاتة الذائبة تقدم مع الآيس كريم.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">28 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('sw3', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-sw3" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('sw3', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('مولتن كيك', 'sw3')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- بقلاوة بالفستق الحلبي -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1519676867240-f03562e64548?w=150&auto=format&fit=crop&q=80" alt="بقلاوة" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">بقلاوة بالفستق الحلبي</h3>
                            <p class="text-xs text-slate-500 mt-1">طبقات البقلاوة الهشة المحشوة بالفستق الحلبي الفاخر.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">22 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('sw4', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-sw4" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('sw4', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('بقلاوة بالفستق', 'sw4')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- رز بحليب مع القرفة -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=150&auto=format&fit=crop&q=80" alt="رز بحليب" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">رز بحليب مع القرفة</h3>
                            <p class="text-xs text-slate-500 mt-1">طبق أرز بحليب كريمي مبرد مع القرفة والفستق المفروم.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">15 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('sw5', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-sw5" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('sw5', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('رز بحليب', 'sw5')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. قسم المشروبات -->
        <section class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                <span class="text-3xl">🥤</span>
                <h2 class="text-xl md:text-2xl font-extrabold text-slate-800">5. المشروبات المنعشة</h2>
            </div>
            
            <div class="space-y-4">
                <!-- عصير برتقال طازج -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1613478223719-2ab802602423?w=150&auto=format&fit=crop&q=80" alt="عصير برتقال" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">عصير برتقال طازج</h3>
                            <p class="text-xs text-slate-500 mt-1">برتقال طبيعي معصور فوراً عند الطلب.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">14 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('d1', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-d1" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('d1', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('عصير برتقال طازج', 'd1')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- كوكاكولا بارد -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=150&auto=format&fit=crop&q=80" alt="كوكاكولا" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">كوكاكولا بارد</h3>
                            <p class="text-xs text-slate-500 mt-1">علبة مشروب غازي كوكاكولا منعشة (330 مل).</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">6 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('d2', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-d2" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('d2', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('كوكاكولا بارد', 'd2')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- سفن اب المنعش -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1581006852262-e4307cf6283a?w=150&auto=format&fit=crop&q=80" alt="سفن اب" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">سفن اب المنعش</h3>
                            <p class="text-xs text-slate-500 mt-1">علبة مشروب غازي بنكهة الليمون (330 مل).</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">6 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('d3', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-d3" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('d3', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('سفن اب المنعش', 'd3')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- لبن عيران طازج -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1556881286-fc6915169721?w=150&auto=format&fit=crop&q=80" alt="لبن عيران" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">لبن عيران طازج</h3>
                            <p class="text-xs text-slate-500 mt-1">لبن زبادي مخفوق بالماء ورشة نعناع منعش.</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">8 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('d4', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-d4" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('d4', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('لبن عيران طازج', 'd4')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>

                <!-- ماء معدني -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=150&auto=format&fit=crop&q=80" alt="ماء معدني" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">ماء معدني</h3>
                            <p class="text-xs text-slate-500 mt-1">زجاجة مياه نقية وباردة (500 مل).</p>
                            <span class="font-extrabold text-orange-600 text-sm mt-2 inline-block">3 ر.س</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-2 sm:pt-0">
                        <div class="flex items-center gap-3 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-sm shrink-0">
                            <button type="button" onclick="updateQty('d5', -1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">-</button>
                            <span id="qty-d5" class="text-sm font-bold text-slate-800 min-w-[1.2rem] text-center">1</span>
                            <button type="button" onclick="updateQty('d5', 1)" class="text-orange-600 font-black text-lg hover:scale-125 transition">+</button>
                        </div>
                        <button type="button" onclick="addToCart('ماء معدني', 'd5')" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">إضافة للسلة 🛒</button>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- شريط التنبيه عند إضافة عنصر -->
    <div id="toast" class="fixed bottom-5 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-xl text-sm font-bold opacity-0 transition-opacity pointer-events-none duration-300">
        تمت إضافة الطلب بنجاح! 🎉
    </div>

    <script>
        function updateQty(id, change) {
            const el = document.getElementById('qty-' + id);
            if (el) {
                let current = parseInt(el.innerText) || 1;
                current += change;
                if (current < 1) current = 1;
                el.innerText = current;
            }
        }

        function addToCart(itemName, id) {
            const qty = document.getElementById('qty-' + id).innerText;
            const toast = document.getElementById('toast');
            toast.innerText = `تمت إضافة (${qty}) من ${itemName} إلى السلة 🛒`;
            toast.classList.remove('opacity-0');
            
            setTimeout(() => {
                toast.classList.add('opacity-0');
            }, 2500);
        }
    </script>
</body>
</html>