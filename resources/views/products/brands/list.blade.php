@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:py-0">
            <h1 class="text-[26px] font-bold tracking-wide">Brands List</h1>
        </div>

        <form method="GET" action="{{ route('products.brand.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <div class="flex xl:items-center flex-col xl:flex-row gap-y-3 gap-x-8">
                <div class="flex flex-col gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search Brands</p>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name..."
                            class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:min-w-72 pr-10"
                        />
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-4 text-gray-500">
                            search
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-end gap-5">
                <button type="submit" class="bg-[#334a8b] border border-[#334a8b] text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search'))
                    <a href="{{ route('products.brand.list', ['limit' => request('limit', 10)]) }}"
                        class=" font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
            <table class="min-w-full table-auto">
                <thead class="bg-indigo-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Brand Image</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Brand Name</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Brand description</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($brands as $brand)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                <div class="relative w-full flex justify-center">
                                    <img src="{{ asset('storage/' . $brand->img_path) }}" alt="{{ $brand->name }}"
                                        class="h-16 max-w-full object-contain rounded-md" />
                                </div>
                            </td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">{{ $brand->name }}</td>
                            <td class="px-4 py-2 border text-center border-gray-200 text-sm text-gray-800">{{ $brand->description }}</td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">
                                <div>
                                    <a href="{{ route('products.brand.edit', $brand->id) }}"
                                        class="text-sm font-medium  px-4 py-2 text-blue-600">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('products.brand.list') }}" class="">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <label for="limit">Brands per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="">
                {{ $brands->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection
