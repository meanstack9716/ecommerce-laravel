@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:py-0 flex justify-between items-center text-center">
            <h1 class="text-[26px] font-bold tracking-wide">Sub Categories List</h1>
            <a href="{{ route('sub-category.add') }}"
                class="font-medium bg-[#334a8b]  text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800  border border-[#334a8b]">
                + Add new category                    
            </a>
        </div>

        <form method="GET" action="{{ route('sub-category.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
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
                <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search by Category</p>
                    <div class="relative w-full">
                        <input 
                            type="text" 
                            id="category-search" 
                            name="category_term" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Search category type"
                            value="{{ request('category_term') }}"
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
                        name="categoryId" 
                        value="{{ request('categoryId') }}"
                    >
                </div>
            </div>
            <div class="flex items-end gap-5 sm:min-w-1/4 sm:justify-end">
                <button type="submit" class="bg-[#334a8b] cursor-pointer border border-[#334a8b] text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search') || request('categoryId'))
                    <a href="{{ route('sub-category.list', ['limit' => request('limit', 10)]) }}"
                        class="font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
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
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($subCategories as $category)
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
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">
                                <div class="flex justify-center items-center text-center gap-4">
                                    <a href="{{ route('sub-category.edit', $category->id) }}" class="m-0 flex">
                                        <span class="material-symbols-outlined text-blue-500">
                                            edit_square
                                        </span>
                                    </a>
                                    <button onclick="openDeleteModal('{{ route('sub-category.destroy', $category->id) }}', 'Delete {{ $category->name }} category')"
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
                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                No category found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('sub-category.list') }}" class="">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="category_term" value="{{ request('category_term') }}">
            <input type="hidden" name="categoryId" value="{{ request('categoryId') }}">
            <label for="limit">Categories per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="">
                {{ $subCategories->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
<x-delete-modal />

<script>
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
