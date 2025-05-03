<form action="{{ route('products.add.step3.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900 border-b pb-2 border-gray-300">Product Variants</h3>

        <div class="flex justify-between items-center mb-6">
            <div class="">
                <label class="font-medium 3xl:text-xl 3xl:font-semibold">Size Type
                    <span class="text-red-600">*</span>
                </label>
                <div class="flex space-x-6 mt-2">
                    <div class="flex items-center">
                        <input type="radio" id="size_type_standard" name="size_type" value="standard" 
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500 accent-blue-600" checked>
                        <label for="size_type_standard" class="ml-2 block font-medium text-sm text-gray-700">Standard Sizes (S, M, L, XL, etc.)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="size_type_numeric" name="size_type" value="numeric" 
                           class="h-5 w-6 text-blue-600 focus:ring-blue-500 ccent-blue-600">
                        <label for="size_type_numeric" class="ml-2 block text-sm font-medium text-gray-700">Numeric Sizes (28, 30, 32, etc.)</label>
                    </div>
                </div>
                @error('size_type')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
            <button type="button" id="add-size-btn" class="mt-4 px-6 py-2 bg-blue-800 hover:bg-blue-900 font-medium text-white rounded-md cursor-pointer">
                + Add Another Size
            </button>
        </div>

        <div id="size-container">
            <!-- Size blocks will be added here dynamically -->
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('products.add.step2') }}'" class="inline-flex justify-center py-2 px-6 border border-[#334a8b] shadow-sm font-medium rounded-md text-[#334a8b] bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bg-blue-950 cursor-pointer">
            Back
        </button>
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Next
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sizeContainer = document.getElementById('size-container');
    const addSizeBtn = document.getElementById('add-size-btn');
    const sizeTypeStandard = document.getElementById('size_type_standard');
    const sizeTypeNumeric = document.getElementById('size_type_numeric');
    let sizeCounter = 0;

    const standardColors = [
        @foreach(\App\Enums\Color::cases() as $color)
        { 
            name: '{{ $color->value }}',
            hex: '{{ \App\Enums\Color::getHexCode($color->value) }}'
        },
        @endforeach
    ];

    const standardSizes = [
        @foreach(\App\Enums\Size::standardSizes() as $size)
            '{{ $size }}',
        @endforeach
    ];

    const numericSizes = [
        @foreach(\App\Enums\Size::numericSizes() as $size)
            '{{ $size }}',
        @endforeach
    ];

    // Add initial size block
    addSizeBlock();

    // Add new size block when button clicked
    addSizeBtn.addEventListener('click', function() {
        addSizeBlock();
        updateRemoveButtons();
    });

    // Update size options when size type changes
    sizeTypeStandard.addEventListener('change', updateSizeOptions);
    sizeTypeNumeric.addEventListener('change', updateSizeOptions);

    function addSizeBlock() {
        const sizeId = `size_${sizeCounter++}`;
        const sizeBlock = document.createElement('div');
        sizeBlock.className = 'size-block mb-6 p-4 border border-gray-200 rounded-lg shadow-md';
        sizeBlock.dataset.sizeId = sizeId;

        const isNumeric = sizeTypeNumeric.checked;
        const sizeOptions = isNumeric ? numericSizes : standardSizes;

        sizeBlock.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-3 items-center mb-5 gap-4">
                <div class="w-full col-span-2">
                    <label class="font-medium 3xl:text-xl 3xl:font-semibold">Size
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <select name="sizes[${sizeId}][name]" id="sizes[${sizeId}][name]"
                            class="size-select cursor-pointer mt-2 block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select size</option>
                            ${sizeOptions.map(size => `
                                <option value="${size}">${size}</option>
                            `).join('')}
                            <option value="custom">Custom Size</option>
                        </select>
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                            chevron_right
                        </span>
                    </div>
                    @error('sizes.*.name')
                        <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                    @enderror
                    <input 
                        type="text" 
                        name="sizes[${sizeId}][custom_size]" 
                        id="sizes[${sizeId}][custom_size]"
                        placeholder="Enter custom size" 
                        class="custom-size-input mt-3 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 hidden focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    @error('sizes.*.custom_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex sm:justify-end">
                    <button type="button" class="w-fit cursor-pointer font-medium remove-size-btn px-5 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">
                        Remove
                    </button>
                </div>
            </div>

            <div class="standard-colors mb-5">
                <label class="font-medium 3xl:text-xl 3xl:font-semibold">Standard Colors (with quantity)</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-4">
                    ${standardColors.map(color => `
                        <div class="flex items-center justify-between w-full gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="${sizeId}_${color.name}" 
                                    name="sizes[${sizeId}][colors][standard][${color.hex}][enabled]" 
                                    value="1"
                                    class="color-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="${sizeId}_${color.name}" class="ml-3 flex items-center font-medium text-gray-700">
                                    <span class="inline-block w-6 h-6 mr-2 rounded-full border border-gray-300" style="background-color:${color.hex}"></span>
                                    ${color.name}
                                </label>
                            </div>
                            <input 
                                type="number" 
                                name="sizes[${sizeId}][colors][standard][${color.hex}][quantity]" 
                                value="{{ old('sizes[${sizeId}][colors][standard][${color.hex}][quantity]', 0) }}" 
                                class="quantity-input ml-2 block w-2/3 border border-gray-300 rounded-md shadow-sm py-1 px-3 hidden focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                placeholder="Enter quantity">
                            </div>
                    `).join('')}
                </div>
            </div>

            <div class="custom-colors mb-5">
                <div class="flex items-center text-center gap-4 mb-4">
                    <p class="font-medium 3xl:text-xl 3xl:font-semibold pb-2">Custom Colors</p>
                    <button type="button" class="add-custom-color-btn cursor-pointer">
                        <span class="material-symbols-outlined text-blue-500">
                            add_circle
                        </span>
                    </button>
                </div>
                <div class="custom-color-container mx-2">
                    <!-- Custom colors will be added here -->
                </div>
            </div>
        `;

        sizeContainer.appendChild(sizeBlock);

        // Add event listeners for this size block
        const sizeSelect = sizeBlock.querySelector('.size-select');
        const customSizeInput = sizeBlock.querySelector('.custom-size-input');
        const removeBtn = sizeBlock.querySelector('.remove-size-btn');
        const addCustomColorBtn = sizeBlock.querySelector('.add-custom-color-btn');
        const colorCheckboxes = sizeBlock.querySelectorAll('.color-checkbox');

        sizeSelect.addEventListener('change', function() {
            customSizeInput.classList.toggle('hidden', this.value !== 'custom');
        });

        removeBtn.addEventListener('click', function() {
            sizeBlock.remove();
            updateRemoveButtons();
        });

        addCustomColorBtn.addEventListener('click', function() {
            addCustomColorField(sizeBlock.querySelector('.custom-color-container'), sizeId);
        });

        // Show/hide quantity input when color is checked
        colorCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = this.closest('div').nextElementSibling;
                quantityInput.classList.toggle('hidden', !this.checked);
            });
        });
    }

    function addCustomColorField(container, sizeId) {
        const colorId = `color_${Date.now()}`;
        const colorField = document.createElement('div');
        colorField.className = 'flex items-center mb-2 space-x-4 lg:w-2/3';
        colorField.innerHTML = `
            <div class="color-picker-wrapper border cursor-pointer border-gray-300 rounded-md shadow-sm py-1 px-3 flex items-center text-center w-1/2">
                <input type="color" name="sizes[${sizeId}][colors][custom][${colorId}][hex]" 
                    value="#000000" class="h-8 w-8 border border-gray-300 rounded-lg">
                <span class="hex-value text-sm font-mono">#000000</span>
            </div>
            <input type="number" name="sizes[${sizeId}][colors][custom][${colorId}][quantity]" 
                min="0" 
                class="block w-1/2 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                placeholder="Enter quantity">
            <button type="button" class="remove-color-btn ml-2 cursor-pointer">
                <span class="material-symbols-outlined text-red-600 bg-red-100 rounded-full">
                    cancel
                </span>
            </button>
        `;
        container.appendChild(colorField);

        // Update hex value display when color changes
        const colorInputDiv = colorField.querySelector('.color-picker-wrapper')
        const colorInput = colorField.querySelector('input[type="color"]');
        const hexValue = colorField.querySelector('.hex-value');

        colorInputDiv.addEventListener('click', () => {
            if (colorInput) {
                colorInput.click();
            }
        });

        colorInput.addEventListener('input', function() {
            hexValue.textContent = this.value;
        });

        colorField.querySelector('.remove-color-btn').addEventListener('click', function() {
            colorField.remove();
        });
    }

    function updateSizeOptions() {
        const sizeBlocks = document.querySelectorAll('.size-block');
        const isNumeric = sizeTypeNumeric.checked;
        const sizeOptions = isNumeric ? numericSizes : standardSizes;

        sizeBlocks.forEach(block => {
            const sizeSelect = block.querySelector('.size-select');
            const currentValue = sizeSelect.value;
            
            // Save current value if it's a custom size
            const isCustom = sizeSelect.querySelector('option[value="custom"]').selected;
            const customInput = block.querySelector('.custom-size-input');
            const customValue = isCustom ? customInput.value : null;

            // Update options
            sizeSelect.innerHTML = `
                <option value="">Select Size</option>
                ${sizeOptions.map(size => `
                    <option value="${size}">${size}</option>
                `).join('')}
                <option value="custom">Custom Size</option>
            `;

            // Restore previous selection if possible
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
        const sizeBlocks = document.querySelectorAll('.size-block');
        const removeButtons = document.querySelectorAll('.remove-size-btn');
        
        if (sizeBlocks.length === 1) {
            removeButtons.forEach(button => {
                button.style.display = 'none';
            });
        } else {
            removeButtons.forEach(button => {
                button.style.display = 'block';
            });
        }
    }

    // Initialize remove buttons visibility
    updateRemoveButtons();
});
</script>