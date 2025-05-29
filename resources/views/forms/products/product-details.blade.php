@php
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
            'type' => 'number',
            'required' => 'true'
        ],
        [
            'name' => 'discount_per',
            'label' => 'Product Discount percent (%)',
            'placeholder' => 'Enter discount percent',
            'col' => 3,
            'type' => 'number',
            'default' => '0'
        ],
        [
            'name' => 'stock_quantity',
            'label' => 'Product Stocks',
            'placeholder' => 'Enter available stock for product',
            'col' => 3,
            'type' => 'number',
            'default' => '0',
            'required' => 'true'
        ],
    ];

@endphp

<form action="{{ route('products.add.step2.submit') }}" method="POST" class="mb-0">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900">Product Category</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            @foreach ($detailFields as $field)
                <div class="sm:col-span-{{ $field['col'] }}">
                    <x-textfield 
                        name="{{ $field['name'] }}" 
                        label="{{ $field['label'] }}" 
                        type="{{ $field['type'] }}"
                        value="{{ old($field['name'], session('product_data.basic.' . $field['name']), $field['default'] ?? '') }}"
                        id="{{ $field['name'] }}"
                        :required="$field['required'] ?? false"
                        placeholder="{{ $field['placeholder'] }}"
                    />
                </div>
            @endforeach

            <div class="w-full col-span-6">
                <label class="font-medium 3xl:text-xl 3xl:font-semibold">Product Brand
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative w-full">
                    <input 
                        type="text" 
                        id="brand-search" 
                        name="brand_name" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search brand name or type new brand name"
                        value="{{ old('brand_name', session('product_data.basic.brand_name') ?? '') }}"
                        autocomplete="off"
                    >
                    <div id="brand-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
                </div>
                <input 
                    type="hidden" 
                    id="brand-filter" 
                    name="product_brand" 
                    value="{{ old('product_brand', session('product_data.basic.product_brand') ?? '') }}"
                >
                @error('brand_name')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <x-quill-editor 
                    name="product_details"
                    id="product_details"
                    :required="true"
                    value="{{ old('product_details', session('product_data.basic.product_details')) }}"
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

<script>
    function setupBrandAutocomplete() {
        const searchInput = document.getElementById('brand-search');
        const resultsContainer = document.getElementById('brand-search-results');
        const selectedDataId = document.getElementById('brand-filter');
        let debounceTimer;

        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
                
            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                selectedDataId.value = '';
                return;
            }
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search/brands?searchTerm=${encodeURIComponent(query)}`);
                    const data = await response.json();
                
                    if (data?.data?.length > 0) {
                        resultsContainer.innerHTML = data.data.map(item => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                data-brand-id="${item.id}">
                                ${item.name}
                            </div>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No brand found</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                    } catch (error) {
                        resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error loading results</div>';
                        resultsContainer.classList.remove('hidden');
                        console.error('Error fetching brands:', error);
                    }
                }, 300);
            });

            resultsContainer.addEventListener('click', async function(e) {
                const selectedItem = e.target.closest('[data-brand-id]');
                if (selectedItem) {
                    const selectedId = selectedItem.getAttribute('data-brand-id');
                    const selectedName = selectedItem.textContent.trim();
                    
                    searchInput.value = selectedName;
                    selectedDataId.value = selectedId;
                    resultsContainer.classList.add('hidden');

                }
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.classList.add('hidden');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', async () => {
            setupBrandAutocomplete();
        });
</script>
