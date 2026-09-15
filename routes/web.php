<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KdsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\Restaurant\ThemeSettingsController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantDashboardController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ThemeController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/{slug}/table/{token}', [RestaurantTableController::class, 'enter'])->name('restaurant.table.menu');
Route::middleware('auth')->get('/restaurant/tables/{table}/qr', [RestaurantTableController::class, 'qr'])->name('restaurant.table.qr');

Route::middleware('auth')->prefix('driver')->group(function () {
    Route::get('/orders', [DriverController::class, 'index'])->name('driver.orders');
    Route::get('/orders/{order}/tracking', [DriverController::class, 'tracking'])->name('driver.order.tracking');
    Route::get('/orders/{order}', [DriverController::class, 'show'])->name('driver.order.show');
    Route::post('/orders/{order}/location', [DriverController::class, 'updateLocation'])->name('driver.location.update');
    Route::post('/orders/{order}/status', [DriverController::class, 'updateStatus'])->name('driver.status.update');
});

Route::middleware(['auth', 'verified'])->get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');

Route::get('/{slug}', [RestaurantController::class, 'home'])->name('restaurant.home');
Route::get('/{slug}/menu', [RestaurantController::class, 'menu'])->name('restaurant.menu');

// نظام السلة
Route::get('/{slug}/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/{slug}/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/{slug}/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/{slug}/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// نظام الطلبات والدفع
Route::get('/{slug}/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/{slug}/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/{slug}/order/success/{code}', [OrderController::class, 'success'])->name('order.success');
Route::get('/{slug}/track/{code}', [OrderController::class, 'track'])->name('order.track');
Route::get('/{slug}/track/{code}/location', [OrderController::class, 'trackingLocation'])->name('order.track.location');

// صفحة إدخال رمز التتبع
Route::get('/{slug}/track', [OrderController::class, 'trackForm'])->name('order.track.form');
Route::post('/{slug}/track', [OrderController::class, 'trackSearch'])->name('order.track.search');

// التقييمات
Route::get('/{slug}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/{slug}/review/{trackingCode}', [ReviewController::class, 'create'])->name('review.create');
Route::post('/{slug}/review/{trackingCode}', [ReviewController::class, 'store'])->name('review.store');

// تطبيق العرض (AJAX)
Route::post('/{slug}/apply-offer', [OrderController::class, 'applyOffer'])->name('offer.apply');

// صفحة الفاتورة
Route::get('/{slug}/invoice/{trackingCode}', [InvoiceController::class, 'showInvoice'])->name('order.invoice');

// PWA Routes ديناميكية لكل مطعم
Route::get('/{slug}/manifest.json', [PwaController::class, 'manifest'])->name('pwa.manifest');
Route::get('/{slug}/sw.js', [PwaController::class, 'serviceWorker'])->name('pwa.sw');

// التحقق من كوبون الخصم (AJAX)
Route::post('/{slug}/apply-coupon', [OrderController::class, 'applyCoupon'])->name('coupon.apply');

Route::get('/{slug}/product/{id}', [RestaurantController::class, 'showProduct'])->name('product.show');
// التحقق من رمز التتبع (AJAX)
Route::post('/review/verify', [ReviewController::class, 'verify'])->name('review.verify');

Route::get('/{slug}/offers', [OfferController::class, 'index'])->name('offers.index');

// إرسال التقييم (AJAX)
Route::post('/review/store-ajax', [ReviewController::class, 'storeAjax'])->name('review.store.ajax');


Route::middleware(['auth'])->prefix('kitchen')->name('kitchen.')->group(function () {
    // عرض شاشة Livewire
    Route::get('/{slug}/display', [KdsController::class , 'index'])->name('display');
    Route::post('/orders/{orderId}/status', [KdsController::class, 'updateStatus'])
    ->name('orders.updateStatus');
});

// مجموعة مسارات API الخاصة بلوحة تحكم المطعم
Route::middleware(['auth'])->prefix('restaurant/api')->group(function () {
    Route::get('/theme/settings', [ThemeSettingsController::class, 'getSettings'])->name('restaurant.theme.settings.get');
    Route::post('/theme/settings', [ThemeSettingsController::class, 'updateSettings'])->name('restaurant.theme.settings.update');
    Route::get('/dashboard/summary', [RestaurantDashboardController::class, 'summary'])->name('restaurant.dashboard.summary');
});
Route::get('themes/{theme}/preview', [ThemeController::class, 'preview'])->name('themes.preview');
Route::post('themes/{theme}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
Route::post('themes/{theme}/clone', [ThemeController::class, 'clone'])->name('themes.clone');
Route::post('themes/{theme}/reset-settings', [ThemeController::class, 'resetSettings'])->name('themes.reset-settings');
Route::resource('themes', ThemeController::class);
require __DIR__.'/settings.php';
