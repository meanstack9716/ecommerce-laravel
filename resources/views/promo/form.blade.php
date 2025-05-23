@php
    $isEdit = isset($promocode);
    $formAction = $isEdit ? route('promo-code.update', $promocode->id) : route('promo-code.add.submit');
    $pageTitle = $isEdit ? 'Edit Promo code' : 'Add New Promo code';
    $pageDescription = $isEdit ? 'Update the promo code details below' : 'Fill in the details below to create a new promo code';
    $submitText = $isEdit ? 'Update Promo Code' : 'Add New Promo Code';
@endphp

@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="text-center mb-8">
            
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-gray-600">{{ $pageDescription }}</p>
        </div>
        
        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="">
                            <x-textfield 
                                name="code" 
                                label="Promo code name" 
                                value="{{ old('code', $promocode->code ?? '') }}"
                                id="code"
                                :required="true"
                                placeholder="Enter promo code ex:SUMMER20"
                            />
                        </div>
                        <div class="">
                            <label for="discount_type" class="font-medium 3xl:text-xl 3xl:font-semibold">
                                Discount Type <span class="text-red-600">*</span>
                            </label>
                            <div class="flex space-x-6 mt-1 h-10">
                                <div class="flex items-center">
                                    <input type="radio" id="percentage" name="discount_type" value="percentage" {{ old('discount_type', $promocode->discount_type ?? 'percentage') === 'percentage' ? 'checked' : '' }}
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 accent-blue-600">
                                    <label for="percentage" class="ml-2 block font-medium text-sm text-gray-700">Percentage</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="fixed" name="discount_type" value="fixed" {{ old('discount_type', $promocode->discount_type ?? '') === 'fixed' ? 'checked' : '' }}
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 accent-blue-600">
                                    <label for="fixed" class="ml-2 block text-sm font-medium text-gray-700">Fixed Amount</label>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <x-textfield 
                                name="discount_value" 
                                label="Discount Value" 
                                type="number"
                                value="{{ old('discount_value', $promocode->discount_value ?? '') }}"
                                id="discount_value"
                                helperId="discountHint"
                                helperText="Percentage discount (e.g. 20 for 20%)"
                                :required="true"
                                placeholder="Enter discount value"
                            />
                        </div>

                        <div id="maxDiscountContainer" class="{{ old('discount_type',  $promocode->discount_type ?? 'percentage') === 'percentage' ? '' : 'hidden' }}">
                            <x-textfield 
                                name="max_discount_amount"
                                type="number"
                                label="Maximum Discount (optional)" 
                                value="{{ old('max_discount_amount', $promocode->max_discount_amount ?? '') }}"
                                id="max_discount_amount"
                                helperText="Maximum discount amount for percentage discounts"
                                placeholder="Enter maximum discount amount"
                            />
                        </div>

                        <div>
                            <x-textfield 
                                name="min_order_amount"
                                label="Minimum Order Amount (optional)" 
                                type="number"
                                value="{{ old('min_order_amount', $promocode->min_order_amount ?? '') }}"
                                id="min_order_amount"
                                helperText="Minimum order total to apply this promo"
                                placeholder="Enter minimum order amount"
                            />
                        </div>

                        <div>
                            <x-textfield 
                                name="start_date"
                                label="Start Date" 
                                type="datetime-local"
                                :required="true"
                                value="{{ old('start_date', $promocode->start_date  ?? now()->format('Y-m-d\TH:i')) }}"
                                id="start_date"
                            />
                        </div>

                        <div>
                            <x-textfield 
                                name="expiry_date"
                                label="Expiry Date" 
                                type="datetime-local"
                                value="{{ old('expiry_date', $promocode->expiry_date ?? null) }}"
                                id="expiry_date"
                                helperText="Leave empty for no expiration"
                            />
                        </div>

                        <div>
                            <x-textfield 
                                name="max_uses"
                                label="Total Usage Limit" 
                                type="number"
                                value="{{ old('max_uses', $promocode->max_uses ?? null) }}"
                                id="max_uses"
                                helperText="Leave empty for unlimited uses"
                                placeholder="Enter number for max uses"
                            />
                        </div>

                        <div>
                            <x-textfield 
                                name="uses_per_user"
                                label="Uses Per Customer" 
                                type="number"
                                min="1"
                                value="{{ old('uses_per_user', $promocode->uses_per_user ?? 1) }}"
                                id="uses_per_user"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <x-quill-editor 
                                name="description"
                                id="description"
                                :required="true"
                                value="{{ old('description', $promocode->description ?? '') }}"
                                label="Promo code description"
                                editorId="promo-editor"
                                height="150px"
                            />
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="applicable_to" class="font-medium 3xl:text-xl 3xl:font-semibold">
                                Product Applicability <span class="text-red-600">*</span>
                            </label>
                            <div class="flex space-x-6 mt-1 h-10">
                                <div class="flex items-center cursor-pointer">
                                    <input type="radio" id="all" name="applicable_to" value="all" {{ old('applicable_to', $promocode->applicable_to ?? 'all') === 'all' ? 'checked' : '' }}
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 accent-blue-600">
                                    <label for="all" class="ml-2 block font-medium text-sm text-gray-700">All Products</label>
                                </div>
                                <div class="flex items-center cursor-pointer">
                                    <input type="radio" id="specific" name="applicable_to" value="specific" {{ old('applicable_to', $promocode->applicable_to ?? '') === 'specific' ? 'checked' : '' }}
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 accent-blue-600">
                                    <label for="specific" class="ml-2 block text-sm font-medium text-gray-700">Specific Products</label>
                                </div>
                            </div>
                            <div id="productSelectionContainer" class="{{ old('applicable_to', $promocode->applicable_to ?? 'all') === 'specific' ? '' : 'hidden' }} mt-3">
                                <div class="product-menu-container sm:w-1/2 relative">
                                    <div class="w-full relative product-menu-toggle" id="product-menu-toggle">
                                        <div class="w-full border px-4 border-gray-300 h-10 flex items-center rounded-md shadow-sm cursor-pointer">
                                            Select Product for this promo code
                                        </div>
                                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                                            chevron_right
                                        </span>
                                    </div>
                                    <div id="product-menu-dropdown" class="hidden opacity-0 scale-95 pointer-events-none absolute px-4 py-2 w-full mt-1 shadow-lg rounded-lg border border-gray-300 bg-white">
                                        <input id="product-search-input" name="search_term" placeholder="Search Product" class="block w-full border disabled:bg-neutral-200 border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"/>
                                        <div id="product-list-container" class="flex flex-col gap-2 mt-4 mb-2 max-h-50 overflow-y-auto">
                                            @forelse($products as $product)
                                                <div class="flex items-center gap-4 text-left">
                                                    <input type="checkbox" id="{{ $product->title }}" 
                                                        name="products[]"
                                                        {{ in_array($product->id, old('products',  $promocode->applicable_products ?? [])) ? 'checked' : '' }}
                                                        value="{{ $product->id }}" class="shrink-0 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                    <label for="product" class="font-medium text-gray-700">
                                                        {{ $product->title}}
                                                    </label>
                                                </div>
                                            @empty
                                                <div class="mx-auto py-5">No product found</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Select products this promo applies to</p>
                                @error('products')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center sm:col-span-2">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ old('is_active', $promocode->is_active ?? false) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block font-medium text-gray-700">Is Active</label>
                        </div>

                        <div class="flex items-center sm:col-span-2">
                            <input type="checkbox" id="only_first_order" name="only_first_order" value="1"
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ old('only_first_order', $promocode->only_first_order ?? false) ? 'checked' : '' }}>
                                <label for="only_first_order" class="ml-2 block font-medium text-gray-700">For first order only</label>
                        </div>

                    </div>
                </div>

                <div class="mt-8 flex justify-center space-x-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        {{ $submitText }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const initialProducts = @json($products);

        const discountTypeRadios = document.querySelectorAll('input[name="discount_type"]');
        const maxDiscountContainer = document.getElementById('maxDiscountContainer');
        const discountHint = document.getElementById('discountHint');
    
        discountTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'percentage') {
                    maxDiscountContainer.classList.remove('hidden');
                    discountHint.textContent = 'Percentage discount (e.g. 20 for 20%)';
                } else {
                    maxDiscountContainer.classList.add('hidden');
                    discountHint.textContent = 'Fixed amount discount (e.g. 20 for 20Rs off)';
                }
            });
        });

        // Toggle product selection based on restriction type
        const productRestrictionRadios = document.querySelectorAll('input[name="applicable_to"]');
        const productSelectionContainer = document.getElementById('productSelectionContainer');
    
        productRestrictionRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'specific') {
                    productSelectionContainer.classList.remove('hidden');
                } else {
                    productSelectionContainer.classList.add('hidden');
                }
            });
        });

        const productMenuToggle = document.getElementById('product-menu-toggle');
        const productMenuDropdown = document.getElementById('product-menu-dropdown');

        productMenuToggle?.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDropdown(productMenuDropdown);
        });

        productMenuDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        const toggleDropdown = (dropdown) => {
            if (dropdown.classList.contains('opacity-0')) {
                dropdown.classList.remove('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
                dropdown.classList.add('opacity-100', 'scale-100');
            } else {
                dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
                dropdown.classList.remove('opacity-100', 'scale-100');
            }
        };

        document.addEventListener('click', function () {
            productMenuDropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
            productMenuDropdown.classList.remove('opacity-100', 'scale-100');
        });

        const productSearchInput = document.getElementById('product-search-input');
        let searchTimeout;

        productSearchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const searchTerm = e.target.value.trim();
        
            if (searchTerm.length >= 2) {
                searchTimeout = setTimeout(() => {
                    searchProducts(searchTerm);
                }, 300);
            } else if (searchTerm.length === 0) {
                updateProductList(initialProducts)
            }
        });


        function searchProducts(searchTerm) {
            fetch(`/api/products/list?searchTerm=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    updateProductList(data.data);
                })
                .catch(error => {
                    console.error('Error searching products:', error);
                });
            }
        });

        function updateProductList(products) {
            const productListContainer = document.getElementById('product-list-container');
            productListContainer.innerHTML = '';
        
            if (products.length === 0) {
                productListContainer.innerHTML = '<div class="mx-auto py-5">No products found</div>';
                return;
            }

            products.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.className = 'flex items-center gap-4 text-left';
            
                const isChecked = document.querySelector(`input[name="products[]"][value="${product.id}"]`)?.checked || false;
            
                productDiv.innerHTML = `
                    <input type="checkbox" id="product-${product.id}" 
                        name="products[]" 
                        value="${product.id}" 
                        class="shrink-0 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        ${isChecked ? 'checked' : ''}>
                    <label for="product-${product.id}" class="ml-3 flex items-center font-medium text-gray-700">
                        ${product.title}
                    </label>
                `;
            
                productListContainer.appendChild(productDiv);
            });
        }
</script>
@endsection