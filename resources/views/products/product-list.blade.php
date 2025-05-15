@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:pt-0 border-b border-[#F1F1F1]">
            <h1 class="text-[26px] font-bold tracking-wide">Products List</h1>
        </div>

        <form method="GET" action="{{ route('products.list') }}" class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-4">
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
            <div class="flex items-center gap-5 sm:min-w-1/4 sm:justify-end">
                <button type="submit" class="bg-[#334a8b] cursor-pointer text-white px-4 py-2 3xl:px-6 rounded-lg text-sm 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search') || request('categoryId'))
                    <a href="{{ route('products.list', ['limit' => request('limit', 10)]) }}"
                        class="text-sm font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-left text-sm text-gray-700">
                <thead class="bg-gray-50 rounded-t-xl">
                    <tr>
                        <th class="px-6 py-3 text-xs min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Image</th>
                        <th class="px-6 py-3 text-xs min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Title</th>
                        <th class="px-6 py-3 text-xs min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Price</th>
                        <th class="px-6 py-3 text-xs min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product Discount</th>
                        <th class="px-6 py-3 text-xs min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Product SKU</th>
                        <th class="px-6 py-3 text-xs min-w-60 font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center border-gray-200">
                            <div class="relative w-full flex justify-center">
                                <img src="{{ asset('storage/' . $product->thumbnail_path) }}" alt="{{ $product->title }}"
                                    class="w-16 h-16 object-cover rounded-md" />
                            </div>
                        </td>

                        <td class="px-4 py-2 border text-center border-gray-200 font-medium">{{ $product->title }}</td>

                        <td class="px-4 py-2 border text-center border-gray-200">
                            <p class="font-bold text-black">₹{{ $product->final_price }}</p>
                            @if($product->discount_percent > 0)
                                <p class="text-xs text-gray-500 line-through">₹{{ $product->price }}</p>
                            @endIf
                        </td>
                    
                        <td class="px-4 py-2 border text-center border-gray-200">
                            @if($product->discount_percent > 0)
                                {{ $product->discount_percent }}%
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-4 py-2 border text-center border-gray-200">{{ $product->sku }}</td>

                        <td class="px-4 py-2 border text-center border-gray-200">
                            <a href="{{ route('product.edit.form', $product->id) }}"
                                class="text-blue-600 hover:underline text-sm">Edit Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 border border-gray-200">
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
            <div class="min-w-1/2">
                {{ $products->appends(['limit' => $limit])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
