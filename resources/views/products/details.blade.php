@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Product Gallery Section -->
            <div class="w-full">
                <!-- Main Image -->
                <div class="mb-4">
                    <img id="mainProductImage" src="{{ asset('storage/' . $product->gallery[0]->img_path) }}" 
                         alt="{{ $product->title }}" 
                         class="w-full h-96 object-contain rounded shadow border border-gray-200">
                </div>
                
                <!-- Thumbnails -->
                <div class="grid grid-cols-4 gap-2" id="colorThumbnails">
                    @foreach($product->gallery as $image)
                        <img src="{{ asset('storage/' . $image->img_path) }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-24 object-cover rounded shadow cursor-pointer border border-gray-200 hover:border-blue-500"
                             onclick="changeMainImage(this)"
                             data-color="{{ $image->color }}">
                    @endforeach
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="w-full">
                <div class="border-b border-gray-200 pb-2 mb-3">
                    <h1 class="text-3xl font-bold text-black mb-2">{{ $product->title }}</h1>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>

                <div class="flex items-center text-center gap-4">
                    <p class="text-2xl m-0 font-bold text-black">₹{{ $product->final_price }}</p>
                    @if ($product->discount_percent > 0)
                        <div class="flex items-center text-center gap-1.5">
                            <p class="text-lg m-0 text-gray-500">MRP</p>
                            <p class="line-through m-0 text-gray-500">₹{{ $product->price }}</p>
                            <span class="text-red-500 m-0 font-semibold">({{ $product->discount_percent }}% OFF)</span>
                        </div>
                    @endif
                </div>
                <p class="font-semibold text-green-500">Inclusive of all taxes</p>
                <p class="font-semibold text-lg mb-3">Seller: <span class="font-medium text-base text-gray-700">{{ $product->user->first_name }}</span></p>

                <!-- Size Selection -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Select Size</h3>
                    <div class="flex flex-wrap gap-2" id="sizeSelection">
                        @foreach($product['sizes'] as $index => $size)
                            <button type="button" 
                                    class="px-4 py-2 border rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $index === 0 ? 'bg-blue-100 border-blue-500' : 'border-gray-300' }}"
                                    data-size="{{ $size['value'] }}"
                                    onclick="selectSize(this, '{{ $size['value'] }}', {{ json_encode($size['variants']) }})">
                                {{ $size['value'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Color Selection -->
                <div class="mt-6" id="colorSelectionContainer">
                    <h3 class="text-lg font-semibold mb-2">Select Color</h3>
                    <div class="flex flex-wrap gap-2" id="colorSelection">
                        <!-- Colors will be populated dynamically based on selected size -->
                        @foreach($product['sizes'][0]['variants'] as $index => $variant)
                            <button type="button" 
                                    class="w-10 h-10 rounded-full border-2 flex items-center justify-center {{ $index === 0 ? 'border-blue-500' : 'border-gray-300' }}"
                                    style="background-color: {{ $variant['value'] }}"
                                    onclick="selectColor(this, '{{ $variant['name'] }}', '{{ $variant['value'] }}', '{{ $variant['stock_quantity'] }}')"
                                    title="{{ $variant['name'] }}">
                                @if($index === 0)
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Stock Quantity -->
                <div class="mt-4">
                    <p class="font-semibold">Available: <span id="stockQuantity" class="font-medium">{{ $product['sizes'][0]['variants'][0]['stock_quantity'] }}</span></p>
                </div>

                <!-- Add to Cart -->
                <div class="mt-6 flex gap-3">
                    <div class="flex items-center border border-gray-300 rounded-md">
                        <button class="px-3 py-1 text-lg" onclick="updateQuantity(-1)">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product['sizes'][0]['variants'][0]['stock_quantity'] }}" class="w-12 text-center border-0 focus:ring-0">
                        <button class="px-3 py-1 text-lg" onclick="updateQuantity(1)">+</button>
                    </div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                        Add to Cart
                    </button>
                </div>

                <!-- Product Details -->
                <div class="pt-4 border-t border-gray-200 space-y-2">
                    <h2 class="font-bold text-xl">Product Details:</h2>
                    <p class="font-semibold mb-0">Sku: <span class="text-sm font-medium ml-1 text-gray-700">{{ $product->sku }}</span></p>
                    <p class="font-semibold mb-0">Brand: <span class="font-medium text-sm ml-1 text-gray-700">{{ $product->brand->name }}</span></p>
                    <p class="font-semibold mb-0">In Stock: <span class="font-medium text-sm ml-1 text-gray-700">{{ $product->stock_quantity }}</span></p>

                    <div class="mt-4">
                        <h3 class="text-lg font-semibold">Category</h3>
                        <div class="flex items-center text-center gap-2 mt-2">
                            <img src="{{ asset('storage/' . $product->subSubCategory->img_path) }}" alt="Category" class="w-16 h-16 rounded-full">
                            <div class="flex flex-col text-left">
                                <p class="font-medium text-gray-800">
                                    {{ $product->category->name }} → {{ $product->subCategory->name }} → {{ $product->subSubCategory->name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="prose pt-4 border-t border-gray-200">{!! $product->details !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Current selections
    let selectedSize = "{{ $product['sizes'][0]['value'] }}";
    let selectedColor = "{{ $product['sizes'][0]['variants'][0]['name'] }}";
    let selectedColorValue = "{{ $product['sizes'][0]['variants'][0]['value'] }}";
    let maxQuantity = {{ $product['sizes'][0]['variants'][0]['stock_quantity'] }};

    // Initialize the gallery with the first color's images
    document.addEventListener('DOMContentLoaded', function() {
        filterImagesByColor(selectedColor);
    });

    // Change main image when thumbnail is clicked
    function changeMainImage(element) {
        document.getElementById('mainProductImage').src = element.src;
    }

    // Filter images by color
    function filterImagesByColor(color) {
        const thumbnails = document.querySelectorAll('#colorThumbnails img');
        thumbnails.forEach(img => {
            if (img.dataset.color.toLowerCase() === color.toLowerCase()) {
                img.style.display = 'block';
                // Set the first matching image as main image
                if (!document.getElementById('mainProductImage').src.includes(img.src)) {
                    document.getElementById('mainProductImage').src = img.src;
                }
            } else {
                img.style.display = 'none';
            }
        });
    }

    // Size selection handler
    function selectSize(element, size, variants) {
        // Update selected size
        selectedSize = size;
        
        // Update active state
        document.querySelectorAll('#sizeSelection button').forEach(btn => {
            btn.classList.remove('bg-blue-100', 'border-blue-500');
            btn.classList.add('border-gray-300');
        });
        element.classList.add('bg-blue-100', 'border-blue-500');
        element.classList.remove('border-gray-300');
        
        // Update color options
        const colorSelection = document.getElementById('colorSelection');
        colorSelection.innerHTML = '';
        
        variants.forEach((variant, index) => {
            const isFirst = index === 0;
            const colorBtn = document.createElement('button');
            colorBtn.type = 'button';
            colorBtn.className = `w-10 h-10 rounded-full border-2 flex items-center justify-center ${isFirst ? 'border-blue-500' : 'border-gray-300'}`;
            colorBtn.style.backgroundColor = variant.value;
            colorBtn.title = variant.name;
            colorBtn.onclick = function() {
                selectColor(this, variant.name, variant.value, variant.stock_quantity);
            };
            
            if (isFirst) {
                colorBtn.innerHTML = `
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                // Update selected color and stock
                selectedColor = variant.name;
                selectedColorValue = variant.value;
                document.getElementById('stockQuantity').textContent = variant.stock_quantity;
                maxQuantity = parseInt(variant.stock_quantity);
                document.getElementById('quantity').max = maxQuantity;
                
                // Filter images
                filterImagesByColor(variant.name);
            }
            
            colorSelection.appendChild(colorBtn);
        });
    }

    // Color selection handler
    function selectColor(element, colorName, colorValue, stockQuantity) {
        // Update selected color
        selectedColor = colorName;
        selectedColorValue = colorValue;
        
        // Update active state
        document.querySelectorAll('#colorSelection button').forEach(btn => {
            btn.classList.remove('border-blue-500');
            btn.classList.add('border-gray-300');
            btn.innerHTML = '';
        });
        element.classList.add('border-blue-500');
        element.classList.remove('border-gray-300');
        element.innerHTML = `
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
        
        // Update stock quantity
        document.getElementById('stockQuantity').textContent = stockQuantity;
        maxQuantity = parseInt(stockQuantity);
        const quantityInput = document.getElementById('quantity');
        if (parseInt(quantityInput.value) > maxQuantity) {
            quantityInput.value = maxQuantity;
        }
        quantityInput.max = maxQuantity;
        
        // Filter images
        filterImagesByColor(colorName);
    }

    // Quantity update handler
    function updateQuantity(change) {
        const quantityInput = document.getElementById('quantity');
        let newValue = parseInt(quantityInput.value) + change;
        
        if (newValue < 1) newValue = 1;
        if (newValue > maxQuantity) newValue = maxQuantity;
        
        quantityInput.value = newValue;
    }
</script>
@endsection