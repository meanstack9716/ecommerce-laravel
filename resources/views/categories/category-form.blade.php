@php
    $isEdit = isset($category);
    $formAction = $isEdit ? route('category.update', $category->id) : route('category.add.submit');
    $pageTitle = $isEdit ? 'Edit Category' : 'Add New Category';
    $pageDescription = $isEdit ? 'Update the category details below' : 'Fill in the details below to create a new category';
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
            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Category Name -->
                        <div class="sm:col-span-4">
                            <label for="category_name" class="font-medium 3xl:text-xl 3xl:font-semibold">
                                Category Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="category_name" id="category_name"
                                value="{{ old('category_name', $category->name ?? '') }}"
                                placeholder="Enter category name"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('category_name')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category Description -->
                        <div class="sm:col-span-4">
                            <label for="category_description" class="font-medium 3xl:text-xl 3xl:font-semibold">
                                Category Description <span class="text-red-600">*</span>
                            </label>
                            <textarea rows="3" name="category_description" id="category_description"
                                placeholder="Write your category description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('category_description', $category->description ?? '') }}</textarea>
                            @error('category_description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <x-file-upload 
                                id="category_img"
                                name="category_img"
                                label="Category Image"
                                :required="$isEdit ? false : true"
                                helpText="Image (PNG, JPG, JPEG) up to 5MB"
                                accept="image/png,image/jpeg,image/jpg"
                            />
                            
                            @if($isEdit && $category->img_path)
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Current Image:</p>
                                    <img src="{{ asset('storage/' . $category->img_path) }}" 
                                         alt="{{ $category->name }}" 
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
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        {{ $submitText }}
                    </button>
                    
                    @if($isEdit)
                        <a href="{{ route('category.list') }}" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection