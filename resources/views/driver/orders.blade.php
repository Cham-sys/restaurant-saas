<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>طلبات التوصيل</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 p-4 text-gray-900">
    <main class="mx-auto max-w-xl space-y-4">
        <header class="rounded-2xl bg-white p-5 shadow-sm">
            <h1 class="text-2xl font-black">طلبات التوصيل</h1>
            <p class="mt-1 text-sm text-gray-500">فعّل مشاركة الموقع أثناء التوصيل ليتمكن العميل من متابعتك.</p>
        </header>

        @forelse ($orders as $order)
            <article class="rounded-2xl bg-white p-5 shadow-sm" data-order="{{ $order->id }}">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="font-bold">طلب {{ $order->tracking_code }}</h2>
                        <p class="text-sm text-gray-500">{{ $order->restaurant?->name }}</p>
                        <p class="mt-2 text-sm">{{ $order->delivery_address }}</p>
                    </div>
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-700">{{ $order->status }}</span>
                </div>
                <button type="button" data-start-tracking="{{ $order->id }}" class="mt-4 w-full rounded-xl bg-orange-500 px-4 py-3 font-bold text-white">
                    بدء مشاركة الموقع
                </button>
                <p data-location-status="{{ $order->id }}" class="mt-2 text-center text-sm text-gray-500"></p>
            </article>
        @empty
            <div class="rounded-2xl bg-white p-8 text-center text-gray-500">لا توجد طلبات توصيل حالياً.</div>
        @endforelse
    </main>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const watches = new Map();

        document.querySelectorAll('[data-start-tracking]').forEach((button) => {
            button.addEventListener('click', () => {
                const orderId = button.dataset.startTracking;
                if (!navigator.geolocation) {
                    document.querySelector(`[data-location-status="${orderId}"]`).textContent = 'المتصفح لا يدعم تحديد الموقع.';
                    return;
                }

                button.disabled = true;
                button.textContent = 'جاري مشاركة الموقع...';
                watches.set(orderId, navigator.geolocation.watchPosition(async (position) => {
                    const response = await fetch(`/driver/orders/${orderId}/location`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ latitude: position.coords.latitude, longitude: position.coords.longitude })
                    });
                    document.querySelector(`[data-location-status="${orderId}"]`).textContent = response.ok ? 'تم تحديث موقعك الآن.' : 'تعذر تحديث الموقع.';
                }, () => {
                    document.querySelector(`[data-location-status="${orderId}"]`).textContent = 'يرجى السماح بالوصول إلى الموقع.';
                    button.disabled = false;
                    button.textContent = 'إعادة المحاولة';
                }, { enableHighAccuracy: true, maximumAge: 10000, timeout: 15000 }));
            });
        });
    </script>
</body>
</html>