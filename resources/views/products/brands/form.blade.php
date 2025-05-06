@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6 mt-5">
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Add New Brand</h1>
            <p class="mt-2 text-sm text-gray-600">Fill in the details below to add a new brand name.</p>
        </div>
        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form action="{{ route('products.brand.add.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <x-textfield 
                                name="name" 
                                label="Brand Name" 
                                value="{{ old('name') }}"
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
                                value="{{ old('description') }}"
                                placeholder="Write your description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-file-upload 
                                id="brand_image"
                                name="image"
                                required
                                label="Upload Brand logo or Image"
                                helpText="Images (PNG, JPG, JPEG) upto 5MB"
                            />
                            @error('image')
                                <p class="text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        Add new Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
