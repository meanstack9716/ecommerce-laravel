@php
    $currentStep = old('step', 1);
    $detailFields = [
        [
            'name' => 'product_title',
            'label' => 'Product Title',
            'placeholder' => 'Enter your product title',
            'col' => 6,
            'type' => 'text',
            'required' => 'true'
        ],
        [
            'name' => 'product_description',
            'label' => 'Product Description',
            'placeholder' => 'Enter your product short description',
            'col' => 6,
            'type' => 'text',
            'required' => 'true'
        ],
        [
            'name' => 'product_price',
            'label' => 'Product Price',
            'placeholder' => 'Enter your product price',
            'col' => 3,
            'type' => 'text',
            'required' => 'true'
        ],
        [
            'name' => 'discount_per',
            'label' => 'Product Discount percent (%)',
            'placeholder' => 'Enter discount percent',
            'col' => 3,
            'type' => 'text',
            'default' => '0'
        ],
        [
            'name' => 'product_brand',
            'label' => 'Product Brand',
            'placeholder' => 'Enter brand of product',
            'col' => 3,
            'type' => 'text',
            'required' => 'true'
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
            'name' => 'product_sku',
            'label' => 'Product Sku',
            'placeholder' => 'Enter sku code for product',
            'col' => 3,
            'type' => 'text',
            'required' => 'true'
        ],
    ];

@endphp

<form action="{{ route('products.add.step2.submit') }}" method="POST" class="mb-0">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900 border-b pb-2 border-gray-300">Product Category</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            @foreach ($detailFields as $field)
                            <div class="sm:col-span-{{ $field['col'] }}">
                                @if($field['type'] === 'text')
                                    <x-textfield 
                                        name="{{ $field['name'] }}" 
                                        label="{{ $field['label'] }}" 
                                        value="{{ old($field['name'], $field['default'] ?? '') }}"
                                        id="{{ $field['name'] }}"
                                        :required="$field['required'] ?? false"
                                        placeholder="{{ $field['placeholder'] }}"
                                    />
                                @endif
                            </div>
                        @endforeach

                        <div class="sm:col-span-6">
                            <x-quill-editor 
                                name="product_details"
                                id="product_details"
                                :required="true"
                                value="{{ old('product_details') }}"
                                label="Product Details"
                                editorId="product-editor"
                                height="150px"
                            />
                            @error('product_description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('products.add.step1') }}'" class="inline-flex justify-center py-2 px-6 border border-[#334a8b] shadow-sm font-medium rounded-md text-[#334a8b] bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bg-blue-950 cursor-pointer">
            Back
        </button>
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Next
        </button>
    </div>
</form>
