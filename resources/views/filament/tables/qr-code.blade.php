@php
    $url = $record->qrUrl();
@endphp

<div class="flex items-center justify-center">
    <img
        src="https://api.qrserver.com/v1/create-qr-code/?size=96x96&data={{ urlencode($url) }}"
        alt="QR Code للطاولة {{ $record->number }}"
        width="96"
        height="96"
        loading="lazy"
        class="rounded border border-gray-200 bg-white p-1 dark:border-gray-700"
    >
</div>
<p class="mt-1 text-center text-xs text-gray-500">طاولة {{ $record->number }}</p>