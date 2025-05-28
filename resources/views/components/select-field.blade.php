@props([
    'name', 
    'label', 
    'value' => '', 
    'required' => false, 
    'id' => null,
    'disabled' => false, 
    'placeholder' => '',
    'options' => []
])

<div class="w-full">
    @if($label ?? null)
        <label for="{{ $id }}" class="font-medium 3xl:text-xl 3xl:font-semibold"> {{ $label }}
            @if($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select name="{{ $name }}" id="{{ $id }}"
            class="mt-1 cursor-pointer block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $option)
                <option value="{{ $option['value'] }}" {{  $value == $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
            @endforeach
        </select>
        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
            chevron_right
        </span>
    </div>
    @error($name)
        <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
    @enderror
</div>