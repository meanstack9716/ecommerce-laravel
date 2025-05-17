@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-5">
        <div class="py-5 xl:py-0">
            <h1 class="text-[26px] font-bold tracking-wide">Products List</h1>
        </div>

        <form method="GET" action="{{ route('products.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
                <div class="flex flex-col items-start gap-2 w-full">
                    <p class="m-0 text-gray-600 font-medium">Search Products</p>
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
            <div class="flex items-end gap-5 sm:min-w-1/4 sm:justify-end">
                <button type="submit" class="bg-[#334a8b] border border-[#334a8b] cursor-pointer text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search') || request('categoryId'))
                    <a href="{{ route('products.list', ['limit' => request('limit', 10)]) }}"
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
                        <th class="px-6 py-3 text-sm min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Image</th>
                        <th class="px-6 py-3 text-sm min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Title</th>
                        <th class="px-6 py-3 text-sm min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Price</th>
                        <th class="px-6 py-3 text-sm min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Discount</th>
                        <th class="px-6 py-3 text-sm min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product SKU</th>
                        <th class="px-6 py-3 text-sm min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $product)
                     <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                        <td class="px-6 py-3 text-sm text-center text-gray-800">
                            <div class="relative w-full flex justify-center">
                                <img src="{{ asset('storage/' . $product->thumbnail_path) }}" alt="{{ $product->title }}"
                                    class="w-16 h-16 object-cover rounded-md" />
                            </div>
                        </td>

                        <td class="px-4 py-2 border text-center text-sm text-gray-700 border-gray-200 font-medium">{{ $product->title }}</td>

                        <td class="px-4 py-2 border text-center border-gray-200">
                            <p class="font-bold text-black">₹{{ $product->final_price }}</p>
                            @if($product->discount_percent > 0)
                                <p class="text-xs text-gray-500 line-through">₹{{ $product->price }}</p>
                            @endIf
                        </td>
                    
                        <td class="px-4 py-2 border text-center text-gray-500 border-gray-200 font-medium">
                            @if($product->discount_percent > 0)
                                {{ $product->discount_percent }}%
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-4 py-2 border text-center border-gray-200 font-medium">{{ $product->sku }}</td>

                        <td class="px-4 py-2 border text-center border-gray-200 font-medium">
                            <a href="{{ route('product.edit.form', $product->id) }}"
                                class="text-blue-600 hover:underline text-sm">Edit Details</a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                No product found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('products.list') }}" class="">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="flex items-center gap-2 relative w-fit">
                    <span class="text-sm text-gray-600">Items per page:</span>
                    <div class="relative">
                        <select name="limit" id="limit" onchange="this.form.submit()"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 appearance-none">
                            @foreach([5, 10, 25, 50, 100] as $option)
                                <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        <span
                            class="material-symbols-outlined pointer-events-none absolute right-1 top-1/2 transform -translate-y-1/2 text-gray-500">
                            expand_more
                        </span>
                    </div>
                </div>
            </form>
            <div class="">
                {{ $products->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection
