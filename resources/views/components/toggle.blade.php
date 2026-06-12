@props([
    'label' => '',
    'active' => false,
    'name' => '',
    'checked' => false,
    'onColor' => 'bg-orange-500',
    'offColor' => 'bg-gray-200 dark:bg-gray-700'
])

<div class="custom-toggle-container">
    <label class="custom-toggle-switch">
        <!-- Hidden input element -->
        <input 
            type="checkbox" 
            name="{{ $name }}"
            id="{{ $name }}"
            value="1"
            @checked($checked)
            {{ $attributes->merge(['class' => 'custom-toggle-input']) }}
        >
        <!-- Custom Pill Slider Body -->
        <span class="custom-toggle-slider"></span>
    </label>
    
    @if($label)
        <span class="custom-toggle-label">{{ $label }}</span>
    @endif
</div>

<style>
    /* Scoped styling parameters safely independent from global configurations */
    .custom-toggle-container {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        font-family: system-ui, -apple-system, sans-serif;
        user-select: none;
    }

    .custom-toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        cursor: pointer;
    }

    /* Completely hides native browser checkbox layout */
    .custom-toggle-input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }

    /* The Switch Pill Background Track */
    .custom-toggle-slider {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #e5e7eb;
        transition: background-color 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 9999px;
    }

    /* The Circular Moving Knob */
    .custom-toggle-slider::before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Activated Toggle Background (Matches your Orange color hex layout) */
    .custom-toggle-input:checked + .custom-toggle-slider {
        background-color: #ea580c; /* Tailwind Orange-600 */
    }

    /* Knob Sliding Animation Position */
    .custom-toggle-input:checked + .custom-toggle-slider::before {
        transform: translateX(20px);
    }

    .custom-toggle-label {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }
</style>
