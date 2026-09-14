<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مركز التوصيل</title>
    <link rel="preconnect" href="https://unpkg.com">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 p-4 text-slate-900 sm:p-6">
    <main class="mx-auto max-w-7xl space-y-6">
        <header class="flex flex-col gap-5 rounded-3xl border border-slate-800 bg-slate-900 p-6 text-white shadow-2xl sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold text-orange-400">مركز التوصيل</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight">رحلاتك الحالية</h1>
                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-400">الموقع يُرسل من جهازك مباشرة، ويظهر للعميل في صفحة التتبع العامة.</p>
            </div>
            <div class="flex items-center gap-3 text-sm text-slate-300">
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_14px_rgba(52,211,153,0.8)]"></span>
                <span>متصل</span>
                <span class="rounded-full bg-slate-800 px-3 py-1 font-bold">{{ $orders->count() }} طلب</span>
            </div>
        </header>

        @forelse ($orders as $order)
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl" data-order="{{ $order->id }}" data-status="{{ $order->status }}">
                <div class="grid lg:grid-cols-[minmax(0,1fr)_390px]">
                    <div class="min-h-[360px] bg-slate-200" id="map-{{ $order->id }}"></div>
                    <div class="flex flex-col gap-5 p-5 sm:p-7">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-orange-600">{{ $order->restaurant?->name }}</p>
                                <h2 class="mt-1 text-xl font-black">طلب {{ $order->tracking_code }}</h2>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span data-order-status class="shrink-0 rounded-full bg-orange-100 px-3 py-1 text-xs font-black text-orange-700">{{ $order->status === 'on_way' ? 'في الطريق' : 'جاهز' }}</span>
                                <a href="{{ route('driver.order.tracking', $order) }}" class="text-xs font-bold text-slate-500 underline hover:text-orange-600">فتح صفحة التتبع</a>
                            </div>
                        </div>
                        <div class="space-y-3 border-y border-slate-100 py-4 text-sm">
                            <div class="flex gap-3"><span class="text-orange-500">●</span><span class="font-bold">{{ $order->customer_name }}</span><a class="mr-auto text-orange-600 underline" href="tel:{{ $order->customer_phone }}">اتصال</a></div>
                            <div class="flex gap-3 text-slate-600"><span class="text-orange-500">⌖</span><span>{{ $order->delivery_address ?: 'لا يوجد عنوان مسجل' }}</span></div>
                        </div>
                        <div>
                            <p class="mb-2 text-xs font-black uppercase tracking-wider text-slate-400">محتويات الطلب</p>
                            <div class="space-y-2 text-sm">
                                @foreach ($order->items as $item)
                                    <div class="flex justify-between"><span>{{ $item->product?->name ?? 'منتج محذوف' }}</span><span class="font-bold text-slate-500">×{{ $item->quantity }}</span></div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-auto space-y-2">
                            <button type="button" data-start-tracking="{{ $order->id }}" class="w-full rounded-xl bg-orange-500 px-4 py-3 font-black text-white transition hover:bg-orange-600 disabled:cursor-not-allowed disabled:opacity-60">
                                {{ $order->status === 'on_way' ? 'إيقاف مشاركة الموقع' : 'بدء التوصيل ومشاركة الموقع' }}
                            </button>
                            <button type="button" data-deliver-order="{{ $order->id }}" class="w-full rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 font-black text-emerald-700 transition hover:bg-emerald-100 disabled:cursor-not-allowed disabled:opacity-50" {{ $order->status !== 'on_way' ? 'disabled' : '' }}>
                                تأكيد التسليم
                            </button>
                            <p data-location-status="{{ $order->id }}" class="text-center text-xs text-slate-500"></p>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-700 bg-slate-900 p-16 text-center text-slate-400">لا توجد طلبات توصيل حالياً.</div>
        @endforelse
    </main>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const watches = new Map();
        const maps = new Map();

        const request = (url, options = {}) => fetch(url, {
            ...options,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', ...(options.headers || {}) },
        });

        document.querySelectorAll('[data-order]').forEach((card) => {
            const orderId = card.dataset.order;
            const map = L.map(`map-${orderId}`, { zoomControl: false }).setView([24.7136, 46.6753], 13);
            L.control.zoom({ position: 'bottomright' }).addTo(map);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors', maxZoom: 19 }).addTo(map);
            maps.set(orderId, { map, customer: null, driver: null });
            loadOrder(orderId);
        });

        async function loadOrder(orderId) {
            const response = await request(`/driver/orders/${orderId}`);
            if (!response.ok) return;
            const data = await response.json();
            const state = maps.get(orderId);
            const customer = data.customer.latitude && data.customer.longitude ? [Number(data.customer.latitude), Number(data.customer.longitude)] : null;
            const driver = data.driver.latitude && data.driver.longitude ? [Number(data.driver.latitude), Number(data.driver.longitude)] : null;
            if (customer) {
                state.customer = state.customer || L.marker(customer).addTo(state.map).bindPopup('موقع العميل');
            }
            if (driver) {
                state.driver = state.driver || L.marker(driver).addTo(state.map).bindPopup('موقعك');
                state.driver.setLatLng(driver);
            }
            const points = [customer, driver].filter(Boolean);
            if (points.length) state.map.fitBounds(points, { padding: [30, 30] });
            setStatus(orderId, data.driver.updated_at ? `آخر تحديث: ${new Date(data.driver.updated_at).toLocaleTimeString('ar')}` : 'ابدأ التوصيل لإرسال موقعك.');
        }

        document.querySelectorAll('[data-start-tracking]').forEach((button) => {
            button.addEventListener('click', () => {
                const orderId = button.dataset.startTracking;
                if (!navigator.geolocation) {
                    document.querySelector(`[data-location-status="${orderId}"]`).textContent = 'المتصفح لا يدعم تحديد الموقع.';
                    return;
                }

                if (watches.has(orderId)) {
                    navigator.geolocation.clearWatch(watches.get(orderId));
                    watches.delete(orderId);
                    button.textContent = 'استئناف مشاركة الموقع';
                    setStatus(orderId, 'تم إيقاف مشاركة الموقع من جهازك.');
                    return;
                }

                button.disabled = true;
                button.textContent = 'جاري تحديد موقعك...';
                watches.set(orderId, navigator.geolocation.watchPosition(async (position) => {
                    const response = await request(`/driver/orders/${orderId}/location`, { method: 'POST', body: JSON.stringify({ latitude: position.coords.latitude, longitude: position.coords.longitude }) });
                    setStatus(orderId, response.ok ? 'تم تحديث موقعك الآن.' : 'تعذر تحديث الموقع.');
                    if (response.ok) {
                        button.disabled = false;
                        button.textContent = 'إيقاف مشاركة الموقع';
                        document.querySelector(`[data-deliver-order="${orderId}"]`).disabled = false;
                        loadOrder(orderId);
                    }
                }, () => {
                    setStatus(orderId, 'يرجى السماح بالوصول إلى الموقع.');
                    button.disabled = false;
                    button.textContent = 'إعادة المحاولة';
                }, { enableHighAccuracy: true, maximumAge: 10000, timeout: 15000 }));
            });
        });

        document.querySelectorAll('[data-deliver-order]').forEach((button) => {
            button.addEventListener('click', async () => {
                if (!window.confirm('هل تريد تأكيد تسليم هذا الطلب؟')) return;
                const orderId = button.dataset.deliverOrder;
                const response = await request(`/driver/orders/${orderId}/status`, { method: 'POST', body: JSON.stringify({ status: 'delivered' }) });
                if (!response.ok) { setStatus(orderId, 'لا يمكن تأكيد التسليم من الحالة الحالية.'); return; }
                if (watches.has(orderId)) navigator.geolocation.clearWatch(watches.get(orderId));
                const card = document.querySelector(`[data-order="${orderId}"]`);
                card.dataset.status = 'delivered';
                card.querySelector('[data-order-status]').textContent = 'تم التسليم';
                card.querySelector('[data-order-status]').className = 'shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700';
                button.disabled = true;
                button.textContent = 'تم التسليم';
                setStatus(orderId, 'تم حفظ التسليم بنجاح.');
            });
        });

        function setStatus(orderId, message) {
            const element = document.querySelector(`[data-location-status="${orderId}"]`);
            if (element) element.textContent = message;
        }

        window.addEventListener('beforeunload', () => watches.forEach((watchId) => navigator.geolocation.clearWatch(watchId)));
    </script>
</body>
</html>