@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6 mt-5">
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Add New Category</h1>
            <p class="mt-2 text-sm text-gray-600">Fill in the details below to create a new category.</p>
        </div>
        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form action="{{ route('category.add.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="category_name" class="font-medium 3xl:text-xl 3xl:font-semibold">Category Name
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="category_name" id="category_name"
                                value="{{ old('category_name') }}"
                                placeholder="Enter new category name"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('category_name')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <label for="category_description" class="font-medium 3xl:text-xl 3xl:font-semibold">Category Description
                                <span class="text-red-600">*</span>
                            </label>
                            <textarea rows="3"  name="category_description" id="category_description"
                                value="{{ old('category_description') }}"
                                placeholder="Write your category description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            @error('category_description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="category_img" class="font-medium 3xl:text-xl 3xl:font-semibold">Image for category
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="file" name="category_img" id="category_img"
                                value="{{ old('category_img') }}"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-gray-500">Upload a clear photo that describe category.</p>

                            @error('category_img')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        Add new Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
