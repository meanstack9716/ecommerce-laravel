@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:py-0">
            <h1 class="text-[26px] font-bold tracking-wide">Promo Code List</h1>
        </div>

        @if(auth()->user()->is_admin)
        <form method="GET" action="{{ route('promo-code.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
                <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search by Seller Name</p>
                    <div class="relative w-full">
                        <select name="sellerId" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none w-full">
                            <option value="">All Sellers</option>
                            @foreach ($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ request('sellerId') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->business_name }}
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
                @if(request('sellerId'))
                    <a href="{{ route('promo-code.list', ['limit' => request('limit', 10)]) }}"
                        class="font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>
        @endif

        <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
            <table class="min-w-full table-auto">
                <thead class="bg-indigo-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Code</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Description</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Discount</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Valid from</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Valid till</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Uses</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Created by</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($codes as $code)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-4 py-4 border text-sm text-center border-gray-200 font-medium">{{ $code->code }}</td>
                            <td class="px-4 py-4 text-sm border text-left border-gray-200 text-gray-500">{!! $code->description !!} </td>
                            <td class="px-4 py-4 text-sm border text-center border-gray-200 text-gray-500">
                               @if($code->discount_type == 'percentage')
                                    {{ $code->discount_value }}% 
                                    @if($code->max_discount_amount > 0)
                                        <span class="text-muted">( max {{ $code->max_discount_amount }} Rs )</span>
                                    @endif
                                @else
                                    {{ ($code->discount_value) }} Rs
                                @endif
                            </td>
                            <td class="px-4 py-4 border text-sm text-center border-gray-200 text-gray-500">
                                {{ \Carbon\Carbon::parse($code->start_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 border text-sm text-center border-gray-200 text-gray-500">
                                {{ \Carbon\Carbon::parse($code->expiry_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm border text-center border-gray-200 text-gray-500">
                                {{ $code->used_count }}
                                @if($code->max_uses)
                                    /{{ $code->max_uses }}
                                @endif
                            </td>
                            <td class="px-4 py-2 border text-center border-gray-200 text-gray-500">
                                @if($code->is_active)
                                    <span class="bg-green-100 text-green-600 rounded px-4 py-2 text-sm font-medium tracking-wide">Active</span>
                                @else
                                    <span class="bg-red-100 text-red-600 rounded px-4 py-2 text-sm font-medium tracking-wide">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm border text-center border-gray-200 text-gray-500">
                                {{ $code->seller->business_name }}
                            </td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">
                                <div>
                                    <a href="{{ route('promo-code.edit', $code->id) }}"
                                        class="text-sm font-medium  px-4 py-2 text-blue-600">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-4 text-center text-sm text-gray-500">
                                No Promo code found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('promo-code.list') }}" class="">
            <label for="limit">Orders per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="">
                {{ $codes->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection
