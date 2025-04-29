@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6 mt-5">
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Add Product Type</h1>
            <p class="mt-2 text-sm text-gray-600">Fill in the details below to create a new product type.</p>
        </div>

        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form action="{{ route('product-type.add.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="category" class="font-medium 3xl:text-xl 3xl:font-semibold">Category
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select name="category" id="category"
                                    class="mt-1 block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category') == $cat->id  ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                        <div class="sm:col-span-3">
                            <label for="sub_category" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub Category
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select name="sub_category" id="sub_category" disabled
                                    class="mt-1 block appearance-none w-full border disabled:text-gray-400 disabled:border-gray-100 border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select sub category</option>
                                    @if(old('category'))
                                        @foreach($subCategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" {{ old('sub_category') == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
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
                            <label for="name" class="font-medium 3xl:text-xl 3xl:font-semibold">Product Type Name
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name') }}"
                                placeholder="Enter type name"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="font-medium 3xl:text-xl 3xl:font-semibold">Product Type Description
                                <span class="text-red-600">*</span>
                            </label>
                            <textarea rows="3"  name="description" id="description"
                                value="{{ old('description') }}"
                                placeholder="Write product type description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="img" class="font-medium 3xl:text-xl 3xl:font-semibold">Image for product type
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="file" name="img" id="img"
                                value="{{ old('img') }}"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-gray-500">Upload a clear photo that describe producr type.</p>

                            @error('img')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        Add new Product Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('category').addEventListener('change', function() {
        const subcategorySelect = document.getElementById('sub_category');
        const categoryId = this.value;
        
        if (categoryId) {
            // Enable the subcategory select
            subcategorySelect.disabled = false;
            subcategorySelect.classList.remove('bg-gray-100');
            
            // Fetch subcategories via AJAX
            fetch(`/category/get-subcategories?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing options
                    subcategorySelect.innerHTML = '<option value="">Select sub category</option>';
                    
                    // Add new options
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
        }
    });
</script>
@endsection
