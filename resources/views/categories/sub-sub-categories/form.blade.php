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
            <form id="categoryForm" action="{{ $formAction }}" method="POST" class="mb-0" enctype="multipart/form-data">
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
                            <div class="relative w-full">
                                <input 
                                    type="text" 
                                    id="category-search" 
                                    name="category_term" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search category type"
                                    value="{{ old('category_term', $subSubCategory->category->name ?? '') }}"
                                    autocomplete="off"
                                >
                                <div id="category-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
                                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 pointer-events-none">
                                    search
                                </span>
                            </div>
                            <input 
                                type="hidden" 
                                id="category-filter" 
                                name="category" 
                                value="{{ old('category', $subSubCategory->category_id ?? '') }}"
                            >
                            @error('category')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-3">
                            <label for="sub_category" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub Category
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="relative w-full">
                                <input 
                                    type="text" 
                                    id="sub-category-search" 
                                    name="sub_category_term" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search sub category type"
                                    value="{{ old('sub_category_term', $subSubCategory->subCategory->name ?? '') }}"
                                    autocomplete="off"
                                    {{ !old('category') && (!$isEdit || !$subSubCategory->category_id) ? 'disabled' : '' }}
                                >
                                <div id="sub-category-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
                                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 pointer-events-none">
                                    search
                                </span>
                            </div>
                            <input 
                                type="hidden" 
                                id="sub-category-filter" 
                                name="sub_category" 
                                value="{{ request('sub_category', $subSubCategory->sub_category_id ?? '') }}"
                            >
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
                    <button id="submitButton" type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
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

    document.getElementById('categoryForm').addEventListener('submit', function() {
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...'; // Optional: Change button text
        submitButton.classList.add('opacity-50', 'cursor-not-allowed'); // Optional: Visual feedback
    });

    function setupCategoryAutocomplete() {
        const searchInput = document.getElementById('category-search');
        const resultsContainer = document.getElementById('category-search-results');
        const selectedDataId = document.getElementById('category-filter');
        const subCategorySearch = document.getElementById('sub-category-search');
        const subCategoryFilter = document.getElementById('sub-category-filter');
        let debounceTimer;

        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                selectedDataId.value = '';
                subCategorySearch.value = '';
                subCategorySearch.disabled = true;
                subCategoryFilter.value = '';
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search/categories?searchTerm=${encodeURIComponent(query)}`);
                    const data = await response.json();                
                    if (data?.data?.length > 0) {
                        resultsContainer.innerHTML = data.data.map(item => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                data-category-id="${item.id}">
                                ${item.name}
                            </div>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No seller found</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } catch (error) {
                    resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error loading results</div>';
                    resultsContainer.classList.remove('hidden');
                    console.error('Error fetching sellers:', error);
                }
            }, 300);
        });

        resultsContainer.addEventListener('click', async function(e) {
            const selectedItem = e.target.closest('[data-category-id]');

            if (selectedItem) {
                const selectedId = selectedItem.getAttribute('data-category-id');
                const selectedName = selectedItem.textContent.trim();
                
                searchInput.value = selectedName;
                selectedDataId.value = selectedId;
                resultsContainer.classList.add('hidden');
                subCategorySearch.disabled = false;
                subCategorySearch.value = '';
                subCategoryFilter.value = '';
            }

        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    }

    function setupSubCategoryAutocomplete() {
        const searchInput = document.getElementById('sub-category-search');
        const resultsContainer = document.getElementById('sub-category-search-results');
        const selectedDataId = document.getElementById('sub-category-filter');
        const categoryFilter = document.getElementById('category-filter');
        let debounceTimer;

        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
            const categoryId = categoryFilter.value;
                
            if (!categoryId) {
                resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Please select a category first</div>';
                resultsContainer.classList.remove('hidden');
                return;
            }

            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                selectedDataId.value = '';
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                try {
                    let url = `/api/search/sub-categories?searchTerm=${encodeURIComponent(query)}`
                    url += categoryId ?  `&categoryId=${categoryId}` : '';
                    const response = await fetch(url);
                    const data = await response.json();
                    if (data?.data?.length > 0) {
                        resultsContainer.innerHTML = data.data.map(item => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                data-sub-category-id="${item.id}">
                                ${item.name}
                            </div>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No sub category found</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } catch (error) {
                    resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error loading results</div>';
                    resultsContainer.classList.remove('hidden');
                    console.error('Error fetching sub categories:', error);
                }
            }, 300);
        });

        resultsContainer.addEventListener('click', async function(e) {
            const selectedItem = e.target.closest('[data-sub-category-id]');
            if (selectedItem) {
                const selectedId = selectedItem.getAttribute('data-sub-category-id');
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
        setupCategoryAutocomplete();
        setupSubCategoryAutocomplete();

        const categoryFilter = document.getElementById('category-filter');
        const subCategorySearch = document.getElementById('sub-category-search');
        if (categoryFilter.value) {
            subCategorySearch.disabled = false;
        }
    });
</script>
@endsection
