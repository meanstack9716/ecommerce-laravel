@php
    $isEdit = isset($brand);
    $formAction = $isEdit ? route('products.brand.update', $brand->id) : route('products.brand.add.submit');
    $pageTitle = $isEdit ? 'Edit Brand' : 'Add New Brand';
    $pageDescription = $isEdit ? 'Update the brand details below' : 'Fill in the details below to add a new brand name';
    $submitText = $isEdit ? 'Update Brand' : 'Add New Brand';
@endphp
@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6 mt-5">
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
                        <div class="sm:col-span-4">
                            <x-textfield 
                                name="name" 
                                label="Brand Name" 
                                value="{{ old('name', $brand->name ?? '') }}"
                                id="brand_name"
                                :required="true"
                                placeholder="Enter new name"
                            />
                        </div>

                        <div class="sm:col-span-4">
                            <label for="description" class="font-medium 3xl:text-xl 3xl:font-semibold">Brand Description
                                <span class="text-red-600">*</span>
                            </label>
                            <textarea rows="3"  name="description" id="description"
                                placeholder="Write your description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $brand->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-file-upload 
                                id="img"
                                name="img"
                                label="Upload Brand logo or Image"
                                :required="$isEdit ? false : true"
                                helpText="Image (PNG, JPG, JPEG) up to 5MB"
                                accept="image/png,image/jpeg,image/jpg"
                            />
                            
                            @if($isEdit && $brand->img_path)
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Current Image:</p>
                                    <img src="{{ asset('storage/' . $brand->img_path) }}" 
                                         alt="{{ $brand->name }}" 
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
                        <a href="{{ route('products.brand.list') }}" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
