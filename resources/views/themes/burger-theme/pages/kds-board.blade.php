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
    @vite(['resources/js/app.js'])
    <style>
        :root {
            /* ===== الألوان الأساسية - يتم استيرادها من PHP ===== */
            --primary-color: #3B82F6;
            --primary-light: #60A5FA;
            --primary-dark: #2563EB;
            --secondary-color: #10B981;
            --secondary-light: #34D399;
            --accent-color: #F59E0B;
            --accent-light: #FBBF24;
            --danger-color: #EF4444;
            --danger-light: #F87171;
            --success-color: #22C55E;
            --info-color: #06B6D4;

            /* ===== ألوان الخلفيات ===== */
            --bg-primary: #FFFFFF;
            --bg-secondary: #F3F4F6;
            --bg-tertiary: #E5E7EB;
            --bg-dark: #1F2937;
            --bg-darker: #111827;
            --bg-overlay: rgba(0, 0, 0, 0.5);
            --bg-card: #FFFFFF;
            --bg-card-hover: #F9FAFB;

            /* ===== النصوص ===== */
            --text-primary: #111827;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --text-white: #FFFFFF;
            --text-link: #3B82F6;

            /* ===== الحدود والظلال ===== */
            --border-color: #E5E7EB;
            --border-light: #F3F4F6;
            --border-radius-sm: 0.375rem;
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
            --border-radius-xl: 1rem;
            --border-radius-full: 9999px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);

            /* ===== المسافات ===== */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;

            /* ===== الخطوط ===== */
            --font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --font-size-xs: 0.75rem;
            --font-size-sm: 0.875rem;
            --font-size-base: 1rem;
            --font-size-lg: 1.125rem;
            --font-size-xl: 1.25rem;
            --font-size-2xl: 1.5rem;
            --font-size-3xl: 1.875rem;
            --font-size-4xl: 2.25rem;
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-semibold: 600;
            --font-weight-bold: 700;
            --font-weight-extrabold: 800;
            --line-height-tight: 1.25;
            --line-height-normal: 1.5;

            /* ===== الانتقالات ===== */
            --transition-fast: 150ms ease;
            --transition-normal: 300ms ease;
            --transition-slow: 500ms ease;

            /* ===== أبعاد خاصة بـ KDS ===== */
            --header-height: 4.5rem;
            --kds-min-card-width: 320px;
            --kds-touch-target: 48px;
        }

        /* ============================================
           إعادة التعيين والأساسيات
           ============================================ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: var(--font-family);
            font-size: var(--font-size-base);
            line-height: var(--line-height-normal);
            color: var(--text-primary);
            background-color: var(--bg-secondary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        button {
            font-family: inherit;
            cursor: pointer;
            border: none;
            background: none;
            color: inherit;
        }

        /* ============================================
           رأس الصفحة (Header)
           ============================================ */
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
            padding: 0 var(--spacing-lg);
            gap: var(--spacing-lg);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            flex-shrink: 0;
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
            font-size: var(--font-size-xl);
            box-shadow: var(--shadow-md);
        }

        .brand-info {
            display: flex;
            flex-direction: column;
            line-height: var(--line-height-tight);
        }

        /* PHP: <?php echo e($restaurant->name); ?> */
        .brand-name {
            font-size: var(--font-size-lg);
            font-weight: var(--font-weight-bold);
            color: var(--text-primary);
        }

        .brand-subtitle {
            font-size: var(--font-size-xs);
            color: var(--text-light);
            font-weight: var(--font-weight-medium);
        }

        /* إحصائيات الرأس */
        .header-stats {
            display: flex;
            gap: var(--spacing-sm);
            margin-right: auto;
        }

        .stat-chip {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-lg);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-semibold);
        }

        .stat-chip .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--font-size-base);
        }

        .stat-chip.active .stat-icon {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .stat-chip.avg-time .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-color);
        }

        .stat-chip.urgent .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .stat-value {
            font-size: var(--font-size-lg);
            font-weight: var(--font-weight-bold);
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .stat-label {
            font-size: var(--font-size-xs);
            color: var(--text-secondary);
            font-weight: var(--font-weight-normal);
        }

        /* الساعة */
        .header-clock {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--bg-dark);
            border-radius: var(--border-radius-lg);
            color: var(--text-white);
            min-width: 110px;
        }

        .clock-time {
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-bold);
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.5px;
        }

        .clock-date {
            font-size: var(--font-size-xs);
            color: var(--text-light);
        }

        /* أزرار التحكم */
        .header-actions {
            display: flex;
            gap: var(--spacing-sm);
        }

        .icon-btn {
            width: var(--kds-touch-target);
            height: var(--kds-touch-target);
            border-radius: var(--border-radius-lg);
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--font-size-lg);
            color: var(--text-secondary);
            transition: all var(--transition-fast);
        }

        .icon-btn:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border-color: var(--primary-light);
        }

        .icon-btn.active {
            background: var(--primary-color);
            color: var(--text-white);
            border-color: var(--primary-color);
        }

        /* ============================================
           لوحة المطبخ (Kanban Board)
           ============================================ */
        .kds-board {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            min-height: calc(100vh - var(--header-height));
        }

        /* ============================================
           عمود KDS
           ============================================ */
        .kds-column {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
            transition: all var(--transition-normal);
        }

        .kds-column-header {
            padding: var(--spacing-md) var(--spacing-lg);
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .column-title {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: var(--font-size-lg);
            font-weight: var(--font-weight-bold);
            color: var(--text-primary);
        }

        .column-status-dot {
            width: 12px;
            height: 12px;
            border-radius: var(--border-radius-full);
            position: relative;
        }

        .column-status-dot::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: var(--border-radius-full);
            background: currentColor;
            opacity: 0.25;
        }

        .kds-column.new .column-status-dot {
            background: var(--primary-color);
            color: var(--primary-color);
            animation: pulse-dot 2s infinite;
        }

        .kds-column.preparing .column-status-dot {
            background: var(--accent-color);
            color: var(--accent-color);
        }

        .kds-column.ready .column-status-dot {
            background: var(--secondary-color);
            color: var(--secondary-color);
        }

        @keyframes pulse-dot {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
        }

        .column-count {
            background: var(--bg-secondary);
            color: var(--text-secondary);
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--border-radius-full);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-bold);
            min-width: 36px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .kds-column.new .column-count {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            border-color: var(--primary-light);
        }

        .kds-column.preparing .column-count {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-color);
            border-color: var(--accent-light);
        }

        .kds-column.ready .column-count {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
            border-color: var(--secondary-light);
        }

        .kds-column-body {
            flex: 1;
            overflow-y: auto;
            padding: var(--spacing-md);
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .kds-column-body::-webkit-scrollbar {
            width: 6px;
        }

        .kds-column-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .kds-column-body::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: var(--border-radius-full);
        }

        .kds-column-body::-webkit-scrollbar-thumb:hover {
            background: var(--text-light);
        }

        /* حالة فارغة */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-2xl) var(--spacing-md);
            text-align: center;
            color: var(--text-light);
            flex: 1;
            border: 2px dashed var(--border-color);
            border-radius: var(--border-radius-lg);
            margin: var(--spacing-sm);
        }

        .empty-state-icon {
            font-size: var(--font-size-3xl);
            margin-bottom: var(--spacing-sm);
            opacity: 0.4;
        }

        .empty-state-text {
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-medium);
        }

        /* ============================================
           بطاقة الطلب (Order Card)
           ============================================ */
        .order-card {
            background: var(--bg-card);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-normal);
            animation: slideInCard 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        @keyframes slideInCard {
            from {
                opacity: 0;
                transform: translateY(-15px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .order-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        /* ألوان أنواع الطلبات */
        .order-card.type-delivery {
            border-right: 5px solid var(--primary-color);
        }

        .order-card.type-dine-in {
            border-right: 5px solid var(--secondary-color);
        }

        .order-card.type-takeaway {
            border-right: 5px solid var(--accent-color);
        }

        /* وميض للطلبات الجديدة */
        .order-card.status-new {
            animation: slideInCard 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                newOrderPulse 2s ease-in-out 3;
        }

        @keyframes newOrderPulse {

            0%,
            100% {
                box-shadow: var(--shadow-md);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2), var(--shadow-lg);
            }
        }

        /* تنسيق الطوارئ (urgent) */
        .order-card.urgent {
            border-color: var(--danger-color) !important;
            background: linear-gradient(135deg, var(--bg-card) 0%, rgba(239, 68, 68, 0.05) 100%);
            animation: slideInCard 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                urgentPulse 1.5s ease-in-out infinite;
        }

        @keyframes urgentPulse {

            0%,
            100% {
                box-shadow: var(--shadow-md);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.25), var(--shadow-lg);
            }
        }

        /* رأس البطاقة */
        .card-header {
            padding: var(--spacing-md);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--spacing-sm);
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-card-hover);
        }

        .order-type-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--border-radius-full);
            font-size: var(--font-size-xs);
            font-weight: var(--font-weight-bold);
            border: 1px solid;
        }

        .order-type-badge.delivery {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            border-color: var(--primary-light);
        }

        .order-type-badge.dine-in {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
            border-color: var(--secondary-light);
        }

        .order-type-badge.takeaway {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-color);
            border-color: var(--accent-light);
        }

        .order-type-badge .type-icon {
            font-size: var(--font-size-base);
        }

        /* عداد الوقت */
        .order-timer {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: var(--spacing-xs) var(--spacing-sm);
            background: var(--bg-secondary);
            border-radius: var(--border-radius-full);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-bold);
            color: var(--text-secondary);
            font-variant-numeric: tabular-nums;
        }

        .order-timer.warning {
            background: rgba(245, 158, 11, 0.15);
            color: var(--accent-color);
        }

        .order-timer.urgent {
            background: var(--danger-color);
            color: var(--text-white);
            animation: timerPulse 1s infinite;
        }

        @keyframes timerPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* معلومات الطلب */
        .order-info {
            padding: var(--spacing-md);
        }

        .order-id-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--spacing-xs);
        }

        .order-id {
            font-size: var(--font-size-2xl);
            font-weight: var(--font-weight-extrabold);
            color: var(--text-primary);
            letter-spacing: -0.5px;
            font-variant-numeric: tabular-nums;
        }

        .table-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: var(--spacing-xs) var(--spacing-sm);
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
            border-radius: var(--border-radius);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-bold);
            border: 1px solid var(--secondary-light);
        }

        .customer-name {
            font-size: var(--font-size-sm);
            color: var(--text-secondary);
            margin-top: var(--spacing-xs);
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .driver-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            margin-top: var(--spacing-sm);
            padding: var(--spacing-xs) var(--spacing-sm);
            background: rgba(59, 130, 246, 0.08);
            border-radius: var(--border-radius);
            font-size: var(--font-size-sm);
            color: var(--primary-color);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        /* قائمة الأصناف */
        .items-list {
            list-style: none;
            padding: 0 var(--spacing-md) var(--spacing-md);
        }

        .item-row {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) 0;
            border-bottom: 1px dashed var(--border-light);
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-qty {
            flex-shrink: 0;
            min-width: 36px;
            height: 28px;
            background: var(--primary-color);
            color: var(--text-white);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-bold);
        }

        .order-card.type-dine-in .item-qty {
            background: var(--secondary-color);
        }

        .order-card.type-takeaway .item-qty {
            background: var(--accent-color);
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-semibold);
            color: var(--text-primary);
            line-height: var(--line-height-tight);
        }

        /* تنسيق ملاحظات الصنف */
        .item-note {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            margin-top: var(--spacing-xs);
            padding: 2px var(--spacing-sm);
            background: rgba(239, 68, 68, 0.08);
            color: var(--danger-color);
            border-radius: var(--border-radius-sm);
            font-size: var(--font-size-xs);
            font-weight: var(--font-weight-semibold);
            font-style: italic;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .item-note i {
            font-size: 0.625rem;
        }

        /* ذيل البطاقة (Actions) */
        .card-actions {
            padding: var(--spacing-sm);
            display: flex;
            gap: var(--spacing-sm);
            background: var(--bg-card-hover);
            border-top: 1px solid var(--border-light);
        }

        .action-btn {
            flex: 1;
            min-height: var(--kds-touch-target);
            padding: 0 var(--spacing-md);
            border-radius: var(--border-radius-lg);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-bold);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-xs);
            transition: all var(--transition-fast);
            border: 2px solid transparent;
        }

        .action-btn:active {
            transform: scale(0.96);
        }

        .action-btn.primary {
            background: var(--primary-color);
            color: var(--text-white);
        }

        .action-btn.primary:hover {
            background: var(--primary-dark);
            box-shadow: var(--shadow-md);
        }

        .action-btn.success {
            background: var(--secondary-color);
            color: var(--text-white);
        }

        .action-btn.success:hover {
            background: #059669;
            box-shadow: var(--shadow-md);
        }

        .action-btn.warning {
            background: var(--accent-color);
            color: var(--text-white);
        }

        .action-btn.warning:hover {
            background: #D97706;
            box-shadow: var(--shadow-md);
        }

        .action-btn.ghost {
            background: var(--bg-secondary);
            color: var(--text-secondary);
            border-color: var(--border-color);
        }

        .action-btn.ghost:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        /* ============================================
           Toast Notifications
           ============================================ */
        .toast-container {
            position: fixed;
            top: calc(var(--header-height) + var(--spacing-md));
            left: var(--spacing-md);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
            max-width: 380px;
        }

        .toast {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-right: 4px solid var(--primary-color);
            border-radius: var(--border-radius-lg);
            padding: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            box-shadow: var(--shadow-xl);
            animation: slideInToast 0.3s ease-out;
        }

        .toast.success {
            border-right-color: var(--secondary-color);
        }

        .toast.warning {
            border-right-color: var(--accent-color);
        }

        .toast.error {
            border-right-color: var(--danger-color);
        }

        @keyframes slideInToast {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--font-size-lg);
            flex-shrink: 0;
        }

        .toast.success .toast-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
        }

        .toast.warning .toast-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-color);
        }

        .toast.error .toast-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .toast.info .toast-icon {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .toast-content {
            flex: 1;
            min-width: 0;
        }

        .toast-title {
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-bold);
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .toast-message {
            font-size: var(--font-size-xs);
            color: var(--text-secondary);
        }

        /* ============================================
           التجاوب (Responsive)
           ============================================ */
        @media (max-width: 1280px) {
            .header-stats {
                display: none;
            }
        }

        @media (max-width: 1024px) {
            .kds-board {
                grid-template-columns: 1fr;
                grid-auto-rows: min-content;
            }

            .kds-column {
                max-height: 70vh;
            }

            .header-clock {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .kds-header {
                padding: 0 var(--spacing-md);
                gap: var(--spacing-sm);
            }

            .brand-info {
                display: none;
            }

            .kds-board {
                padding: var(--spacing-sm);
                gap: var(--spacing-sm);
            }

            .kds-column-body {
                padding: var(--spacing-sm);
            }

            .order-id {
                font-size: var(--font-size-xl);
            }
        }

        /* ============================================
           تأثيرات إضافية
           ============================================ */
        .card-exit {
            animation: cardExit 0.3s ease-in forwards;
        }

        @keyframes cardExit {
            to {
                opacity: 0;
                transform: scale(0.9) translateY(-10px);
            }
        }

        /* شارة "جديد" */
        .new-badge {
            position: absolute;
            top: var(--spacing-sm);
            left: var(--spacing-sm);
            background: var(--primary-color);
            color: var(--text-white);
            padding: 2px var(--spacing-sm);
            border-radius: var(--border-radius-full);
            font-size: var(--font-size-xs);
            font-weight: var(--font-weight-bold);
            display: flex;
            align-items: center;
            gap: 4px;
            z-index: 2;
            animation: pulse-dot 1.5s infinite;
        }

        .new-badge i {
            font-size: 0.5rem;
        }

        /* اتصال منقطع */
        .connection-bar {
            position: fixed;
            bottom: var(--spacing-md);
            left: var(--spacing-md);
            right: var(--spacing-md);
            background: var(--danger-color);
            color: var(--text-white);
            padding: var(--spacing-sm) var(--spacing-md);
            border-radius: var(--border-radius-lg);
            display: none;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            font-weight: var(--font-weight-semibold);
            z-index: 500;
            box-shadow: var(--shadow-xl);
        }

        .connection-bar.visible {
            display: flex;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: var(--border-radius-full);
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
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
        // ============================================
        // التكوين - يتم حقنه من Laravel
        // ============================================
        const KDS_CONFIG = {
            apiEndpoint: "{{ route('kitchen.display', $restaurant->id) }}",
            websocketUrl: true, // سيتم الاعتماد على Laravel Echo المدمج
            updateInterval: 10000,
            urgentThreshold: 15,
            restaurantId: {{ $orderCollection->first()->restaurant_id ?? $restaurant->id }},
            csrfToken: "{{ csrf_token() }}",
            demoMode: false // إيقاف البيانات التجريبية
        };

        // ============================================
        // بيانات تجريبية (Mock Orders)
        // Laravel: ستأتي من Order::with('items')->where('status', '!=', 'completed')
        // ============================================
        const mockOrders = [{
                id: 'ORD-9921',
                type: 'delivery',
                table_number: null,
                customer_name: 'محمد علي',
                driver_name: 'أحمد',
                items: [{
                        name: 'برجر دبل',
                        qty: 2,
                        notes: 'بدون بصل'
                    },
                    {
                        name: 'بطاطس مقلية',
                        qty: 1,
                        notes: ''
                    }
                ],
                status: 'new',
                created_at: new Date(Date.now() - 2 * 60 * 1000).toISOString()
            },
            {
                id: 'ORD-9922',
                type: 'dine-in',
                table_number: 7,
                customer_name: 'عائلة الأحمد',
                driver_name: null,
                items: [{
                        name: 'مشاوي مشكلة',
                        qty: 1,
                        notes: 'حار'
                    },
                    {
                        name: 'فتوش',
                        qty: 2,
                        notes: ''
                    },
                    {
                        name: 'لبنة',
                        qty: 1,
                        notes: ''
                    },
                    {
                        name: 'عصير ليمون بالنعناع',
                        qty: 3,
                        notes: 'قليل السكر'
                    }
                ],
                status: 'new',
                created_at: new Date(Date.now() - 5 * 60 * 1000).toISOString()
            },
            {
                id: 'ORD-9923',
                type: 'takeaway',
                table_number: null,
                customer_name: 'فاطمة الخالد',
                driver_name: null,
                items: [{
                        name: 'شاورما عربية دجاج',
                        qty: 3,
                        notes: ''
                    },
                    {
                        name: 'كولا 330مل',
                        qty: 3,
                        notes: ''
                    }
                ],
                status: 'preparing',
                created_at: new Date(Date.now() - 8 * 60 * 1000).toISOString()
            },
            {
                id: 'ORD-9924',
                type: 'delivery',
                table_number: null,
                customer_name: 'خالد المصري',
                driver_name: 'يوسف',
                items: [{
                        name: 'كبسة لحم',
                        qty: 2,
                        notes: ''
                    },
                    {
                        name: 'سلطة خضار',
                        qty: 1,
                        notes: 'بدون بصل'
                    }
                ],
                status: 'preparing',
                created_at: new Date(Date.now() - 18 * 60 * 1000).toISOString() // متأخر!
            },
            {
                id: 'ORD-9925',
                type: 'dine-in',
                table_number: 3,
                customer_name: 'أبو يوسف',
                driver_name: null,
                items: [{
                        name: 'كبة مشوية',
                        qty: 6,
                        notes: ''
                    },
                    {
                        name: 'متبل',
                        qty: 1,
                        notes: ''
                    }
                ],
                status: 'ready',
                created_at: new Date(Date.now() - 22 * 60 * 1000).toISOString()
            }
        ];

        // ============================================
        // Class: KitchenDisplaySystem
        // ============================================
        class KitchenDisplaySystem {
            constructor(config) {
                this.config = config;
                this.orders = new Map();
                this.soundEnabled = true;
                this.audioContext = null;
                this.updateTimer = null;
                this.tickTimer = null;
                this.websocket = null;
                this.seenOrderIds = new Set();

                // عناصر DOM
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

            // ========== التهيئة ==========
            async init() {
                this.initAudio();
                this.setupEventListeners();
                this.startClock();
                this.loadInitialData();
                this.startTimers();

                if (!this.config.demoMode) {
                    this.startAutoFetch();
                    if (this.config.websocketUrl) {
                        this.connectWebSocket();
                    }
                }
            }

            // ========== تهيئة الصوت ==========
            initAudio() {
                try {
                    this.audioContext = new(window.AudioContext || window.webkitAudioContext)();
                } catch (e) {
                    console.warn('Web Audio API غير مدعوم');
                }
            }

            // ========== تنبيه صوتي (Ding) ==========
            playDing() {
                if (!this.soundEnabled || !this.audioContext) return;

                try {
                    if (this.audioContext.state === 'suspended') {
                        this.audioContext.resume();
                    }

                    // نغمة Ding قصيرة
                    const oscillator = this.audioContext.createOscillator();
                    const gainNode = this.audioContext.createGain();

                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(880, this.audioContext.currentTime);
                    oscillator.frequency.exponentialRampToValueAtTime(
                        1320, this.audioContext.currentTime + 0.1
                    );

                    gainNode.gain.setValueAtTime(0.3, this.audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(
                        0.01, this.audioContext.currentTime + 0.3
                    );

                    oscillator.connect(gainNode);
                    gainNode.connect(this.audioContext.destination);

                    oscillator.start();
                    oscillator.stop(this.audioContext.currentTime + 0.3);
                } catch (e) {
                    console.warn('فشل تشغيل الصوت:', e);
                }
            }

            // ========== مستمعات الأحداث ==========
            setupEventListeners() {
                // زر الصوت
                document.getElementById('btnSound').addEventListener('click', () => {
                    this.soundEnabled = !this.soundEnabled;
                    const btn = document.getElementById('btnSound');
                    const icon = btn.querySelector('i');

                    if (this.soundEnabled) {
                        btn.classList.add('active');
                        icon.className = 'fa-solid fa-volume-high';
                        this.showToast('success', 'تم تشغيل الصوت', 'سيتم تنبيهك عند وصول طلبات جديدة');
                        if (this.audioContext?.state === 'suspended') {
                            this.audioContext.resume();
                        }
                    } else {
                        btn.classList.remove('active');
                        icon.className = 'fa-solid fa-volume-xmark';
                        this.showToast('warning', 'تم كتم الصوت', 'لن يتم تنبيهك صوتياً');
                    }
                });

                // زر ملء الشاشة
                document.getElementById('btnFullscreen').addEventListener('click', () => {
                    this.toggleFullscreen();
                });

                // زر إضافة طلب تجريبي
                document.getElementById('btnAddDemo').addEventListener('click', () => {
                    this.addDemoOrder();
                });

                // اختصارات لوحة المفاتيح
                document.addEventListener('keydown', (e) => {
                    if (e.target.matches('input, textarea')) return;

                    if (e.key === 'f' || e.key === 'F') {
                        this.toggleFullscreen();
                    }
                    if (e.key === 'm' || e.key === 'M') {
                        document.getElementById('btnSound').click();
                    }
                });
            }

            // ========== ملء الشاشة ==========
            toggleFullscreen() {
                const btn = document.getElementById('btnFullscreen');
                const icon = btn.querySelector('i');

                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().then(() => {
                        icon.className = 'fa-solid fa-compress';
                    }).catch(() => {
                        this.showToast('error', 'خطأ', 'تعذر تفعيل ملء الشاشة');
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        icon.className = 'fa-solid fa-expand';
                    });
                }
            }

            // ========== الساعة ==========
            startClock() {
                const updateClock = () => {
                    const now = new Date();
                    const time = now.toLocaleTimeString('ar-SA', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });
                    const date = now.toLocaleDateString('ar-SA', {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric'
                    });

                    document.getElementById('clockTime').textContent = time;
                    document.getElementById('clockDate').textContent = date;
                };

                updateClock();
                setInterval(updateClock, 1000);
            }

            // ========== تحميل البيانات الأولية ==========
            loadInitialData() {
                if (this.config.demoMode) {
                    mockOrders.forEach(order => {
                        this.orders.set(order.id, order);
                        this.seenOrderIds.add(order.id);
                    });
                    this.renderAllOrders();
                    return;
                }

                // حقن البيانات القادمة من View لارافيل فوراً
                const initialOrders = @json(\App\Http\Resources\KdsOrderResource::collection($orderCollection));

                initialOrders.forEach(order => {
                    this.orders.set(order.id, order);
                    this.seenOrderIds.add(order.id);
                });

                this.renderAllOrders();


                /**
                 * ============================================
                 * Laravel Integration - Fetch API
                 * ============================================
                 * 
                 * fetch(`${this.config.apiEndpoint}?restaurant_id=${this.config.restaurantId}`, {
                 *     method: 'GET',
                 *     headers: {
                 *         'Accept': 'application/json',
                 *         'X-Requested-With': 'XMLHttpRequest',
                 *         'X-CSRF-Token': this.config.csrfToken
                 *     }
                 * })
                 * .then(response => response.json())
                 * .then(data => {
                 *     data.orders.forEach(order => {
                 *         this.orders.set(order.id, order);
                 *         this.seenOrderIds.add(order.id);
                 *     });
                 *     this.renderAllOrders();
                 * })
                 * .catch(error => {
                 *     console.error('فشل جلب الطلبات:', error);
                 *     this.handleConnectionError();
                 * });
                 */
            }

            // ========== بدء التحديث التلقائي ==========
            startAutoFetch() {
                this.updateTimer = setInterval(() => {
                    this.fetchOrders();
                }, this.config.updateInterval);
            }

            // ========== جلب الطلبات ==========
            async fetchOrders() {
                /**
                 * Laravel Integration:
                 * 
                 * try {
                 *     const response = await fetch(
                 *         `${this.config.apiEndpoint}?restaurant_id=${this.config.restaurantId}&t=${Date.now()}`,
                 *         {
                 *             headers: {
                 *                 'Accept': 'application/json',
                 *                 'X-CSRF-Token': this.config.csrfToken
                 *             }
                 *         }
                 *     );
                 *     const data = await response.json();
                 *     this.syncOrders(data.orders);
                 * } catch (error) {
                 *     this.handleConnectionError();
                 * }
                 */
            }

            // ========== WebSocket (Laravel Reverb) ==========
            connectWebSocket() {
                // إذا لم تجهز مكتبة Echo بعد، انتظر 200 ملي ثانية وأعد المحاولة تلقائياً
                if (typeof window.Echo === 'undefined') {
                    setTimeout(() => this.connectWebSocket(), 200);
                    console.error("خطأ في اتصال ريفيرب");
                    return;
                }

                // بمجرد توفر Echo يتم الاتصال بالقناة
                window.Echo.private(`restaurant.${this.config.restaurantId}.kds`)
                    .listen('.order.created', (event) => {
                        this.handleNewOrder(event.order);
                    });
            }

            // ========== بدء المؤقتات ==========
            startTimers() {
                // تحديث المؤقتات كل 30 ثانية
                this.tickTimer = setInterval(() => {
                    this.updateAllTimers();
                    this.updateStats();
                }, 30000);
            }

            // ========== تحديث جميع المؤقتات ==========
            updateAllTimers() {
                this.orders.forEach((order, id) => {
                    const timerEl = document.querySelector(`[data-order-id="${id}"] .order-timer`);
                    if (!timerEl) return;

                    const elapsed = this.getElapsedMinutes(order.created_at);
                    const card = document.querySelector(`[data-order-id="${id}"]`);

                    // تحديث النص
                    timerEl.innerHTML = `<i class="fa-regular fa-clock"></i> منذ ${elapsed} د`;

                    // تحديث حالة الطوارئ
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

            // ========== حساب الوقت المنقضي (بالدقائق) ==========
            getElapsedMinutes(isoDate) {
                const created = new Date(isoDate).getTime();
                const now = Date.now();
                return Math.floor((now - created) / 60000);
            }

            // ========== عرض جميع الطلبات ==========
            renderAllOrders() {
                // مسح الأعمدة
                Object.values(this.columns).forEach(col => col.innerHTML = '');

                // تجميع الطلبات حسب الحالة
                const grouped = {
                    new: [],
                    preparing: [],
                    ready: []
                };
                this.orders.forEach(order => {
                    if (grouped[order.status]) {
                        grouped[order.status].push(order);
                    }
                });

                // ترتيب حسب الوقت (الأقدم أولاً)
                Object.keys(grouped).forEach(status => {
                    grouped[status].sort((a, b) =>
                        new Date(a.created_at) - new Date(b.created_at)
                    );
                });

                // عرض الطلبات
                grouped.new.forEach(order => this.columns.new.appendChild(this.createOrderCard(order)));
                grouped.preparing.forEach(order => this.columns.preparing.appendChild(this.createOrderCard(order)));
                grouped.ready.forEach(order => this.columns.ready.appendChild(this.createOrderCard(order)));

                // حالات فارغة
                Object.keys(this.columns).forEach(status => {
                    if (grouped[status].length === 0) {
                        this.columns[status].innerHTML = `
                            <div class="empty-state">
                                <div class="empty-state-icon">📭</div>
                                <div class="empty-state-text">لا توجد طلبات</div>
                            </div>
                        `;
                    }
                });

                this.updateStats();
            }

            // ========== إنشاء بطاقة طلب ==========
            createOrderCard(order) {
                const card = document.createElement('article');
                card.className = `order-card type-${order.type} status-${order.status}`;
                card.dataset.orderId = order.id;
                card.setAttribute('role', 'article');
                card.setAttribute('aria-label', `طلب رقم ${order.id}`);

                const elapsed = this.getElapsedMinutes(order.created_at);

                // تحديد فئة المؤقت
                let timerClass = 'order-timer';
                if (order.status !== 'ready') {
                    if (elapsed >= this.config.urgentThreshold) {
                        timerClass += ' urgent';
                        card.classList.add('urgent');
                    } else if (elapsed >= this.config.urgentThreshold * 0.7) {
                        timerClass += ' warning';
                    }
                }

                // شارة "جديد" للطلبات الحديثة
                const newBadge = order.status === 'new' && elapsed < 1 ?
                    `<div class="new-badge"><i class="fa-solid fa-circle"></i> جديد</div>` :
                    '';

                // معلومات إضافية حسب النوع
                let extraInfo = '';
                if (order.type === 'dine-in' && order.table_number) {
                    extraInfo = `
                        <div class="table-badge">
                            <i class="fa-solid fa-utensils"></i>
                            <span>طاولة ${order.table_number}</span>
                        </div>
                    `;
                } else if (order.type === 'delivery') {
                    if (order.driver_name) {
                        extraInfo = `
                            <div class="driver-info">
                                <i class="fa-solid fa-motorcycle"></i>
                                <span>المندوب: ${this.escapeHtml(order.driver_name)}</span>
                            </div>
                        `;
                    } else {
                        extraInfo = `
                            <div class="driver-info" style="background: rgba(239, 68, 68, 0.08); color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2);">
                                <i class="fa-solid fa-clock"></i>
                                <span>بانتظار المندوب</span>
                            </div>
                        `;
                    }
                }

                // أيقونة النوع
                const typeIcons = {
                    'dine-in': {
                        icon: '🪑',
                        label: 'طاولة'
                    },
                    'takeaway': {
                        icon: '🛍️',
                        label: 'استلام'
                    },
                    'delivery': {
                        icon: '🛵',
                        label: 'توصيل'
                    }
                };
                const typeInfo = typeIcons[order.type];

                // الأصناف
                const itemsHtml = order.items.map(item => {
                    const itemName = item.name || item.product_name || 'منتج';
                    const itemQty = item.qty || item.quantity || 1;

                    return `
                        <li class="item-row">
                            <span class="item-qty">${itemQty}×</span>
                            <div class="item-details">
                                <div class="item-name">${this.escapeHtml(itemName)}</div>
                                ${item.notes ? `
                                        <div class="item-note">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            <span>${this.escapeHtml(item.notes)}</span>
                                        </div>
                                    ` : ''}
                            </div>
                        </li>
                    `;
                }).join('');

                // أزرار حسب الحالة
                let actionsHtml = '';
                if (order.status === 'new') {
                    actionsHtml = `
                        <button class="action-btn primary" data-action="start" data-order-id="${order.id}">
                            <i class="fa-solid fa-play"></i>
                            <span>ابدأ التحضير</span>
                        </button>
                    `;
                } else if (order.status === 'preparing') {
                    actionsHtml = `
                        <button class="action-btn ghost" data-action="back" data-order-id="${order.id}">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                        <button class="action-btn success" data-action="ready" data-order-id="${order.id}">
                            <i class="fa-solid fa-check"></i>
                            <span>تم التحضير / جاهز</span>
                        </button>
                    `;
                } else if (order.status === 'ready') {
                    actionsHtml = `
                        <button class="action-btn ghost" data-action="back" data-order-id="${order.id}">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>إرجاع</span>
                        </button>
                        <button class="action-btn warning" data-action="complete" data-order-id="${order.id}">
                            <i class="fa-solid fa-box-archive"></i>
                            <span>أرشفة / تم التسليم</span>
                        </button>
                    `;
                }

                card.innerHTML = `
                    ${newBadge}
                    <header class="card-header">
                        <div class="order-type-badge ${order.type}">
                            <span class="type-icon">${typeInfo.icon}</span>
                            <span>${typeInfo.label}</span>
                        </div>
                        <div class="${timerClass}">
                            <i class="fa-regular fa-clock"></i>
                            <span>منذ ${elapsed} د</span>
                        </div>
                    </header>

                    <div class="order-info">
                        <div class="order-id-row">
                            <h3 class="order-id">#${order.id}</h3>
                            ${extraInfo}
                        </div>
                        ${order.customer_name ? `
                                            <div class="customer-name">
                                                <i class="fa-regular fa-user"></i>
                                                <span>${this.escapeHtml(order.customer_name)}</span>
                                            </div>
                                        ` : ''}
                    </div>

                    <ul class="items-list">
                        ${itemsHtml}
                    </ul>

                    <div class="card-actions">
                        ${actionsHtml}
                    </div>
                `;

                // ربط أزرار الإجراءات
                card.querySelectorAll('[data-action]').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const action = btn.dataset.action;
                        const orderId = btn.dataset.orderId;
                        this.handleAction(action, orderId);
                    });
                });

                return card;
            }

            // ========== معالجة الإجراءات ==========

            async handleAction(action, orderId) {
                const order = this.orders.get(orderId);
                if (!order) return;

                const statusMap = {
                    'start': 'preparing',
                    'ready': 'ready',
                    'complete': 'completed',
                    'back': this.getPreviousStatus(order.status)
                };

                const newStatus = statusMap[action];
                if (!newStatus) return;

                // استخدام المعرف العددي الخاص بالـ DB إذا كان متوفراً
                const targetId = order.db_id || orderId;

                try {
                    const response = await fetch(`/kitchen/orders/${targetId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.config.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    });

                    if (!response.ok) throw new Error('فشل التحديث في السيرفر');

                    if (newStatus === 'completed') {
                        this.orders.delete(orderId);
                        this.showToast('success', 'تم التسليم', `تم أرشفة الطلب #${orderId} بنجاح`);
                    } else {
                        order.status = newStatus;
                        this.orders.set(orderId, order);
                        const statusLabels = {
                            'new': 'طلبات جديدة',
                            'preparing': 'جاري التحضير',
                            'ready': 'جاهزة'
                        };
                        this.showToast('success', 'تم التحديث', `الطلب #${orderId} ← ${statusLabels[newStatus]}`);
                    }

                    this.renderAllOrders();

                } catch (error) {
                    console.error('Error updating status:', error);
                    this.showToast('error', 'خطأ', 'تعذر تحديث حالة الطلب في السيرفر');
                }
            }

            // ========== الحصول على الحالة السابقة ==========
            getPreviousStatus(currentStatus) {
                const flow = {
                    'preparing': 'new',
                    'ready': 'preparing'
                };
                return flow[currentStatus] || currentStatus;
            }

            // ========== معالجة طلب جديد ==========
            handleNewOrder(order) {
                if (this.seenOrderIds.has(order.id)) {
                    this.orders.set(order.id, order);
                } else {
                    this.orders.set(order.id, order);
                    this.seenOrderIds.add(order.id);
                    this.playDing();
                    this.showToast('info', 'طلب جديد!', `وصل طلب جديد #${order.id}`);
                }
                this.renderAllOrders();
            }

            // ========== تحديث الإحصائيات ==========
            updateStats() {
                let active = 0;
                let urgent = 0;
                let totalTime = 0;
                let countForAvg = 0;

                this.orders.forEach(order => {
                    if (order.status !== 'completed') {
                        active++;
                        const elapsed = this.getElapsedMinutes(order.created_at);
                        totalTime += elapsed;
                        countForAvg++;

                        if (elapsed >= this.config.urgentThreshold && order.status !== 'ready') {
                            urgent++;
                        }
                    }
                });

                const avgTime = countForAvg > 0 ? Math.round(totalTime / countForAvg) : 0;

                document.getElementById('statActive').textContent = active;
                document.getElementById('statAvgTime').textContent = `${avgTime} د`;
                document.getElementById('statUrgent').textContent = urgent;

                // تحديث العدادات
                const counts = {
                    new: 0,
                    preparing: 0,
                    ready: 0
                };
                this.orders.forEach(order => {
                    if (counts.hasOwnProperty(order.status)) {
                        counts[order.status]++;
                    }
                });

                Object.keys(counts).forEach(status => {
                    this.counts[status].textContent = counts[status];
                });
            }

            // ========== إضافة طلب تجريبي ==========
            addDemoOrder() {
                const types = ['dine-in', 'takeaway', 'delivery'];
                const names = ['أبو سارة', 'ليلى حسن', 'عمر السالم', 'سلمى الخطيب', 'يوسف النجار'];
                const drivers = ['أحمد', 'يوسف', 'محمود', 'كريم', null];
                const tables = [1, 2, 3, 5, 7, 9, 12];
                const items = [{
                        name: 'برجر كلاسيكي',
                        notes: ''
                    },
                    {
                        name: 'شاورما لحم',
                        notes: 'حار'
                    },
                    {
                        name: 'مشاوي مشكلة',
                        notes: ''
                    },
                    {
                        name: 'كبسة دجاج',
                        notes: ''
                    },
                    {
                        name: 'فتوش',
                        notes: 'بدون بصل'
                    },
                    {
                        name: 'بطاطس مقلية',
                        notes: ''
                    },
                    {
                        name: 'بيتزا مارغريتا',
                        notes: 'حجم عائلي'
                    },
                    {
                        name: 'عصير برتقال',
                        notes: 'طبيعي'
                    }
                ];

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

                // إضافة 1-4 أصناف عشوائية
                const itemCount = 1 + Math.floor(Math.random() * 4);
                const usedItems = new Set();
                for (let i = 0; i < itemCount; i++) {
                    let item;
                    do {
                        item = items[Math.floor(Math.random() * items.length)];
                    } while (usedItems.has(item.name) && usedItems.size < items.length);
                    usedItems.add(item.name);
                    newOrder.items.push({
                        name: item.name,
                        qty: 1 + Math.floor(Math.random() * 3),
                        notes: item.notes
                    });
                }

                this.handleNewOrder(newOrder);
            }

            // ========== Toast Notifications ==========
            showToast(type, title, message, duration = 4000) {
                const container = document.getElementById('toastContainer');
                const icons = {
                    success: 'fa-solid fa-circle-check',
                    warning: 'fa-solid fa-triangle-exclamation',
                    error: 'fa-solid fa-circle-xmark',
                    info: 'fa-solid fa-circle-info'
                };

                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.setAttribute('role', 'alert');
                toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="${icons[type] || icons.info}"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${this.escapeHtml(title)}</div>
                        <div class="toast-message">${this.escapeHtml(message)}</div>
                    </div>
                `;

                container.appendChild(toast);

                setTimeout(() => {
                    toast.style.animation = 'slideInToast 0.3s ease-in reverse forwards';
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }

            // ========== أدوات مساعدة ==========
            escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // ========== التنظيف ==========
            destroy() {
                if (this.updateTimer) clearInterval(this.updateTimer);
                if (this.tickTimer) clearInterval(this.tickTimer);
                if (this.websocket) this.websocket.close();
                if (this.audioContext) this.audioContext.close();
            }
        }

        // ============================================
        // بدء التطبيق
        // ============================================
        let kds;

        document.addEventListener('DOMContentLoaded', () => {
            kds = new KitchenDisplaySystem(KDS_CONFIG);
            kds.init();

            window.addEventListener('beforeunload', () => {
                kds.destroy();
            });

            // إعادة تشغيل الصوت عند أول تفاعل
            const resumeAudio = () => {
                if (kds.audioContext?.state === 'suspended') {
                    kds.audioContext.resume();
                }
                document.removeEventListener('click', resumeAudio);
                document.removeEventListener('keydown', resumeAudio);
            };
            document.addEventListener('click', resumeAudio);
            document.addEventListener('keydown', resumeAudio);
        });
    </script>
</body>

</html>
