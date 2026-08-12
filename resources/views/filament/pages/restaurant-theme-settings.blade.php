@php
    $restaurant = auth()->user()?->restaurant()->with('theme')->first();
    $theme = $restaurant?->theme;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">إعدادات الثيم</h1>
            <p class="text-sm text-slate-600">تعديل القيم المسموح بها من ملف theme.json فقط، مع حفظها في قاعدة البيانات.</p>
        </div>

        @if($theme)
            <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-sm font-bold text-orange-700">
                {{ $theme->name }}
            </span>
        @endif
    </div>

    @if(! $restaurant || ! $theme)
        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
            <p class="text-lg font-bold text-slate-700">لم يتم العثور على مطعم أو ثيم مفعّل.</p>
            <p class="mt-2 text-sm text-slate-500">قم بتعيين الثيم للمطعم أولاً ثم عد إلى هذه الصفحة.</p>
        </div>
    @else
        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">خيارات المتغيرات</h2>
                        <p class="text-sm text-slate-500">يتم جلبها تلقائياً من theme.json</p>
                    </div>
                    <span id="theme-status" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">
                        جاري التحميل...
                    </span>
                </div>

                <form id="restaurant-theme-form" class="space-y-4">
                    <div id="theme-fields" class="space-y-4"></div>

                    <div class="flex justify-end pt-2">
                        <button id="save-theme-button" type="submit" class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 disabled:cursor-not-allowed disabled:bg-orange-300">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-lg font-black text-slate-900">معاينة سريعة</h2>

                <div id="theme-preview" class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-100 shadow-inner" style="--preview-primary: #FF6B35; --preview-secondary: #FFFFFF; --preview-background: #1A1A1A;">
                    <div id="theme-preview-hero" class="p-5" style="background: linear-gradient(135deg, var(--preview-primary), var(--preview-background));">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20 text-xl text-white shadow-sm">🍔</div>
                                <div>
                                    <p id="theme-preview-title" class="text-lg font-black text-white">أشهى البرجر</p>
                                    <p class="text-xs text-white/80">جودة طعام لا تضاهى</p>
                                </div>
                            </div>
                            <button id="theme-preview-button" class="rounded-full px-4 py-2 text-xs font-bold text-white shadow-md" style="background: var(--preview-primary);">
                                اطلب الآن
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3 bg-white p-4">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 p-3">
                            <span class="text-sm font-bold text-slate-700">الألوان</span>
                            <span class="flex items-center gap-2">
                                <span class="h-5 w-5 rounded-full border border-slate-200" style="background: var(--preview-primary);"></span>
                                <span class="h-5 w-5 rounded-full border border-slate-200" style="background: var(--preview-background);"></span>
                            </span>
                        </div>
                        <div class="rounded-2xl bg-slate-100 p-3">
                            <p class="text-sm text-slate-600">إظهار البانر الرئيسي</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fieldsContainer = document.getElementById('theme-fields');
        const form = document.getElementById('restaurant-theme-form');
        const statusBadge = document.getElementById('theme-status');
        const saveButton = document.getElementById('save-theme-button');

        if (!form || !fieldsContainer || !statusBadge || !saveButton) {
            return;
        }

        const settingsEndpoint = @json(route('restaurant.theme.settings.get'));
        const updateEndpoint = @json(route('restaurant.theme.settings.update'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        function setStatus(message, type = 'neutral') {
            statusBadge.textContent = message;
            statusBadge.className = 'inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-bold';

            if (type === 'success') {
                statusBadge.classList.add('bg-emerald-100', 'text-emerald-700');
                return;
            }

            if (type === 'error') {
                statusBadge.classList.add('bg-red-100', 'text-red-700');
                return;
            }

            statusBadge.classList.add('bg-slate-100', 'text-slate-600');
        }

        function buildField(key, config, value) {
            const wrapper = document.createElement('div');
            wrapper.className = 'rounded-2xl border border-slate-200 bg-slate-50 p-4';

            const labelRow = document.createElement('div');
            labelRow.className = 'mb-2 flex items-center justify-between gap-2';

            const label = document.createElement('label');
            label.textContent = config.label || key;
            label.className = 'text-sm font-bold text-slate-700';
            label.setAttribute('for', key);

            const helpText = document.createElement('span');
            helpText.textContent = config.description || '';
            helpText.className = 'text-[11px] text-slate-500';

            labelRow.appendChild(label);
            labelRow.appendChild(helpText);
            wrapper.appendChild(labelRow);

            let input;
            const inputType = config.type || 'text';

            if (inputType === 'boolean') {
                input = document.createElement('input');
                input.type = 'checkbox';
                input.checked = Boolean(value ?? config.default ?? false);
                input.className = 'h-5 w-5 rounded border-slate-300 text-orange-500 focus:ring-orange-500';
            } else if (inputType === 'select') {
                input = document.createElement('select');
                input.className = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-200';

                Object.entries(config.options || {}).forEach(([optionValue, optionLabel]) => {
                    const option = document.createElement('option');
                    option.value = optionValue;
                    option.textContent = optionLabel;
                    option.selected = String(optionValue) === String(value ?? config.default ?? '');
                    input.appendChild(option);
                });
            } else if (inputType === 'color') {
                input = document.createElement('input');
                input.type = 'color';
                input.value = value || config.default || '#FF6B35';
                input.className = 'h-12 w-full cursor-pointer rounded-xl border border-slate-300 bg-white p-1';
            } else {
                input = document.createElement('input');
                input.type = inputType === 'number' ? 'number' : 'text';
                input.value = value ?? config.default ?? '';
                input.className = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-200';
            }

            input.name = key;
            input.dataset.settingKey = key;
            input.dataset.settingType = inputType;

            wrapper.appendChild(input);
            return wrapper;
        }

        function updatePreview(values) {
            const preview = document.getElementById('theme-preview');
            if (!preview) {
                return;
            }

            const primary = values.primary_color || '#FF6B35';
            const secondary = values.secondary_color || '#FFFFFF';
            const background = values.background_color || '#1A1A1A';

            preview.style.setProperty('--preview-primary', primary);
            preview.style.setProperty('--preview-secondary', secondary);
            preview.style.setProperty('--preview-background', background);

            const previewHero = document.getElementById('theme-preview-hero');
            if (previewHero) {
                previewHero.style.background = 'linear-gradient(135deg, ' + primary + ', ' + background + ')';
            }

            const previewButton = document.getElementById('theme-preview-button');
            if (previewButton) {
                previewButton.style.background = primary;
            }

            const previewTitle = document.getElementById('theme-preview-title');
            if (previewTitle) {
                previewTitle.style.color = secondary;
            }
        }

        function collectSettings() {
            const settings = {};

            document.querySelectorAll('[data-setting-key]').forEach(function (element) {
                const key = element.dataset.settingKey;
                const type = element.dataset.settingType || 'text';

                let value;
                if (type === 'boolean') {
                    value = element.checked;
                } else if (type === 'number') {
                    value = Number(element.value);
                } else {
                    value = element.value;
                }

                settings[key] = value;
            });

            return settings;
        }

        async function loadSettings() {
            setStatus('جارٍ التحميل...');
            saveButton.disabled = true;

            const response = await fetch(settingsEndpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.error || 'تعذّر تحميل الإعدادات');
            }

            const allowedVariables = data.allowed_variables || {};
            const settings = data.settings || {};

            fieldsContainer.innerHTML = '';

            Object.entries(allowedVariables).forEach(([key, config]) => {
                fieldsContainer.appendChild(buildField(key, config, settings[key] ?? config.default ?? ''));
            });

            updatePreview(settings);
            setStatus('تم التحميل', 'success');
            saveButton.disabled = false;
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            saveButton.disabled = true;
            setStatus('جارٍ الحفظ...');

            try {
                const response = await fetch(updateEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        settings: collectSettings()
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.error || data.message || 'حدث خطأ أثناء الحفظ');
                }

                updatePreview(data.settings || {});
                setStatus('تم الحفظ بنجاح', 'success');
            } catch (error) {
                setStatus('فشل الحفظ', 'error');
                console.error(error);
            } finally {
                saveButton.disabled = false;
            }
        });

        loadSettings().catch(function (error) {
            setStatus('تعذّر تحميل الإعدادات', 'error');
            console.error(error);
        });
    });
</script>
