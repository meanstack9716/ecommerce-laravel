<form action="{{ route('products.add.step1.submit') }}" method="POST" class="mb-0">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900">Product Category</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-6">
                <label for="category" class="font-medium 3xl:text-xl 3xl:font-semibold">Category
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <select name="category" id="category"
                        class="mt-1 block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category') == $cat->id || session('product_data.category.category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                        chevron_right
                    </span>
                </div>
                @error('category')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="sub_category" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub Category
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <select name="sub_category" id="sub_category" 
                        {{ old('category') || session('product_data.category.category_id') ? '' : 'disabled' }}
                        class="mt-1 block appearance-none w-full border disabled:text-gray-400 disabled:border-gray-100 border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select sub category</option>
                        @if(old('category') || session('product_data.category.category_id'))
                            @foreach($subCategories as $subcategory)
                                <option 
                                    value="{{ $subcategory->id }}" 
                                    {{ old('sub_category') == $subcategory->id || session('product_data.category.sub_category_id') == $subcategory->id ? 'selected' : '' }}
                                >{{ $subcategory->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                        chevron_right
                    </span>
                </div>
                @error('sub_category')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="sub_sub_category" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub Sub Category
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <select name="sub_sub_category" id="sub_sub_category" 
                        {{ old('sub_category') || session('product_data.category.sub_category_id') ? '' : 'disabled' }}
                        class="mt-1 block appearance-none w-full border disabled:text-gray-400 disabled:border-gray-100 border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select sub sub category type</option>
                        @if(old('sub_category') || session('product_data.category.sub_category_id'))
                            @foreach($subSubCategories as $type)
                                <option 
                                    value="{{ $type->id }}" 
                                    {{ old('sub_sub_category') == $type->id || session('product_data.category.sub_sub_category_id') == $type->id ? 'selected' : '' }}
                                >{{ $type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                        chevron_right
                    </span>
                </div>
                @error('sub_sub_category')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Next
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nextButton = document.getElementById('next-button');

        const category = document.getElementById('category');
        const subcategorySelect = document.getElementById('sub_category');
        const subSubCategorySelect = document.getElementById('sub_sub_category');
        
        subSubCategorySelect.addEventListener('change', function() {
            nextButton.disabled = !this.value;
        });
                
        category.addEventListener('change', function() {
            const categoryId = this.value;
            
            if (categoryId) {
                subcategorySelect.disabled = false;
                subcategorySelect.classList.remove('bg-gray-100');
                
                fetch(`/category/get-subcategories?category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        subcategorySelect.innerHTML = '<option value="">Select sub category</option>';
                        data.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });
                    });
            } else {
                subcategorySelect.disabled = true;
                subcategorySelect.classList.add('bg-gray-100');
                subcategorySelect.innerHTML = '<option value="">Select sub category</option>';

                subSubCategorySelect.disabled = true;
                subSubCategorySelect.classList.add('bg-gray-100');
                subSubCategorySelect.innerHTML = '<option value="">Select product type</option>';
                nextButton.disabled = true;
            }
        });

        subcategorySelect.addEventListener('change', function() {
            const subCategoryId = this.value;
            
            if (subCategoryId) {
                subSubCategorySelect.disabled = false;
                subSubCategorySelect.classList.remove('bg-gray-100');
                
                fetch(`/category/get-subSubCategory?sub_category_id=${subCategoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        subSubCategorySelect.innerHTML = '<option value="">Select sub sub category type</option>';
                        data.forEach(type => {
                            const option = document.createElement('option');
                            option.value = type.id;
                            option.textContent = type.name;
                            subSubCategorySelect.appendChild(option);
                        });
                    });
            } else {
                subSubCategorySelect.disabled = true;
                subSubCategorySelect.classList.add('bg-gray-100');
                subSubCategorySelect.innerHTML = '<option value="">Select sub sub category type</option>';
                nextButton.disabled = true;
            }
        });
    });
</script>