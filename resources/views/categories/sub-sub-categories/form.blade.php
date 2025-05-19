@php
    $isEdit = isset($subSubCategory);
    $formAction = $isEdit ? route('sub-sub-category.update', $subSubCategory->id) : route('sub-sub-category.add.submit');
    $pageTitle = $isEdit ? 'Edit Sub-Sub Category' : 'Add Sub-Sub Category';
    $pageDescription = $isEdit ? 'Update the sub-sub category details below' : 'Fill in the details below to create a new sub-sub category';
    $submitText = $isEdit ? 'Update Category' : 'Add New Category';
@endphp
@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6 ">
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-gray-600">{{ $pageDescription }}</p>
        </div>

        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form action="{{ $formAction }}" method="POST" class="mb-0" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
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
                                        <option value="{{ $cat->id }}" {{ old('category') == $cat->id || ( $isEdit && $subSubCategory->category_id == $cat->id)  ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                                <select name="sub_category" id="sub_category" 
                                    {{ old('category') || ( $isEdit && $subSubCategory->category_id)  ? '' : 'disabled' }}
                                    class="mt-1 block appearance-none w-full border disabled:text-gray-400 disabled:border-gray-100 border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select sub category</option>
                                    @if(old('category') || ( $isEdit && $subSubCategory->category_id))
                                        @foreach($subCategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" {{ old('sub_category') == $subcategory->id || ( $isEdit && $subSubCategory->sub_category_id == $subcategory->id) ? 'selected' : '' }}>{{ $subcategory->name }}</option>
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
                            <label for="name" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub-Sub Category Name
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $subSubCategory->name ?? '') }}"
                                placeholder="Enter category name"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub-Sub Category Description
                                <span class="text-red-600">*</span>
                            </label>
                            <textarea rows="3"  name="description" id="description"
                                value="{{ old('description') }}"
                                placeholder="Write category description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $subSubCategory->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <x-file-upload 
                                id="img"
                                name="img"
                                label="Image for sub-sub Category"
                                :required="$isEdit ? false : true"
                                helpText="Image (PNG, JPG, JPEG) up to 5MB"
                                accept="image/png,image/jpeg,image/jpg"
                            />
                            
                            @if($isEdit && $subSubCategory->img_path)
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Current Image:</p>
                                    <img src="{{ asset('storage/' . $subSubCategory->img_path) }}" 
                                         alt="{{ $subSubCategory->name }}" 
                                         class="mt-1 h-32 w-32 object-cover rounded-md">
                                </div>
                            @endif
                            
                            @error('img')
                                <p class="text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center space-x-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        {{ $submitText }}
                    </button>
                    
                    @if($isEdit)
                        <a href="{{ route('sub-sub-category.list') }}" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                    @endif
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
