<?php

use App\Models\Order;
use App\Models\Restaurant;
use App\Events\OrderStatusChanged;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    public string $slug;
    public string $activeMobileTab = 'new';

    public function mount(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * جلب بيانات المطعم بناءً على الـ slug
     */
    #[Computed]
    public function restaurant()
    {
        return Restaurant::with(['theme', 'categories', 'activeOffers'])
            ->where('slug', $this->slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    /**
     * جلب الطلبات النشطة للمطبخ مع الأصناف
     */
    #[Computed]
    public function orders()
    {
        return Order::with('items')
            ->where('restaurant_id', $this->restaurant->id)
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * الطلبات الجديدة
     */
    #[Computed]
    public function newOrders()
    {
        return $this->orders->filter(fn($order) => in_array($order->status, ['pending', 'confirmed', 'new']));
    }

    /**
     * الطلبات قيد التحضير
     */
    #[Computed]
    public function preparingOrders()
    {
        return $this->orders->where('status', 'preparing');
    }

    /**
     * الطلبات الجاهزة
     */
    #[Computed]
    public function readyOrders()
    {
        return $this->orders->where('status', 'ready');
    }

    /**
     * الإحصائيات
     */
    #[Computed]
    public function stats()
    {
        $all = $this->orders;
        $avgMinutes = $all->count() > 0 
            ? round($all->avg(fn($order) => now()->diffInMinutes($order->created_at)))
            : 0;

        $urgentCount = $all->filter(fn($order) => $order->status !== 'ready' && now()->diffInMinutes($order->created_at) >= 15)->count();

        return [
            'total' => $all->count(),
            'avg_wait' => $avgMinutes,
            'urgent' => $urgentCount,
        ];
    }

    /**
     * تحديث حالة الطلب وبث التغيير
     */
    public function updateOrderStatus(int $orderId, string $newStatus)
    {
        $allowedStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
        
        if (!in_array($newStatus, $allowedStatuses)) {
            return;
        }

        $order = Order::where('restaurant_id', $this->restaurant->id)->find($orderId);

        if ($order) {
            $oldStatus = $order->status;
            
            $updateData = ['status' => $newStatus];
            if ($newStatus === 'completed') {
                $updateData['completed_at'] = now();
            }

            $order->update($updateData);

            // بث التحديث اللحظي
            broadcast(new OrderStatusChanged($order, $oldStatus))->toOthers();
        }
    }

    /**
     * تبديل التبويب للموبايل
     */
    public function setMobileTab(string $tab)
    {
        $this->activeMobileTab = $tab;
    }
};
?>

<div wire:poll.5s class="kds-root" x-data="kdsClient()">
    <!-- المكتبات الخارجية والخطوط الأصلية -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontsource/css/inter@latest/arabic.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontsource/css/inter@latest/latin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-color: #3B82F6;
            --primary-light: #60A5FA;
            --primary-dark: #2563EB;
            --secondary-color: #10B981;
            --secondary-light: #34D399;
            --accent-color: #F59E0B;
            --accent-light: #FBBF24;
            --danger-color: #EF4444;
            --danger-light: #F87171;

            --bg-primary: #FFFFFF;
            --bg-secondary: #F3F4F6;
            --bg-tertiary: #E5E7EB;
            --bg-dark: #1F2937;
            --bg-card: #FFFFFF;
            --bg-card-hover: #F9FAFB;

            --text-primary: #111827;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --text-white: #FFFFFF;

            --border-color: #E5E7EB;
            --border-light: #F3F4F6;
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
            --border-radius-xl: 1rem;
            --border-radius-full: 9999px;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);

            --font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --header-height: 4.5rem;
            --kds-touch-target: 48px;
        }

        .kds-root {
            font-family: var(--font-family);
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            min-height: 100vh;
            direction: rtl;
        }

        /* Header */
        .kds-header {
            position: sticky;
            top: 0;
            height: var(--header-height);
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            z-index: 100;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1.5rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-white);
            font-size: 1.25rem;
        }

        .brand-name {
            font-size: 1.125rem;
            font-weight: 700;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .header-stats {
            display: flex;
            gap: 0.75rem;
            margin-right: auto;
        }

        .stat-chip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-lg);
            font-size: 0.875rem;
            font-weight: 600;
        }

        .stat-chip .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-chip.active .stat-icon { background: rgba(59, 130, 246, 0.1); color: var(--primary-color); }
        .stat-chip.avg-time .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--accent-color); }
        .stat-chip.urgent .stat-icon { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

        .stat-value { font-size: 1.125rem; font-weight: 700; }
        .stat-label { font-size: 0.75rem; color: var(--text-secondary); }

        .header-clock {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 1rem;
            background: var(--bg-dark);
            border-radius: var(--border-radius-lg);
            color: var(--text-white);
            min-width: 110px;
        }

        .clock-time { font-size: 1.25rem; font-weight: 700; }
        .clock-date { font-size: 0.75rem; color: var(--text-light); }

        .header-actions { display: flex; gap: 0.5rem; }

        .icon-btn {
            width: var(--kds-touch-target);
            height: var(--kds-touch-target);
            border-radius: var(--border-radius-lg);
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .icon-btn.active { background: var(--primary-color); color: var(--text-white); }

        /* Board & Columns */
        .kds-board {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            padding: 1rem;
            min-height: calc(100vh - var(--header-height));
        }

        .kds-column {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .kds-column-header {
            padding: 1rem 1.5rem;
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .column-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
            font-weight: 700;
        }

        .column-status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .kds-column.new .column-status-dot { background: var(--primary-color); }
        .kds-column.preparing .column-status-dot { background: var(--accent-color); }
        .kds-column.ready .column-status-dot { background: var(--secondary-color); }

        .column-count {
            background: var(--bg-secondary);
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius-full);
            font-size: 0.875rem;
            font-weight: 700;
            border: 1px solid var(--border-color);
        }

        .kds-column-body {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Order Cards */
        .order-card {
            background: var(--bg-card);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            position: relative;
        }

        .order-card.type-delivery { border-right: 5px solid var(--primary-color); }
        .order-card.type-dine-in { border-right: 5px solid var(--secondary-color); }
        .order-card.type-takeaway { border-right: 5px solid var(--accent-color); }

        .card-header {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-card-hover);
        }

        .order-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius-full);
            font-size: 0.75rem;
            font-weight: 700;
        }

        .order-type-badge.delivery { background: rgba(59, 130, 246, 0.1); color: var(--primary-color); }
        .order-type-badge.dine-in { background: rgba(16, 185, 129, 0.1); color: var(--secondary-color); }
        .order-type-badge.takeaway { background: rgba(245, 158, 11, 0.1); color: var(--accent-color); }

        .order-timer {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-secondary);
        }

        .order-info { padding: 1rem; }
        .order-id-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem; }
        .order-id { font-size: 1.5rem; font-weight: 800; }

        .items-list { list-style: none; padding: 0 1rem 1rem; }
        .item-row { display: flex; align-items: flex-start; gap: 0.5rem; padding: 0.5rem 0; border-bottom: 1px dashed var(--border-light); }
        .item-qty {
            min-width: 32px;
            height: 28px;
            background: var(--primary-color);
            color: #fff;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .item-name { font-weight: 600; font-size: 1rem; }
        .item-note {
            background: rgba(239, 68, 68, 0.08);
            color: var(--danger-color);
            font-size: 0.75rem;
            padding: 2px 0.5rem;
            border-radius: var(--border-radius-sm);
            margin-top: 2px;
            display: inline-block;
        }

        .card-actions { padding: 0.5rem; display: flex; gap: 0.5rem; background: var(--bg-card-hover); border-top: 1px solid var(--border-light); }
        .action-btn {
            flex: 1;
            height: var(--kds-touch-target);
            border-radius: var(--border-radius-lg);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            color: white;
        }

        .action-btn.primary { background: var(--primary-color); }
        .action-btn.success { background: var(--secondary-color); }
        .action-btn.warning { background: var(--accent-color); }
        .action-btn.ghost { background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border-color); }

        .empty-state { text-align: center; padding: 2rem; color: var(--text-light); border: 2px dashed var(--border-color); border-radius: var(--border-radius); }

        @media (max-width: 1024px) {
            .header-stats, .header-clock { display: none; }
            .kds-board { grid-template-columns: 1fr; }
            .kds-column { display: none; }
            .kds-column.mobile-active { display: flex; }
        }
    </style>

    <!-- Header -->
    <header class="kds-header">
        <div class="brand">
            <div class="brand-logo"><i class="fa-solid fa-utensils"></i></div>
            <div class="brand-info">
                <div class="brand-name">{{ $this->restaurant->name }}</div>
                <div class="brand-subtitle">شاشة عرض المطبخ</div>
            </div>
        </div>

        <div class="header-stats">
            <div class="stat-chip active">
                <div class="stat-icon"><i class="fa-solid fa-fire"></i></div>
                <div>
                    <div class="stat-value">{{ $this->stats['total'] }}</div>
                    <div class="stat-label">طلبات نشطة</div>
                </div>
            </div>
            <div class="stat-chip avg-time">
                <div class="stat-icon"><i class="fa-regular fa-clock"></i></div>
                <div>
                    <div class="stat-value">{{ $this->stats['avg_wait'] }} د</div>
                    <div class="stat-label">متوسط الانتظار</div>
                </div>
            </div>
            <div class="stat-chip urgent">
                <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <div class="stat-value">{{ $this->stats['urgent'] }}</div>
                    <div class="stat-label">متأخرة</div>
                </div>
            </div>
        </div>

        <div class="header-clock">
            <div class="clock-time" x-text="time">--:--:--</div>
            <div class="clock-date" x-text="date">--</div>
        </div>

        <div class="header-actions">
            <button @click="toggleSound()" :class="{ 'active': soundEnabled }" class="icon-btn" title="الصوت">
                <i :class="soundEnabled ? 'fa-solid fa-volume-high' : 'fa-solid fa-volume-xmark'"></i>
            </button>
            <button @click="toggleFullscreen()" class="icon-btn" title="ملء الشاشة">
                <i class="fa-solid fa-expand"></i>
            </button>
            <button wire:click="$refresh" class="icon-btn" title="تحديث البيانات">
                <i class="fa-solid fa-rotate-right" wire:loading.class="fa-spin"></i>
            </button>
        </div>
    </header>

    <!-- اللوحة الرئيسية KDS -->
    <main class="kds-board">

        <!-- جديد -->
        <section class="kds-column new {{ $activeMobileTab === 'new' ? 'mobile-active' : '' }}">
            <header class="kds-column-header">
                <div class="column-title">
                    <span class="column-status-dot"></span>
                    <span>طلبات جديدة</span>
                </div>
                <span class="column-count">{{ $this->newOrders->count() }}</span>
            </header>
            <div class="kds-column-body">
                @forelse($this->newOrders as $order)
                    <article class="order-card type-{{ $order->type ?? 'delivery' }}" wire:key="ord-{{ $order->id }}">
                        <header class="card-header">
                            <div class="order-type-badge {{ $order->type ?? 'delivery' }}">
                                <span>{{ $order->type === 'dine-in' ? '🪑 طاولة' : ($order->type === 'takeaway' ? '🛍️ استلام' : '🛵 توصيل') }}</span>
                            </div>
                            <div class="order-timer">
                                <i class="fa-regular fa-clock"></i>
                                <span>منذ {{ round(now()->diffInMinutes($order->created_at)) }} د</span>
                            </div>
                        </header>
                        <div class="order-info">
                            <div class="order-id-row">
                                <h3 class="order-id">#{{ $order->tracking_code ?? $order->id }}</h3>
                            </div>
                            @if($order->customer_name)
                                <div style="font-size:0.85rem; color:var(--text-secondary);">
                                    <i class="fa-regular fa-user"></i> {{ $order->customer_name }}
                                </div>
                            @endif
                        </div>
                        <ul class="items-list">
                            @foreach($order->items as $item)
                                <li class="item-row" wire:key="item-{{ $item->id }}">
                                    <span class="item-qty">{{ $item->quantity ?? $item->qty ?? 1 }}×</span>
                                    <div>
                                        <div class="item-name">{{ $item->name }}</div>
                                        @if($item->notes)
                                            <div class="item-note"><i class="fa-solid fa-triangle-exclamation"></i> {{ $item->notes }}</div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="card-actions">
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'preparing')" class="action-btn primary">
                                <i class="fa-solid fa-play"></i> بدء التحضير
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">لا توجد طلبات جديدة</div>
                @endforelse
            </div>
        </section>

        <!-- قيد التحضير -->
        <section class="kds-column preparing {{ $activeMobileTab === 'preparing' ? 'mobile-active' : '' }}">
            <header class="kds-column-header">
                <div class="column-title">
                    <span class="column-status-dot"></span>
                    <span>جاري التحضير</span>
                </div>
                <span class="column-count">{{ $this->preparingOrders->count() }}</span>
            </header>
            <div class="kds-column-body">
                @forelse($this->preparingOrders as $order)
                    <article class="order-card type-{{ $order->type ?? 'delivery' }}" wire:key="ord-{{ $order->id }}">
                        <header class="card-header">
                            <div class="order-type-badge {{ $order->type ?? 'delivery' }}">
                                <span>{{ $order->type === 'dine-in' ? '🪑 طاولة' : ($order->type === 'takeaway' ? '🛍️ استلام' : '🛵 توصيل') }}</span>
                            </div>
                            <div class="order-timer">
                                <i class="fa-regular fa-clock"></i>
                                <span>منذ {{ round(now()->diffInMinutes($order->created_at)) }} د</span>
                            </div>
                        </header>
                        <div class="order-info">
                            <div class="order-id-row">
                                <h3 class="order-id">#{{ $order->tracking_code ?? $order->id }}</h3>
                            </div>
                        </div>
                        <ul class="items-list">
                            @foreach($order->items as $item)
                                <li class="item-row" wire:key="item-{{ $item->id }}">
                                    <span class="item-qty">{{ $item->quantity ?? $item->qty ?? 1 }}×</span>
                                    <div>
                                        <div class="item-name">{{ $item->name }}</div>
                                        @if($item->notes)
                                            <div class="item-note">{{ $item->notes }}</div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="card-actions">
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'pending')" class="action-btn ghost" title="إرجاع">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'ready')" class="action-btn success">
                                <i class="fa-solid fa-check"></i> جاهز
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">لا توجد طلبات قيد التحضير</div>
                @endforelse
            </div>
        </section>

        <!-- جاهز -->
        <section class="kds-column ready {{ $activeMobileTab === 'ready' ? 'mobile-active' : '' }}">
            <header class="kds-column-header">
                <div class="column-title">
                    <span class="column-status-dot"></span>
                    <span>جاهزة للتسليم</span>
                </div>
                <span class="column-count">{{ $this->readyOrders->count() }}</span>
            </header>
            <div class="kds-column-body">
                @forelse($this->readyOrders as $order)
                    <article class="order-card type-{{ $order->type ?? 'delivery' }}" wire:key="ord-{{ $order->id }}">
                        <header class="card-header">
                            <div class="order-type-badge {{ $order->type ?? 'delivery' }}">
                                <span>{{ $order->type === 'dine-in' ? '🪑 طاولة' : ($order->type === 'takeaway' ? '🛍️ استلام' : '🛵 توصيل') }}</span>
                            </div>
                        </header>
                        <div class="order-info">
                            <h3 class="order-id">#{{ $order->tracking_code ?? $order->id }}</h3>
                        </div>
                        <ul class="items-list">
                            @foreach($order->items as $item)
                                <li class="item-row" wire:key="item-{{ $item->id }}">
                                    <span class="item-qty">{{ $item->quantity ?? $item->qty ?? 1 }}×</span>
                                    <div class="item-name">{{ $item->name }}</div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="card-actions">
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'preparing')" class="action-btn ghost">
                                إرجاع
                            </button>
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" class="action-btn warning">
                                <i class="fa-solid fa-box-archive"></i> إنهاء/أرشفة
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">لا توجد طلبات جاهزة</div>
                @endforelse
            </div>
        </section>

    </main>

    <!-- سكربت تفاعلي خفيف للساعة والصوت وملء الشاشة -->
    <script>
        function kdsClient() {
            return {
                time: '--:--:--',
                date: '--',
                soundEnabled: true,
                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                },
                updateClock() {
                    const now = new Date();
                    this.time = now.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                    this.date = now.toLocaleDateString('ar-SA', { weekday: 'short', month: 'short', day: 'numeric' });
                },
                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                },
                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    } else {
                        document.exitFullscreen().catch(() => {});
                    }
                }
            }
        }
    </script>
</div>