@extends('themes.burger-theme.pages.kds')
@section('Style')
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
@endsection
