@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:py-0 flex justify-between items-center text-center">
            <h1 class="text-[26px] font-bold tracking-wide">Sub Sub Categories List</h1>
            <a href="{{ route('sub-sub-category.add') }}"
                class="font-medium bg-[#334a8b]  text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800  border border-[#334a8b]">
                + Add new category                    
            </a>
        </div>

        <form method="GET" action="{{ route('sub-sub-category.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <div class="grid grid-cols-1 lg:grid-cols-3 items-center gap-y-3 gap-x-8 w-full">
                <div class="flex flex-col items-start gap-2 w-full">
                    <p class="m-0 text-gray-600 font-medium">Search by name</p>
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
                <x-category-filters :showSubCategoryFilter="true" />
            </div>
            <div class="flex items-end gap-5 sm:min-w-1/4 sm:justify-end">
                <button type="submit" class="bg-[#334a8b] border border-[#334a8b] cursor-pointer text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search') || request('categoryId') || request('subCategoryId'))
                    <a href="{{ route('sub-sub-category.list', ['limit' => request('limit', 10)]) }}"
                        class="text-sm font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>
        
        <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
            <table class="min-w-full table-auto">
                <thead class="bg-indigo-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Image</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Name</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Description</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Category Name</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Sub Category Name</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($subSubCategories as $category)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                <div class="relative w-full flex justify-center">
                                    <img src="{{ asset('storage/' . $category->img_path) }}" alt="{{ $category->name }}"
                                        class="w-16 h-16 object-cover rounded-md" />
                                </div>
                            </td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">{{ $category->name }}</td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">{{ $category->description }}</td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">{{ $category->category->name }}</td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">{{ $category->subCategory->name }}</td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">
                                <div class="flex justify-center items-center text-center gap-4">
                                    <a href="{{ route('sub-sub-category.edit', $category->id) }}" class="m-0 flex">
                                        <span class="material-symbols-outlined text-blue-500">
                                            edit_square
                                        </span>
                                    </a>
                                    <button onclick="openDeleteModal('{{ route('sub-sub-category.destroy', $category->id) }}', 'Delete {{ $category->name }} category')"
                                        class="flex cursor-pointer">
                                        <span class="material-symbols-outlined text-red-600">
                                            delete
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                No category found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('sub-sub-category.list') }}" class="">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="category_term" value="{{ request('category_term') }}">
            <input type="hidden" name="categoryId" value="{{ request('categoryId') }}">
            <input type="hidden" name="sub_category_term" value="{{ request('sub_category_term') }}">
            <input type="hidden" name="subCategoryId" value="{{ request('subCategoryId') }}">
            <label for="limit">Sub Sub Categories per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="cursor-pointer border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="">
                {{ $subSubCategories->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
<x-delete-modal />
@endsection
