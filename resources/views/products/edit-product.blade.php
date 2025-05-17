@extends('layouts.main')

@php
    $detailFields = [
        [
            'name' => 'title',
            'label' => 'Product Title',
            'placeholder' => 'Enter your product title',
            'col' => 6,
            'type' => 'text',
            'required' => 'true'
        ],
        [
            'name' => 'description',
            'label' => 'Product Description',
            'placeholder' => 'Enter your product short description',
            'col' => 6,
            'type' => 'text',
            'required' => 'true'
        ],
        [
            'name' => 'price',
            'label' => 'Product Price',
            'placeholder' => 'Enter your product price',
            'col' => 3,
            'type' => 'text',
            'required' => 'true'
        ],
        [
            'name' => 'discount_percent',
            'label' => 'Product Discount percent (%)',
            'placeholder' => 'Enter discount percent',
            'col' => 3,
            'type' => 'text',
            'default' => '0'
        ],
        [
            'name' => 'stock_quantity',
            'label' => 'Product Stocks',
            'placeholder' => 'Enter available stock for product',
            'col' => 3,
            'type' => 'text',
            'default' => '0',
            'required' => 'true'
        ],
        [
            'name' => 'sku',
            'label' => 'Product Sku',
            'placeholder' => 'Enter sku code for product',
            'col' => 3,
            'type' => 'text',
            'required' => 'true',
        ],
    ];

@endphp

