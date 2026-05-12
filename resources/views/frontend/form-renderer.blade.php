@php
    $ap           = $form->settings['appearance'] ?? [];
    $columns      = max(1, intval($ap['columns']          ?? 1));
    $size         = $ap['size']                           ?? 'md';
    $borderClr    = $ap['border_color']                   ?? '#d1d5db';
    $bgClr        = $ap['field_bg']                       ?? '#ffffff';
    $textClr      = $ap['text_color']                     ?? '#374151';
    $phClr        = $ap['placeholder_color']              ?? '#9ca3af';
    $labelClr     = $ap['label_color']                    ?? '#374151';
    $focusClr     = $ap['focus_color']                    ?? '#3b82f6';
    $btnBg        = $ap['button_bg']                      ?? '#2563eb';
    $btnText      = $ap['button_text']                    ?? '#ffffff';
    $borderRadius = max(0, intval($ap['border_radius']    ?? 8));
    $btnWidth     = $ap['btn_width']                      ?? 'full';
    $btnAlign     = $ap['btn_align']                      ?? 'center';
    $submitLabel  = $form->settings['submit_label']       ?? 'Submit';
    $successMsg   = $form->settings['success_message']    ?? 'Thank you! Your message has been sent.';
    $formId       = 'lazy-form-' . $form->id;

    $inputSize = match($size) {
        'sm'    => 'px-3 py-1.5 text-xs',
        'lg'    => 'px-5 py-3 text-base',
        default => 'px-4 py-2.5 text-sm',
    };
    $btnPad = match($size) {
        'sm'    => 'py-2 px-5 text-sm',
        'lg'    => 'py-4 px-8 text-base',
        default => 'py-3 px-6 text-sm',
    };

    // Button wrapper: always spans full grid width, then controls inner alignment
    $alignJustify = match($btnAlign) { 'left' => 'flex-start', 'right' => 'flex-end', default => 'center' };
    $btnWrapStyle = 'grid-column:1/-1;' . ($btnWidth === 'auto' ? " display:flex; justify-content:{$alignJustify};" : '');
    $btnInline    = $btnWidth === 'auto' ? 'width:auto; min-width:120px;' : '';
@endphp

<style>
#{{ $formId }}-wrap .lf-input {
    border: 1px solid {{ $borderClr }};
    background-color: {{ $bgClr }};
    color: {{ $textClr }};
    border-radius: {{ $borderRadius }}px;
    width: 100%;
    display: block;
    transition: border-color .15s, box-shadow .15s;
}
#{{ $formId }}-wrap .lf-input::placeholder { color: {{ $phClr }}; }
#{{ $formId }}-wrap .lf-input:focus {
    outline: none;
    border-color: {{ $focusClr }};
    box-shadow: 0 0 0 3px {{ $focusClr }}33;
}
#{{ $formId }}-wrap .lf-input.lf-err-input { border-color: #ef4444 !important; box-shadow: none !important; }
#{{ $formId }}-wrap .lf-label { color: {{ $labelClr }}; }
#{{ $formId }}-wrap .lf-btn {
    background-color: {{ $btnBg }};
    color: {{ $btnText }};
    border: none;
    cursor: pointer;
    border-radius: {{ $borderRadius }}px;
    font-weight: 600;
    transition: opacity .15s;
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}
#{{ $formId }}-wrap .lf-btn:hover:not(:disabled) { opacity: .88; }
#{{ $formId }}-wrap .lf-btn:disabled { opacity: .6; cursor: not-allowed; }
#{{ $formId }}-wrap .lf-field-err {
    color: #ef4444;
    font-size: .72rem;
    margin-top: .3rem;
    font-weight: 500;
    display: block;
}
</style>

<div class="lazy-form-wrap my-6" id="{{ $formId }}-wrap">
    <div id="{{ $formId }}-success" class="hidden bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl text-sm font-medium mb-4">
        {{ $successMsg }}
    </div>

    <form id="{{ $formId }}"
          style="display:grid; grid-template-columns: repeat({{ $columns }}, 1fr); gap: 1rem;"
          novalidate>
        @csrf
        <input type="hidden" name="form_id" value="{{ $form->id }}">

        @foreach($form->fields as $field)
            @php
                $name       = $field['name'] ?? $field['type'];
                $required   = !empty($field['required']);
                $alwaysFull = in_array($field['type'], ['heading', 'paragraph', 'divider', 'hidden']);

                if ($alwaysFull) {
                    $gridAttr = 'style="grid-column:1/-1"';
                } else {
                    $span = $field['col_span'] ?? null;
                    if ($span === null || $span === 'full') {
                        $gridAttr = 'style="grid-column:1/-1"';
                    } else {
                        $spanNum  = max(1, min($columns, intval($span)));
                        $gridAttr = $spanNum >= $columns
                            ? 'style="grid-column:1/-1"'
                            : 'style="grid-column:span ' . $spanNum . '"';
                    }
                }

                $reqData = '';
                if ($required) {
                    $reqData = 'data-lf-req="1"'
                        . ' data-lf-name="' . e($name) . '"'
                        . ' data-lf-type="' . e($field['type']) . '"'
                        . ' data-lf-err="'  . e($field['error_message'] ?? '') . '"';
                }
                $wrapAttrs = trim($gridAttr . ' ' . $reqData);
            @endphp

            @if($field['type'] === 'heading')
                <div style="grid-column:1/-1">
                    <{{ $field['level'] ?? 'h3' }} class="lf-label font-bold text-lg mt-2">{{ $field['content'] ?? '' }}</{{ $field['level'] ?? 'h3' }}>
                </div>

            @elseif($field['type'] === 'paragraph')
                <div style="grid-column:1/-1">
                    <p class="lf-label text-sm">{{ $field['content'] ?? '' }}</p>
                </div>

            @elseif($field['type'] === 'divider')
                <div style="grid-column:1/-1"><hr style="border-color:{{ $borderClr }}" class="my-1"></div>

            @elseif($field['type'] === 'hidden')
                <input type="hidden" name="{{ $name }}" value="{{ $field['value'] ?? '' }}">

            @elseif($field['type'] === 'textarea')
                <div {!! $wrapAttrs !!}>
                    <label class="lf-label block text-sm font-semibold mb-1">
                        {{ $field['label'] ?? '' }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif
                    </label>
                    <textarea name="{{ $name }}" rows="{{ $field['rows'] ?? 4 }}"
                              placeholder="{{ $field['placeholder'] ?? '' }}"
                              class="lf-input {{ $inputSize }} resize-none"></textarea>
                </div>

            @elseif($field['type'] === 'select')
                <div {!! $wrapAttrs !!}>
                    <label class="lf-label block text-sm font-semibold mb-1">
                        {{ $field['label'] ?? '' }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif
                    </label>
                    <select name="{{ $name }}" class="lf-input {{ $inputSize }}">
                        <option value="">-- Select --</option>
                        @foreach(explode("\n", $field['options'] ?? '') as $opt)
                            @php $opt = trim($opt); @endphp
                            @if($opt)<option value="{{ $opt }}">{{ $opt }}</option>@endif
                        @endforeach
                    </select>
                </div>

            @elseif($field['type'] === 'checkbox')
                <div {!! $wrapAttrs !!}>
                    <label class="lf-label block text-sm font-semibold mb-2">
                        {{ $field['label'] ?? '' }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif
                    </label>
                    @foreach(explode("\n", $field['options'] ?? '') as $opt)
                        @php $opt = trim($opt); @endphp
                        @if($opt)
                            <label class="flex items-center gap-2 text-sm mb-1.5 cursor-pointer lf-label">
                                <input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}"
                                       style="accent-color:{{ $focusClr }}" class="rounded">
                                {{ $opt }}
                            </label>
                        @endif
                    @endforeach
                </div>

            @elseif($field['type'] === 'radio')
                <div {!! $wrapAttrs !!}>
                    <label class="lf-label block text-sm font-semibold mb-2">
                        {{ $field['label'] ?? '' }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif
                    </label>
                    @foreach(explode("\n", $field['options'] ?? '') as $opt)
                        @php $opt = trim($opt); @endphp
                        @if($opt)
                            <label class="flex items-center gap-2 text-sm mb-1.5 cursor-pointer lf-label">
                                <input type="radio" name="{{ $name }}" value="{{ $opt }}"
                                       style="accent-color:{{ $focusClr }}">
                                {{ $opt }}
                            </label>
                        @endif
                    @endforeach
                </div>

            @elseif($field['type'] === 'file')
                <div {!! $wrapAttrs !!}>
                    <label class="lf-label block text-sm font-semibold mb-1">
                        {{ $field['label'] ?? '' }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif
                    </label>
                    <input type="file" name="{{ $name }}"
                           class="lf-input {{ $inputSize }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

            @else
                <div {!! $wrapAttrs !!}>
                    <label class="lf-label block text-sm font-semibold mb-1">
                        {{ $field['label'] ?? '' }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif
                    </label>
                    <input type="{{ $field['type'] }}" name="{{ $name }}"
                           placeholder="{{ $field['placeholder'] ?? '' }}"
                           class="lf-input {{ $inputSize }}">
                </div>
            @endif
        @endforeach

        <div style="{{ $btnWrapStyle }}">
            <button type="submit" class="lf-btn {{ $btnPad }}" style="{{ $btnInline }}">
                <span class="btn-text">{{ $submitLabel }}</span>
                <svg class="btn-spinner hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </button>
        </div>

        {{-- AJAX error shown below the button --}}
        <div id="{{ $formId }}-ajax-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium" style="grid-column:1/-1"></div>
    </form>
</div>

<script>
(function () {
    const form      = document.getElementById('{{ $formId }}');
    const success   = document.getElementById('{{ $formId }}-success');
    const ajaxErr   = document.getElementById('{{ $formId }}-ajax-error');
    const origLabel = '{{ addslashes($submitLabel) }}';

    // ── error helpers ────────────────────────────────────────────────
    function clearErr(wrap) {
        wrap.querySelectorAll('.lf-field-err').forEach(e => e.remove());
        const inp = wrap.querySelector('.lf-err-input');
        if (inp) { inp.classList.remove('lf-err-input'); inp.style.borderColor = ''; }
    }

    function showErr(wrap, msg) {
        clearErr(wrap);
        const span = document.createElement('span');
        span.className = 'lf-field-err';
        span.textContent = msg || 'This field is required.';
        wrap.appendChild(span);
        const inp = wrap.querySelector('.lf-input');
        if (inp) inp.classList.add('lf-err-input');
    }

    // clear error on interaction
    form.addEventListener('input',  e => { const w = e.target.closest('[data-lf-req]'); if (w) clearErr(w); });
    form.addEventListener('change', e => { const w = e.target.closest('[data-lf-req]'); if (w) clearErr(w); });

    // ── validation ───────────────────────────────────────────────────
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function validate() {
        let ok = true, first = null;

        // 1. Required fields
        form.querySelectorAll('[data-lf-req]').forEach(wrap => {
            const type = wrap.dataset.lfType;
            const name = wrap.dataset.lfName;
            let empty  = true;

            if (type === 'checkbox') {
                empty = !form.querySelector(`input[name="${name}[]"]:checked`);
            } else if (type === 'radio') {
                empty = !form.querySelector(`input[name="${name}"]:checked`);
            } else if (type === 'file') {
                const inp = form.querySelector(`input[name="${name}"]`);
                empty = !inp || inp.files.length === 0;
            } else {
                const inp = form.querySelector(`[name="${name}"]`);
                empty = !inp || !inp.value.trim();
            }

            if (empty) {
                ok = false;
                showErr(wrap, wrap.dataset.lfErr || 'This field is required.');
                if (!first) first = wrap;
            } else {
                clearErr(wrap);
            }
        });

        // 2. Email format — all email inputs that have a value
        form.querySelectorAll('input[type="email"]').forEach(inp => {
            const val = inp.value.trim();
            if (!val) return; // empty handled by required check above
            if (!emailRe.test(val)) {
                ok = false;
                const wrap = inp.closest('[data-lf-req]') || inp.parentElement;
                if (!wrap.querySelector('.lf-field-err')) {
                    showErr(wrap, wrap.dataset?.lfErr || 'Please enter a valid email address.');
                }
                if (!first) first = wrap;
            }
        });

        if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return ok;
    }

    // ── submit ───────────────────────────────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        ajaxErr.classList.add('hidden');

        if (!validate()) return;

        const btn     = form.querySelector('button[type=submit]');
        const spinner = btn.querySelector('.btn-spinner');
        const btnText = btn.querySelector('.btn-text');

        btn.disabled = true;
        spinner.classList.remove('hidden');
        btnText.textContent = 'Submitting...';

        try {
            const res  = await fetch('{{ route("frontend.form.submit") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData(form)
            });
            const data = await res.json();
            if (data.success) {
                form.reset();
                success.classList.remove('hidden');
                success.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                // Hide success after 8s and show form again
                setTimeout(() => {
                    success.classList.add('hidden');
                }, 8000);
            } else {
                ajaxErr.textContent = data.message || 'Something went wrong. Please try again.';
                ajaxErr.classList.remove('hidden');
            }
        } catch (err) {
            ajaxErr.textContent = 'Network error. Please check your connection and try again.';
            ajaxErr.classList.remove('hidden');
        }

        btn.disabled = false;
        spinner.classList.add('hidden');
        btnText.textContent = origLabel;
    });
})();
</script>
