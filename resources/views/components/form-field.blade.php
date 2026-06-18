@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'full' => false,
    'rows' => 3,
])

<div @class(['sm:col-span-2' => $full])>
    <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-slate-700">
        {{ $label }}@if($required)<span class="text-red-500"> *</span>@endif
    </label>

    @if($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            class="input-field @error($name) border-red-300 @enderror">{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}"
            value="{{ $type !== 'password' ? old($name, $value) : '' }}"
            @if($required) required @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            {{ $attributes->merge(['class' => 'input-field' . ($errors->has($name) ? ' border-red-300' : '')]) }}>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
