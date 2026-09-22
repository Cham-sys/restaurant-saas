<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class PwaController extends Controller
{
    /**
     * توليد manifest.json ديناميكياً لكل مطعم
     */
    public function manifest($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // تحديد الأيقونة (شعار المطعم أو أيقونة افتراضية)
        $iconUrl = $restaurant->logo
            ? asset('storage/' . $restaurant->logo)
            : asset('/icons/default-icon.png');

        // بناء الـ Manifest بتصحيح جميع أخطاء Chrome
        $manifest = [
            'name' => $restaurant->name . ' - ' . ($restaurant->description ?? 'طعم لا يُقاوم'),
            'short_name' => $restaurant->name,
            'description' => $restaurant->description ?? 'اطلب أشهى الأطباق مع توصيل سريع',

            // ✅ تصحيح مشكلة Scope: استخدام مسارات نسبية لضمان التطابق التام
            'start_url' => '/' . $restaurant->slug,
            'scope' => '/' . $restaurant->slug . '/',

            'display' => 'standalone',
            'background_color' => $restaurant->background_color ?? '#1A1A1A',
            'theme_color' => $restaurant->primary_color ?? '#FF6B35',
            'orientation' => 'portrait-primary',
            'lang' => 'ar',
            'dir' => 'rtl',
            'categories' => ['food', 'lifestyle'],

            'icons' => [
                [
                    'src' => $iconUrl,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any' // ✅ تم تغييرها من 'any maskable' لتجنب التحذير
                ],
                [
                    'src' => $iconUrl,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any' // ✅ تم تغييرها من 'any maskable' لتجنب التحذير
                ],
            ],

            // ✅ إضافة لقطات الشاشة لحل تحذيرات Richer PWA Install UI
            'screenshots' => [
                [
                    'src' => 'https://via.placeholder.com/1280x720/FF6B35/FFFFFF?text=Desktop+View',
                    'sizes' => '1280x720',
                    'type' => 'image/png',
                    'form_factor' => 'wide' // ✅ لحل تحذير سطح المكتب
                ],
                [
                    'src' => 'https://via.placeholder.com/750x1334/FF6B35/FFFFFF?text=Mobile+View',
                    'sizes' => '750x1334',
                    'type' => 'image/png',
                    'form_factor' => 'narrow' // ✅ لحل تحذير الجوال
                ]
            ]
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Service Worker ديناميكي لكل مطعم
     */
    /**
 * Service Worker ديناميكي لكل مطعم
 */
public function serviceWorker($slug)
{
    $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

    $sw = <<<JS
const CACHE_NAME = '{$restaurant->slug}-v3'; // رفع الإصدار لـ v3 لتطبيق التعديلات
const urlsToCache = [
    '/{$restaurant->slug}',
    '/{$restaurant->slug}/manifest.json',
    'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap'
];

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

// جلب الموارد وتفادي أخطاء الشبكة Uncaught TypeError
self.addEventListener('fetch', event => {
    // معالجة طلبات GET فقط لتجنب التعارض مع طلبات POST أو API
    if (event.request.method !== 'GET') return;

    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                // إضافة catch لمنع انهيار الـ Service Worker عند فشل جلب روابط خارجية
                return fetch(event.request).catch(() => {
                    return new Response('', {
                        status: 408,
                        statusText: 'Network Request Failed'
                    });
                });
            })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});
JS;

    return response($sw, 200)
        ->header('Content-Type', 'application/javascript')
        ->header('Service-Worker-Allowed', '/' . $restaurant->slug . '/');
}
}