@section('content')
<div class="p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6 mt-5">
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Product</h1>
        </div>

        <div class="mt-8 bg-white py-8 px-6 sm:px-10">
            <form action="{{ route('product.edit.submit', $product->id) }}" method="POST" class="mb-0" enctype="multipart/form-data" id="product-submit-form">
                @csrf
                <div class="space-y-6">
                    <h3 class="text-xl font-medium text-gray-900">Product Description</h3>
        
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        @foreach ($detailFields as $field)
                            <div class="sm:col-span-{{ $field['col'] }}">
                                @if($field['type'] === 'text')
                                    <x-textfield 
                                        name="{{ $field['name'] }}" 
                                        label="{{ $field['label'] }}" 
                                        value="{{ old($field['name'], $product->{$field['name']}) }}"                                        
                                        id="{{ $field['name'] }}"
                                        :required="$field['required'] ?? false"
                                        placeholder="{{ $field['placeholder'] }}"
                                    />
                                @endif
                                <p id="{{ $field['name'] . '-error' }}" class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>
                        @endforeach
                        <div class="sm:col-span-6">
                            <x-textfield :disabled="true" label="Product Brand" name="brand" value="{{ $product->brand->name}}"/>
                        </div>

                        <div class="sm:col-span-6">
                            <x-quill-editor 
                                name="details"
                                id="details"
                                :required="true"
                                value="{{ old('details', $product->details) }}"
                                label="Product Details"
                                editorId="product-editor"
                                height="150px"
                            />
                            @error('product_description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-xl font-medium text-gray-900">Product Size and Variants</h3>
                    <div class="flex justify-between items-center mb-6">
                        <div class="">
                            <label class="font-medium 3xl:text-xl 3xl:font-semibold">Size Type
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="flex space-x-6 mt-2">
                                <div class="flex items-center">
                                    <input type="radio" id="size_type_standard" name="size_type" value="standard" 
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 accent-blue-600" 
                                        {{ $product->sizes[0]->size_type === 'standard' ? 'checked' : '' }}>
                                    <label for="size_type_standard" class="ml-2 block font-medium text-sm text-gray-700">Standard Sizes (S, M, L, XL, etc.)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="size_type_numeric" name="size_type" value="numeric" 
                                       class="h-5 w-6 text-blue-600 focus:ring-blue-500 accent-blue-600"
                                       {{ $product->sizes[0]->size_type === 'numeric' ? 'checked' : '' }}>
                                    <label for="size_type_numeric" class="ml-2 block text-sm font-medium text-gray-700">Numeric Sizes (28, 30, 32, etc.)</label>
                                </div>
                            </div>
                            @error('size_type')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div id="size-container">
                    </div>
                    <div>
                        <button type="button" id="add-size-btn" class="mt-4 px-6 py-2 bg-blue-800 hover:bg-blue-900 font-medium text-white rounded-md cursor-pointer">
                            + Add Another Size
                        </button>
                    </div>
                </div>
                <div class="mt-8 flex justify-center">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const product = @json($product);

    const config = {
        standardColors : [
            @foreach(\App\Enums\Color::cases() as $color)
                { 
                    name: '{{ $color->value }}',
                    hex: '{{ \App\Enums\Color::getHexCode($color->value) }}'
                },
            @endforeach
        ],

        hexCodes: @json(\App\Enums\Color::allHexCodes()),

        standardSizes: [
            @foreach(\App\Enums\Size::standardSizes() as $size)
                '{{ $size }}',
            @endforeach
        ],

        numericSizes: [
            @foreach(\App\Enums\Size::numericSizes() as $size)
                '{{ $size }}',
            @endforeach
        ]
    };

    const form = document.getElementById('product-submit-form');
    const sizeContainer = document.getElementById('size-container');
    const addSizeBtn = document.getElementById('add-size-btn');
    const sizeTypeStandard = document.getElementById('size_type_standard');
    const sizeTypeNumeric = document.getElementById('size_type_numeric');

    let sizeCounter = 0;

    product.sizes.forEach((itemSize) => addSizeBlock(null, itemSize))

    // Event Listeners
    addSizeBtn.addEventListener('click', addSizeBlock);
    sizeTypeStandard.addEventListener('change', updateAllSizeOptions);
    sizeTypeNumeric.addEventListener('change', updateAllSizeOptions);
    form.addEventListener('submit', validateForm);

    // Functions
    function addSizeBlock(event, itemSize) {
        const sizeId = `size_${sizeCounter++}`;
        const isNumeric = sizeTypeNumeric.checked;
        const sizeOptions = isNumeric ? config.numericSizes : config.standardSizes;

        const sizeData = itemSize
        // const variants = sizeData ? sizeData.variants.map((color) => color.value) : []
        // let standardVariants = [];
        let customVariants = [];

        if (sizeData) {
            customVariants = sizeData.variants.filter((color) => !config.hexCodes.includes(color.value))
        }
       
        const sizeBlock = document.createElement('div');
        sizeBlock.className = 'size-block mb-6 p-4 border border-gray-200 rounded-lg';
        sizeBlock.dataset.sizeId = sizeId;
        sizeBlock.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-3 items-center mb-5 gap-4">
                <div class="w-full col-span-2">
                    <label class="font-medium 3xl:text-xl 3xl:font-semibold"">Size <span class="text-red-600">*</span></label>
                    <div class="relative mt-2">
                        <select name="sizes[${sizeId}][name]" id="sizes[${sizeId}][name]"
                            class="size-select cursor-pointer mt-2 block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select size</option>
                            ${sizeOptions.map(size => `
                                <option class="disabled:text-neutral-200" value="${size}" ${sizeData && sizeData.value === size ? 'selected' : '' }>${size}</option>
                            `).join('')}
                            <option value="custom" ${sizeData && !sizeOptions.includes(sizeData.value) ? 'selected' : ''}>Custom Size</option>
                        </select>
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                            chevron_right
                        </span>
                    </div>
                    <input 
                        type="text" 
                        name="sizes[${sizeId}][custom_size]" 
                        id="sizes[${sizeId}][custom_size]"
                        value="${sizeData && !sizeOptions.includes(sizeData.value) ? sizeData.value : ''}"
                        placeholder="Enter custom size" 
                        class="custom-size-input mt-3 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 ${sizeData && !sizeOptions.includes(sizeData.value) ? '' : 'hidden'} focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <p class="size-error mt-1 text-sm font-medium text-red-600 hidden"></p>
                </div>
                <div class="flex sm:justify-end">
                    <button type="button" class="w-fit cursor-pointer font-medium remove-size-btn px-5 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">
                        Remove
                    </button>
                </div>
            </div>

            <div class="standard-colors mb-5">
                <label class="font-medium 3xl:text-xl 3xl:font-semibold">Standard Colors</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-4">
                    ${config.standardColors.map(color => {
                        const variant = sizeData ? sizeData.variants.find((item) => item.value === color.hex) : null;
                        console.log(variant)
                        return `
                        
                        <div class="flex items-center justify-between w-full gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="${sizeId}_${color.name}" 
                                    name="sizes[${sizeId}][colors][standard][${color.hex}][enabled]" 
                                    ${variant ? "checked" : "" }
                                    value="1" class="color-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="${sizeId}_${color.name}" class="ml-3 flex items-center font-medium text-gray-700">
                                    <span class="w-6 h-6 mr-2 rounded-full border border-gray-300" style="background-color:${color.hex}"></span>
                                    ${color.name}
                                </label>
                                <input type="hidden" name="sizes[${sizeId}][colors][standard][${color.hex}][name]" value="${color.name}">
                            </div>
                            <input type="number" name="sizes[${sizeId}][colors][standard][${color.hex}][quantity]" 
                                value="${variant?.stock_quantity ?? 0}" class="quantity-input ml-2 block w-2/3 border border-gray-300 rounded-md shadow-sm py-1 px-3 ${variant ? "" : "hidden" } focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                placeholder="Enter quantity" min="0">
                        </div>
                    `}).join('')}
                </div>
                <p class="color-error mt-1 text-sm font-medium text-red-600 hidden"></p>
            </div>

            <div class="custom-colors mb-5">
                <div class="flex items-center text-center gap-4 mb-4">
                    <p class="font-medium 3xl:text-xl 3xl:font-semibold pb-2">Custom Colors</p>
                    <button type="button" class="add-custom-color-btn cursor-pointer">
                        <span class="material-symbols-outlined text-blue-500">add_circle</span>
                    </button>
                </div>
                <div class="custom-color-container mx-2"></div>
            </div>
        `;

        sizeContainer.appendChild(sizeBlock);
        setupSizeBlockEvents(sizeBlock, customVariants);
        updateRemoveButtons();
        updateDisabledSizeOptions();
    }

    function setupSizeBlockEvents(sizeBlock, customVariants) {
        const sizeSelect = sizeBlock.querySelector('.size-select');
        const customSizeInput = sizeBlock.querySelector('.custom-size-input');
        const removeBtn = sizeBlock.querySelector('.remove-size-btn');
        const addCustomColorBtn = sizeBlock.querySelector('.add-custom-color-btn');
        const colorCheckboxes = sizeBlock.querySelectorAll('.color-checkbox');

        sizeSelect.addEventListener('change', function() {
            customSizeInput.classList.toggle('hidden', this.value !== 'custom');
            updateDisabledSizeOptions();
        });

        removeBtn.addEventListener('click', function() {
            sizeBlock.remove();
            updateRemoveButtons();
            updateDisabledSizeOptions();
        });

        customVariants.forEach((customColor) => {
            addCustomColorField(sizeBlock.querySelector('.custom-color-container'), sizeBlock.dataset.sizeId, customColor);
        })

        addCustomColorBtn.addEventListener('click', function() {
            addCustomColorField(sizeBlock.querySelector('.custom-color-container'), sizeBlock.dataset.sizeId);
        });

        colorCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = this.closest('div').nextElementSibling;
                quantityInput.classList.toggle('hidden', !this.checked);
            });
        });
    }

    function addCustomColorField(container, sizeId, customColor) {
        const colorId = `color_${Math.floor(100000 + Math.random() * 900000)}`;
        const colorField = document.createElement('div');
        colorField.className = 'flex items-center mb-2 space-x-4 lg:w-2/3';
        colorField.innerHTML = `
            <div class="border cursor-pointer border-gray-300 rounded-md shadow-sm py-1 px-3 flex items-center text-center w-1/2">
                <div class="color-picker-wrapper flex items-center text-center">
                <input type="color" name="sizes[${sizeId}][colors][custom][${colorId}][hex]" 
                    value="${customColor?.value || '#000000'}" class="h-8 w-8 border border-gray-300 rounded-lg">
                <span class="hex-value text-sm ml-1">${customColor?.value || '#000000'}</span>
                </div>
                <input type="text" name="sizes[${sizeId}][colors][custom][${colorId}][name]" 
                    value="${customColor?.name || ''}"
                    placeholder="Color name" class="ml-2 block w-1/2 border-none focus:outline-none">
            </div>
            <input type="number" name="sizes[${sizeId}][colors][custom][${colorId}][quantity]"
               value="${customColor?.stock_quantity || '#000000'}"
                min="0" class="block w-1/2 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter Quantity">
            <button type="button" class="remove-color-btn ml-2">
                <span class="material-symbols-outlined text-red-600 bg-red-100 rounded-full">cancel</span>
            </button>
        `;
        container.appendChild(colorField);

        const colorInputDiv = colorField.querySelector('.color-picker-wrapper')
        const colorInput = colorField.querySelector('input[type="color"]');
        const hexValue = colorField.querySelector('.hex-value');
        const removeBtn = colorField.querySelector('.remove-color-btn');

        colorInputDiv.addEventListener('click', () => {
            if (colorInput) {
                colorInput.click();
            }
        });

        colorInput.addEventListener('input', () => hexValue.textContent = colorInput.value);
        removeBtn.addEventListener('click', () => colorField.remove());
    }

    function updateAllSizeOptions() {
        document.querySelectorAll('.size-block').forEach(block => {
            const sizeSelect = block.querySelector('.size-select');
            const currentValue = sizeSelect.value;
            const isCustom = sizeSelect.value === 'custom';
            const customInput = block.querySelector('.custom-size-input');
            const customValue = isCustom ? customInput.value : null;
            const sizeOptions = sizeTypeNumeric.checked ? config.numericSizes : config.standardSizes;

            sizeSelect.innerHTML = `
                <option value="">Select size</option>
                ${sizeOptions.map(size => `<option value="${size}">${size}</option>`).join('')}
                <option value="custom">Custom Size</option>
            `;

            if (isCustom) {
                sizeSelect.value = 'custom';
                customInput.value = customValue;
                customInput.classList.remove('hidden');
            } else if (sizeOptions.includes(currentValue)) {
                sizeSelect.value = currentValue;
            }
        });
    }

    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-size-btn');
        removeButtons.forEach(btn => {
            btn.style.display = document.querySelectorAll('.size-block').length > 1 ? 'block' : 'none';
        });
    }

    function validateTextField(field, minLength = 1) {
        const value = field.value.trim();
        const errorElement = document.getElementById(`${field.name}-error`);
        
        if (!value || value.length < minLength) {
            if (errorElement) {
                errorElement.textContent = `This field is required${minLength > 1 ? ` and must be at least ${minLength} characters` : ''}`;
                errorElement.classList.remove('hidden');
            }
            field.classList.add('border-red-500');
            return false;
        }
        
        if (errorElement) errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateNumberField(field, minValue = 0) {
        const value = parseFloat(field.value);
        const errorElement = document.getElementById(`${field.name}-error`);
        
        if (isNaN(value) || value < minValue) {
            if (errorElement) {
                errorElement.textContent = `Please enter a valid number${minValue > 0 ? ` greater than or equal to ${minValue}` : ''}`;
                errorElement.classList.remove('hidden');
            }
            field.classList.add('border-red-500');
            return false;
        }
        
        if (errorElement) errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateDiscountField(field) {
        const value = parseFloat(field.value);
        const errorElement = document.getElementById(`${field.name}-error`);
        
        if (isNaN(value)) {
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid number';
                errorElement.classList.remove('hidden');
            }
            field.classList.add('border-red-500');
            return false;
        }
        
        if (value < 0 || value > 100) {
            if (errorElement) {
                errorElement.textContent = 'Discount must be between 0 and 100';
                errorElement.classList.remove('hidden');
            }
            field.classList.add('border-red-500');
            return false;
        }
        
        if (errorElement) errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateForm(e) {
        e.preventDefault();
        clearErrors();
        
        const sizeBlocks = document.querySelectorAll('.size-block');
        const errors = [];
        let isValid = true;

        isValid = validateTextField(document.getElementById('title'), 3) && isValid;
        isValid = validateTextField(document.getElementById('description'), 10) && isValid;
        isValid = validateNumberField(document.getElementById('price'), 0.01) && isValid;
        isValid = validateDiscountField(document.getElementById('discount_percent')) && isValid;
        isValid = validateNumberField(document.getElementById('stock_quantity'), 0) && isValid;
        isValid = validateTextField(document.getElementById('sku'), 3) && isValid;

        // Validate at least one size
        if (sizeBlocks.length === 0) {
            errors.push('Please add at least one size.');
            showError('size-container', 'Please add at least one size.');
        }

        // Validate each size block
        sizeBlocks.forEach(block => {
            const sizeId = block.dataset.sizeId;
            const sizeSelect = block.querySelector('.size-select');
            const customSizeInput = block.querySelector('.custom-size-input');
            const sizeValue = sizeSelect.value === 'custom' ? customSizeInput.value : sizeSelect.value;
            
            // Validate size
            if (!sizeValue) {
                errors.push(`Size is required for variant ${sizeId}`);
                showErrorInBlock(block, 'size-error', 'Please select or enter a size.');
            }

            // Validate colors
            const hasValidColor = checkColorQuantities(block);
            if (!hasValidColor) {
                errors.push(`At least one color with quantity > 0 is required for size ${sizeValue || sizeId}`);
                showErrorInBlock(block, 'color-error', 'Please add at least one color with quantity > 0.');
            }
        });

        if (errors.length > 0) {
        } else {
            if(isValid) {
                form.submit();
            }
        }
    }

    function checkColorQuantities(block) {
        const standardQuantities = Array.from(block.querySelectorAll('.color-checkbox:checked'))
            .map(checkbox => {
                const quantityInput = checkbox.closest('div').nextElementSibling;
                return parseInt(quantityInput.value) || 0;
            });

        const customQuantities = Array.from(block.querySelectorAll('.custom-color-container input[name*="[quantity]"]'))
            .map(input => parseInt(input.value) || 0);

        return [...standardQuantities, ...customQuantities].some(qty => qty > 0);
    }

    function clearErrors() {
        document.querySelectorAll('.size-error, .color-error').forEach(el => {
            el.classList.add('hidden');
        });
    }

    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.createElement('p');
        errorElement.className = 'mt-1 text-sm text-red-600';
        errorElement.textContent = message;
        field.insertAdjacentElement('afterend', errorElement);
    }

    function showErrorInBlock(block, className, message) {
        const errorElement = block.querySelector(`.${className}`);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }

    function updateDisabledSizeOptions() {
        const allSizeSelects = document.querySelectorAll('.size-select');
        const selectedSizes = new Set();

        allSizeSelects.forEach(select => {
            if (select.value && select.value !== 'custom') {
                selectedSizes.add(select.value);
            }
        });

        allSizeSelects.forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(option => {
                if (option.value && option.value !== 'custom') {
                    option.disabled = selectedSizes.has(option.value) && option.value !== currentValue;
                }
            });
        });
    }

});
</script>
@endsection
