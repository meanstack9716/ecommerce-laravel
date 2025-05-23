@props([
    'name', 
    'label', 
    'value' => '', 
    'required' => false, 
    'id' => null,
    'helperText' => '',
    'helperId' => 'helper_text',
    'disabled' => false, 
    'type' => 'text',
    'min' => 0,
    'step' => 1,
    'placeholder' => '',
    'minDate' => null,
    'maxDate' => null,
    'preventPastDate' => false
])

@php
    $id = $id ?? $name;

    $minValue = null;
    $maxValue = null;
    
    if (in_array($type, ['date', 'datetime-local'])) {
        if ($preventPastDate) {
            $minValue = $type === 'date' ? now()->format('Y-m-d') : now()->format('Y-m-d\TH:i');
        } else if ($minDate) {
            $minValue = $minDate instanceof \DateTime 
                ? ($type === 'date' ? $minDate->format('Y-m-d') : $minDate->format('Y-m-d\TH:i'))
                : $minDate;
        }
        
        if ($maxDate) {
            $maxValue = $maxDate instanceof \DateTime 
                ? ($type === 'date' ? $maxDate->format('Y-m-d') : $maxDate->format('Y-m-d\TH:i'))
                : $maxDate;
        }
    }
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
        @if($type === 'number') 
            min="{{ $min }}" 
            step="{{ $step }}"
        @elseif(in_array($type, ['date', 'datetime-local']))
            @if($minValue) min="{{ $minValue }}" @endif
            @if($maxValue) max="{{ $maxValue }}" @endif
        @endif
        id="{{ $id }}"
        @if($disabled) disabled @endif
        placeholder="{{ $placeholder }}" 
        value="{{ old($name, $value) }}"
        class="mt-1 block w-full border disabled:bg-neutral-200 border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
    >
    @if($helperText)
        <p class="mt-1 text-sm text-gray-500" id="{{ $helperId }}">{{ $helperText }}</p>
    @endif
    @error($name)
        <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
    @enderror
</div>