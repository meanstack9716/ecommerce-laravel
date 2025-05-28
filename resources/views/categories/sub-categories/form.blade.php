@php
    $isEdit = isset($subCategory);
    $formAction = $isEdit ? route('sub-category.update', $subCategory->id) : route('sub-category.add.submit');
    $pageTitle = $isEdit ? 'Edit Sub Category' : 'Add Sub Category';
    $pageDescription = $isEdit ? 'Update the sub category details below' : 'Fill in the details below to create a new sub category';
    $submitText = $isEdit ? 'Update Category' : 'Add New Category';
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
            <form id="categoryForm" action="{{ $formAction }}" method="POST" class="mb-0" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="category_type" class="font-medium 3xl:text-xl 3xl:font-semibold">For which Category
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="relative w-full">
                                <input 
                                    type="text" 
                                    id="category-search" 
                                    name="category_term" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search category type"
                                    value="{{ old('category_term', $subCategory->category->name ?? '') }}"
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
                                name="category_type" 
                                value="{{ old('category_type', $subCategory->category_id ?? '') }}"
                            >
                            @error('category_type')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-3">
                            <label for="category_name" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub Category Name
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="category_name" id="category_name"
                                value="{{ old('category_name', $subCategory->name ?? '') }}"
                                placeholder="Enter new category name"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('category_name')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="category_description" class="font-medium 3xl:text-xl 3xl:font-semibold">Sub Category Description
                                <span class="text-red-600">*</span>
                            </label>
                            <textarea rows="3"  name="category_description" id="category_description"
                                placeholder="Write your category description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('category_description', $subCategory->description ?? '') }}</textarea>
                            @error('category_description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <x-file-upload 
                                id="category_img"
                                name="category_img"
                                label="Image for Category"
                                :required="$isEdit ? false : true"
                                helpText="Image (PNG, JPG, JPEG) up to 5MB"
                                accept="image/png,image/jpeg,image/jpg"
                            />
                            
                            @if($isEdit && $subCategory->img_path)
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Current Image:</p>
                                    <img src="{{ asset('storage/' . $subCategory->img_path) }}" 
                                         alt="{{ $subCategory->name }}" 
                                         class="mt-1 h-32 w-32 object-cover rounded-md">
                                </div>
                            @endif
                            
                            @error('category_img')
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
                        <a href="{{ route('sub-category.list') }}" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                        const response = await fetch(`/api/search/categories?searchTerm=${encodeURIComponent(query)}`);
                        const data = await response.json();
                        console.log(data, '1111111111111')
                
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
        });
</script>
@endsection
