@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:pt-0 border-b border-[#F1F1F1]">
            <h1 class="text-[26px] font-bold tracking-wide">Sub Categories List</h1>
        </div>

        <form method="GET" action="{{ route('sub-category.list') }}" class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
                <div class="flex flex-col items-start gap-2 w-full">
                    <p class="m-0 text-gray-600 font-medium">Search Sub Category</p>
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name..."
                            class="border border-gray-300 rounded-lg px-4 py-2 w-full pr-10"
                        />
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-4 text-gray-500">
                            search
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search by Category</p>
                    <div class="relative w-full">
                        <select name="categoryId" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none w-full">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('categoryId') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                            chevron_right
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-5 sm:min-w-1/4 sm:justify-end">
                <button type="submit" class="bg-[#334a8b] cursor-pointer text-white px-4 py-2 3xl:px-6 rounded-lg text-sm 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search') || request('categoryId'))
                    <a href="{{ route('sub-category.list', ['limit' => request('limit', 10)]) }}"
                        class="text-sm font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($subCategories as $subCategory)
                <div class="bg-gray-50 rounded-lg shadow-lg p-4">
                    <img src="{{ asset('storage/' . $subCategory->img_path) }}" alt="{{ $subCategory->name }}" class="w-full h-48 object-contain rounded-t-lg">
                    <div class="p-4">
                        <div class="flex items-center text-center gap-4">
                            <img src="{{ asset('storage/' . $subCategory->category->img_path) }}" alt="{{ $subCategory->category->name }}" class="w-12 h-12 rounded-full">
                            <p class="text-sm text-gray-900 font-semibold">{{ $subCategory->category->name }}'s Category</p>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 mt-2">{{ $subCategory->name }}</h2>
                        <p class="text-gray-600 mt-2">{{ $subCategory->description }}</p>
                    </div>
                    <div class="px-4 flex justify-between items-center text-sm">
                        <a href="" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <a href="" class="text-red-600 hover:text-red-800 font-medium">Delete</a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 mt-6">
                    <div class="bg-gray-50 rounded-lg shadow-lg p-4 w-full max-w-xl h-40 mx-auto flex justify-center items-center text-center">
                        <p class="text-lg font-medium">No Sub Category Found</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('sub-category.list') }}" class="">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="search" value="{{ request('category_id') }}">
            <label for="limit">Categories per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="min-w-1/2">
                {{ $subCategories->appends(['limit' => $limit])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
