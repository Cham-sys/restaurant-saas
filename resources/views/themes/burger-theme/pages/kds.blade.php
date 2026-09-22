<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شاشة عرض المطبخ | KDS - نظام إدارة الطلبات</title>
    <meta name="description" content="نظام عرض المطبخ - Kitchen Display System لإدارة وتتبع الطلبات">

    <!-- Fontsource Inter (نفس الخط المستخدم في صفحة تتبع الطلبات) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontsource/css/inter@latest/arabic.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontsource/css/inter@latest/latin.css" />

    <!-- Font Awesome للأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('Style')
</head>

<body>
    <!-- ============================================
         رأس الصفحة (Header)
         ============================================ -->
    <header class="kds-header" role="banner">
        <!-- الشعار -->
        <div class="brand">
            <div class="brand-logo" aria-hidden="true">
                <i class="fa-solid fa-utensils"></i>
            </div>

            <div class="brand-info">
                <div class="brand-name">{{ $restaurant->name }}</div>
                <div class="brand-subtitle">شاشة عرض المطبخ</div>
            </div>
        </div>

        <!-- إحصائيات -->
        <div class="header-stats" aria-label="إحصائيات الطلبات">
            <div class="stat-chip active">
                <div class="stat-icon">
                    <i class="fa-solid fa-fire"></i>
                </div>
                <div>
                    <div class="stat-value" id="statActive">0</div>
                    <div class="stat-label">طلبات نشطة</div>
                </div>
            </div>
            <div class="stat-chip avg-time">
                <div class="stat-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <div class="stat-value" id="statAvgTime">0 د</div>
                    <div class="stat-label">متوسط التحضير</div>
                </div>
            </div>
            <div class="stat-chip urgent">
                <div class="stat-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <div class="stat-value" id="statUrgent">0</div>
                    <div class="stat-label">طلبات متأخرة</div>
                </div>
            </div>
        </div>

        <!-- الساعة -->
        <div class="header-clock" aria-live="polite">
            <div class="clock-time" id="clockTime">--:--:--</div>
            <div class="clock-date" id="clockDate">--</div>
        </div>

        <!-- أزرار التحكم -->
        <div class="header-actions">
            <button class="icon-btn active" id="btnSound" aria-label="تشغيل/كتم الصوت" title="الصوت">
                <i class="fa-solid fa-volume-high"></i>
            </button>
            <button class="icon-btn" id="btnFullscreen" aria-label="ملء الشاشة" title="ملء الشاشة">
                <i class="fa-solid fa-expand"></i>
            </button>
            <button class="icon-btn" id="btnAddDemo" aria-label="إضافة طلب تجريبي" title="طلب تجريبي">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </header>

    <!-- ============================================
         لوحة المطبخ (Kanban Board)
         ============================================ -->
    <main class="kds-board" role="main">

        <!-- عمود: طلبات جديدة -->
        <section class="kds-column new" aria-label="الطلبات الجديدة">
            <header class="kds-column-header">
                <div class="column-title">
                    <span class="column-status-dot"></span>
                    <span>طلبات جديدة</span>
                </div>
                <span class="column-count" id="countNew">0</span>
            </header>
            <div class="kds-column-body" id="columnNew">
                <!-- البطاقات تُحقن ديناميكياً -->
            </div>
        </section>

        <!-- عمود: جاري التحضير -->
        <section class="kds-column preparing" aria-label="الطلبات قيد التحضير">
            <header class="kds-column-header">
                <div class="column-title">
                    <span class="column-status-dot"></span>
                    <span>جاري التحضير</span>
                </div>
                <span class="column-count" id="countPreparing">0</span>
            </header>
            <div class="kds-column-body" id="columnPreparing">
                <!-- البطاقات تُحقن ديناميكياً -->
            </div>
        </section>

        <!-- عمود: جاهزة -->
        <section class="kds-column ready" aria-label="الطلبات الجاهزة">
            <header class="kds-column-header">
                <div class="column-title">
                    <span class="column-status-dot"></span>
                    <span>جاهزة</span>
                </div>
                <span class="column-count" id="countReady">0</span>
            </header>
            <div class="kds-column-body" id="columnReady">
                <!-- البطاقات تُحقن ديناميكياً -->
            </div>
        </section>
    </main>

    <!-- حاوية Toast -->
    <div class="toast-container" id="toastContainer" aria-live="polite"></div>

    <!-- شريط الاتصال -->
    <div class="connection-bar" id="connectionBar" role="alert">
        <div class="spinner"></div>
        <span>جاري إعادة الاتصال بالخادم...</span>
    </div>

    <script>
    // 1. حماية البيانات: إذا كانت فارغة، اجعلها مصفوفة فارغة لتجنب الأخطاء
    const initialOrders = @json($initialOrders ?? []);
    
    console.log("🔍 البيانات الواردة من Laravel:", initialOrders); // سطر تشخيصي مهم جداً

    const KDS_CONFIG = {{  json_encode([
        'updateUrlTemplate' => url('/kitchen/'.($restaurant->slug ?? 'default').'/orders/:id/status'),
        'ordersUrl' => url('/kitchen/'.($restaurant->slug ?? 'default').'/orders'),
        'updateInterval' => 30000,
        'urgentThreshold' => 15,
        'restaurantId' => $restaurant->id ?? 1,
        'csrfToken' => csrf_token(),
        'demoMode' => false,
    ])}};

    class KitchenDisplaySystem {
        constructor(config) {
            this.config = config;
            this.orders = new Map();
            this.soundEnabled = true;
            this.audioContext = null;
            this.updateTimer = null;
            this.tickTimer = null;
            this.seenOrderIds = new Set();

            this.columns = {
                new: document.getElementById('columnNew'),
                preparing: document.getElementById('columnPreparing'),
                ready: document.getElementById('columnReady')
            };

            this.counts = {
                new: document.getElementById('countNew'),
                preparing: document.getElementById('countPreparing'),
                ready: document.getElementById('countReady')
            };
        }

        async init() {
            this.initAudio();
            this.setupEventListeners();
            this.startClock();
            this.loadInitialData();
            this.startTimers();

            if (!this.config.demoMode) {
                this.startAutoFetch();
                this.connectWebSocket();
            }
        }

        initAudio() {
            try {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            } catch (e) {
                console.warn('Web Audio API غير مدعوم');
            }
        }

        playDing() {
            if (!this.soundEnabled || !this.audioContext) return;
            try {
                if (this.audioContext.state === 'suspended') this.audioContext.resume();
                const oscillator = this.audioContext.createOscillator();
                const gainNode = this.audioContext.createGain();
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, this.audioContext.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(1320, this.audioContext.currentTime + 0.1);
                gainNode.gain.setValueAtTime(0.3, this.audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.3);
                oscillator.connect(gainNode);
                gainNode.connect(this.audioContext.destination);
                oscillator.start();
                oscillator.stop(this.audioContext.currentTime + 0.3);
            } catch (e) {
                console.warn('فشل تشغيل الصوت:', e);
            }
        }

        setupEventListeners() {
            document.getElementById('btnSound').addEventListener('click', () => {
                this.soundEnabled = !this.soundEnabled;
                const btn = document.getElementById('btnSound');
                const icon = btn.querySelector('i');
                if (this.soundEnabled) {
                    btn.classList.add('active');
                    icon.className = 'fa-solid fa-volume-high';
                    this.showToast('success', 'تم تشغيل الصوت', 'سيتم تنبيهك عند وصول طلبات جديدة');
                    if (this.audioContext?.state === 'suspended') this.audioContext.resume();
                } else {
                    btn.classList.remove('active');
                    icon.className = 'fa-solid fa-volume-xmark';
                    this.showToast('warning', 'تم كتم الصوت', 'لن يتم تنبيهك صوتياً');
                }
            });

            document.getElementById('btnFullscreen').addEventListener('click', () => this.toggleFullscreen());
            document.getElementById('btnAddDemo').addEventListener('click', () => this.addDemoOrder());

            document.addEventListener('keydown', (e) => {
                if (e.target.matches('input, textarea')) return;
                if (e.key === 'f' || e.key === 'F') this.toggleFullscreen();
                if (e.key === 'm' || e.key === 'M') document.getElementById('btnSound').click();
            });
        }

        toggleFullscreen() {
            const btn = document.getElementById('btnFullscreen');
            const icon = btn.querySelector('i');
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    icon.className = 'fa-solid fa-compress';
                }).catch(() => this.showToast('error', 'خطأ', 'تعذر تفعيل ملء الشاشة'));
            } else {
                document.exitFullscreen().then(() => {
                    icon.className = 'fa-solid fa-expand';
                });
            }
        }

        startClock() {
            const updateClock = () => {
                const now = new Date();
                document.getElementById('clockTime').textContent = now.toLocaleTimeString('ar-SA', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                });
                document.getElementById('clockDate').textContent = now.toLocaleDateString('ar-SA', {
                    weekday: 'short', month: 'short', day: 'numeric'
                });
            };
            updateClock();
            setInterval(updateClock, 1000);
        }

        loadInitialData() {
            if (!Array.isArray(initialOrders)) {
                console.error("❌ الخطأ: initialOrders ليس مصفوفة!");
                return;
            }

            initialOrders.forEach(order => {
                // 🔥 تصحيح تلقائي للحالة: إذا كانت pending أو confirmed، حولها لـ new
                let jsStatus = order.status;
                if (jsStatus === 'pending' || jsStatus === 'confirmed') {
                    jsStatus = 'new';
                }

                // 🔥 حماية من خطأ items: إذا كانت null، اجعلها مصفوفة فارغة
                const safeItems = Array.isArray(order.items) ? order.items : [];

                const safeOrder = {
                    ...order,
                    status: jsStatus,
                    items: safeItems,
                    type: order.type || 'delivery' // قيمة افتراضية
                };

                const orderIdStr = safeOrder.id.toString();
                this.orders.set(orderIdStr, safeOrder);
                this.seenOrderIds.add(orderIdStr);
            });
            
            console.log("✅ تم تحميل الطلبات بنجاح:", this.orders.size);
            this.renderAllOrders();
        }

        startAutoFetch() {
            this.updateTimer = setInterval(() => this.fetchOrders(), this.config.updateInterval);
        }

        async fetchOrders() {
            try {
                const response = await fetch(`${this.config.ordersUrl}?t=${Date.now()}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-Token': this.config.csrfToken }
                });
                if (response.ok) {
                    const data = await response.json();
                    this.syncOrders(data.orders);
                }
            } catch (error) {
                console.error('فشل جلب الطلبات:', error);
            }
        }

        connectWebSocket() {
            if (typeof Echo !== 'undefined') {
                Echo.channel(`kds.restaurant.${this.config.restaurantId}`)
                    .listen('.order.status.changed', (e) => {
                        if (!this.seenOrderIds.has(e.order.id.toString())) {
                            this.playDing();
                            this.showToast('info', 'طلب جديد!', `وصل طلب جديد #${e.order.id}`);
                        }
                        this.orders.set(e.order.id.toString(), e.order);
                        this.seenOrderIds.add(e.order.id.toString());
                        this.renderAllOrders();
                    });
            }
        }

        startTimers() {
            this.tickTimer = setInterval(() => {
                this.updateAllTimers();
                this.updateStats();
            }, 30000);
        }

        updateAllTimers() {
            this.orders.forEach((order, id) => {
                const timerEl = document.querySelector(`[data-order-id="${id}"] .order-timer`);
                const card = document.querySelector(`[data-order-id="${id}"]`);
                if (!timerEl) return;

                const elapsed = this.getElapsedMinutes(order.created_at);
                timerEl.innerHTML = `<i class="fa-regular fa-clock"></i> منذ ${elapsed} د`;
                timerEl.classList.remove('warning', 'urgent');
                if (card) card.classList.remove('urgent');

                if (order.status !== 'ready') {
                    if (elapsed >= this.config.urgentThreshold) {
                        timerEl.classList.add('urgent');
                        if (card) card.classList.add('urgent');
                    } else if (elapsed >= this.config.urgentThreshold * 0.7) {
                        timerEl.classList.add('warning');
                    }
                }
            });
        }

        getElapsedMinutes(isoDate) {
            if (!isoDate) return 0;
            return Math.floor((Date.now() - new Date(isoDate).getTime()) / 60000);
        }

        renderAllOrders() {
            Object.values(this.columns).forEach(col => col.innerHTML = '');
            const grouped = { new: [], preparing: [], ready: [] };

            this.orders.forEach(order => {
                if (grouped[order.status]) {
                    grouped[order.status].push(order);
                } else {
                    console.warn(`⚠️ حالة غير معروفة للطلب ${order.id}:`, order.status);
                }
            });

            Object.keys(grouped).forEach(status => {
                grouped[status].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                if (grouped[status].length === 0) {
                    this.columns[status].innerHTML = `<div class="empty-state"><div class="empty-state-icon">📭</div><div class="empty-state-text">لا توجد طلبات</div></div>`;
                } else {
                    grouped[status].forEach(order => this.columns[status].appendChild(this.createOrderCard(order)));
                }
            });
            this.updateStats();
        }

        createOrderCard(order) {
            const card = document.createElement('article');
            card.className = `order-card type-${order.type} status-${order.status}`;
            card.dataset.orderId = order.id;

            const elapsed = this.getElapsedMinutes(order.created_at);
            let timerClass = 'order-timer';
            if (order.status !== 'ready') {
                if (elapsed >= this.config.urgentThreshold) {
                    timerClass += ' urgent';
                    card.classList.add('urgent');
                } else if (elapsed >= this.config.urgentThreshold * 0.7) {
                    timerClass += ' warning';
                }
            }

            const newBadge = (order.status === 'new' && elapsed < 1) ? `<div class="new-badge"><i class="fa-solid fa-circle"></i> جديد</div>` : '';

            let extraInfo = '';
            if (order.type === 'dine-in' && order.table_number) {
                extraInfo = `<div class="table-badge"><i class="fa-solid fa-utensils"></i><span>طاولة ${order.table_number}</span></div>`;
            } else if (order.type === 'delivery') {
                const driverHtml = order.driver_name ? `<i class="fa-solid fa-motorcycle"></i><span>المندوب: ${this.escapeHtml(order.driver_name)}</span>` : `<i class="fa-solid fa-clock"></i><span>بانتظار المندوب</span>`;
                const style = order.driver_name ? '' : 'style="background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2);"';
                extraInfo = `<div class="driver-info" ${style}>${driverHtml}</div>`;
            }

            const typeIcons = { 'dine-in': { icon: '🪑', label: 'طاولة' }, 'takeaway': { icon: '🛍️', label: 'استلام' }, 'delivery': { icon: '🛵', label: 'توصيل' } };
            const typeInfo = typeIcons[order.type] || typeIcons['delivery'];

            const itemsHtml = (order.items || []).map(item => `
                <li class="item-row">
                    <span class="item-qty">${item.qty || 1}×</span>
                    <div class="item-details">
                        <div class="item-name">${this.escapeHtml(item.name)}</div>
                        ${item.notes ? `<div class="item-note"><i class="fa-solid fa-triangle-exclamation"></i><span>${this.escapeHtml(item.notes)}</span></div>` : ''}
                    </div>
                </li>
            `).join('') || '<li class="item-row"><span class="item-details">لا توجد تفاصيل للأصناف</span></li>';

            let actionsHtml = '';
            if (order.status === 'new') {
                actionsHtml = `<button class="action-btn primary" data-action="start" data-order-id="${order.id}"><i class="fa-solid fa-play"></i><span>ابدأ التحضير</span></button>`;
            } else if (order.status === 'preparing') {
                actionsHtml = `<button class="action-btn ghost" data-action="back" data-order-id="${order.id}"><i class="fa-solid fa-rotate-left"></i></button>
                               <button class="action-btn success" data-action="ready" data-order-id="${order.id}"><i class="fa-solid fa-check"></i><span>تم التحضير / جاهز</span></button>`;
            } else if (order.status === 'ready') {
                actionsHtml = `<button class="action-btn ghost" data-action="back" data-order-id="${order.id}"><i class="fa-solid fa-rotate-left"></i><span>إرجاع</span></button>
                               <button class="action-btn warning" data-action="complete" data-order-id="${order.id}"><i class="fa-solid fa-box-archive"></i><span>أرشفة / تم التسليم</span></button>`;
            }

            card.innerHTML = `
                ${newBadge}
                <header class="card-header">
                    <div class="order-type-badge ${order.type}"><span class="type-icon">${typeInfo.icon}</span><span>${typeInfo.label}</span></div>
                    <div class="${timerClass}"><i class="fa-regular fa-clock"></i><span>منذ ${elapsed} د</span></div>
                </header>
                <div class="order-info">
                    <div class="order-id-row"><h3 class="order-id">#${this.escapeHtml(order.display_id || order.id)}</h3>${extraInfo}</div>
                    ${order.customer_name ? `<div class="customer-name"><i class="fa-regular fa-user"></i><span>${this.escapeHtml(order.customer_name)}</span></div>` : ''}
                </div>
                <ul class="items-list">${itemsHtml}</ul>
                <div class="card-actions">${actionsHtml}</div>
            `;

            card.querySelectorAll('[data-action]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.handleAction(btn.dataset.action, btn.dataset.orderId);
                });
            });

            return card;
        }

        async handleAction(action, orderId) {
            const order = this.orders.get(orderId);
            if (!order) return;

            const statusMap = { 'start': 'preparing', 'ready': 'ready', 'complete': 'completed', 'back': this.getPreviousStatus(order.status) };
            const newStatus = statusMap[action];
            if (!newStatus) return;

            const card = document.querySelector(`[data-order-id="${orderId}"]`);
            if (card) {
                card.classList.add('card-exit');
                await new Promise(resolve => setTimeout(resolve, 280));
            }

            try {
                const url = this.config.updateUrlTemplate.replace(':id', orderId);
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-Token': this.config.csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ status: newStatus })
                });

                if (!response.ok) throw new Error('فشل الخادم في تحديث الحالة');

                if (newStatus === 'completed') {
                    this.orders.delete(orderId);
                    this.showToast('success', 'تم التسليم', `تم أرشفة الطلب #${orderId}`);
                } else {
                    order.status = newStatus;
                    this.orders.set(orderId, order);
                    this.showToast('success', 'تم التحديث', `الطلب #${orderId} انتقل إلى: ${this.getStatusLabel(newStatus)}`);
                }
                this.renderAllOrders();
            } catch (error) {
                console.error('Error updating order:', error);
                this.showToast('error', 'خطأ', 'تعذر الاتصال بالخادم. يرجى المحاولة مرة أخرى.');
                this.renderAllOrders();
            }
        }

        getPreviousStatus(currentStatus) {
            const flow = { 'preparing': 'new', 'ready': 'preparing' };
            return flow[currentStatus] || currentStatus;
        }

        updateStats() {
            let active = 0, urgent = 0, totalTime = 0, countForAvg = 0;
            this.orders.forEach(order => {
                if (order.status !== 'completed') {
                    active++;
                    const elapsed = this.getElapsedMinutes(order.created_at);
                    totalTime += elapsed;
                    countForAvg++;
                    if (elapsed >= this.config.urgentThreshold && order.status !== 'ready') urgent++;
                }
            });

            const avgTime = countForAvg > 0 ? Math.round(totalTime / countForAvg) : 0;
            document.getElementById('statActive').textContent = active;
            document.getElementById('statAvgTime').textContent = `${avgTime} د`;
            document.getElementById('statUrgent').textContent = urgent;

            const counts = { new: 0, preparing: 0, ready: 0 };
            this.orders.forEach(order => {
                if (counts.hasOwnProperty(order.status)) counts[order.status]++;
            });
            Object.keys(counts).forEach(status => {
                this.counts[status].textContent = counts[status];
            });
        }

        getStatusLabel(status) {
            const labels = { 'new': 'طلبات جديدة', 'preparing': 'جاري التحضير', 'ready': 'جاهزة' };
            return labels[status] || status;
        }

        addDemoOrder() {
            const types = ['dine-in', 'takeaway', 'delivery'];
            const names = ['أبو سارة', 'ليلى حسن', 'عمر السالم', 'سلمى الخطيب', 'يوسف النجار'];
            const drivers = ['أحمد', 'يوسف', 'محمود', 'كريم', null];
            const tables = [1, 2, 3, 5, 7, 9, 12];
            const itemsList = [{ name: 'برجر كلاسيكي', notes: '' }, { name: 'شاورما لحم', notes: 'حار' }, { name: 'مشاوي مشكلة', notes: '' }, { name: 'كبسة دجاج', notes: '' }, { name: 'فتوش', notes: 'بدون بصل' }, { name: 'بطاطس مقلية', notes: '' }];

            const type = types[Math.floor(Math.random() * types.length)];
            const newOrder = {
                id: 'ORD-' + (9930 + Math.floor(Math.random() * 100)),
                type: type,
                table_number: type === 'dine-in' ? tables[Math.floor(Math.random() * tables.length)] : null,
                customer_name: names[Math.floor(Math.random() * names.length)],
                driver_name: type === 'delivery' ? drivers[Math.floor(Math.random() * drivers.length)] : null,
                items: [],
                status: 'new',
                created_at: new Date().toISOString()
            };

            const itemCount = 1 + Math.floor(Math.random() * 3);
            const usedItems = new Set();
            for (let i = 0; i < itemCount; i++) {
                let item;
                do { item = itemsList[Math.floor(Math.random() * itemsList.length)]; } while (usedItems.has(item.name) && usedItems.size < itemsList.length);
                usedItems.add(item.name);
                newOrder.items.push({ name: item.name, qty: 1 + Math.floor(Math.random() * 3), notes: item.notes });
            }

            this.orders.set(newOrder.id, newOrder);
            this.seenOrderIds.add(newOrder.id);
            this.playDing();
            this.showToast('info', 'طلب جديد!', `وصل طلب جديد #${newOrder.id}`);
            this.renderAllOrders();
        }

        showToast(type, title, message, duration = 4000) {
            const container = document.getElementById('toastContainer');
            const icons = { success: 'fa-solid fa-circle-check', warning: 'fa-solid fa-triangle-exclamation', error: 'fa-solid fa-circle-xmark', info: 'fa-solid fa-circle-info' };
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `<div class="toast-icon"><i class="${icons[type] || icons.info}"></i></div><div class="toast-content"><div class="toast-title">${this.escapeHtml(title)}</div><div class="toast-message">${this.escapeHtml(message)}</div></div>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideInToast 0.3s ease-in reverse forwards';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        destroy() {
            if (this.updateTimer) clearInterval(this.updateTimer);
            if (this.tickTimer) clearInterval(this.tickTimer);
            if (this.audioContext) this.audioContext.close();
        }
    }

    let kds;
    document.addEventListener('DOMContentLoaded', () => {
        kds = new KitchenDisplaySystem(KDS_CONFIG);
        kds.init();
        window.addEventListener('beforeunload', () => kds.destroy());

        const resumeAudio = () => {
            if (kds.audioContext?.state === 'suspended') kds.audioContext.resume();
            document.removeEventListener('click', resumeAudio);
            document.removeEventListener('keydown', resumeAudio);
        };
        document.addEventListener('click', resumeAudio);
        document.addEventListener('keydown', resumeAudio);
    });
</script>
</body>

</html>
