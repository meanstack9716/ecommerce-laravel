@props([
    'name', 
    'label', 
    'value' => '', 
    'required' => false, 
    'id' => null, 
    'disabled' => false, 
    'type' => 'text',
    'placeholder' => ''
])

@php
    $id = $id ?? $name;
@endphp

<div class="w-full">
    @if($label ?? null)
        <label for="{{ $id }}" class="font-medium 3xl:text-xl 3xl:font-semibold"> {{ $label }}
            @if($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif

    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id }}"
        @if($disabled) disabled @endif
        placeholder="{{ $placeholder }}" 
        value="{{ old($name, $value) }}"
        class="mt-1 block w-full border disabled:bg-neutral-200 border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
    >
    @error($name)
        <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
    @enderror
</div>