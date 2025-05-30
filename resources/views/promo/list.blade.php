@extends('layouts.main')

@php
    $tableFields = [
        [
            'label' => 'Code',
            'field' => 'code',
            'allow_sort' => true
        ],
        [
            'label' => 'Description',
            'field' => 'description',
            'allow_sort' => true
        ],
        [
            'label' => 'Discount',
            'field' => 'discount_value',
            'allow_sort' => true
        ],
        [
            'label' => 'Valid from',
            'field' => 'start_date',
            'allow_sort' => true
        ],
        [
            'label' => 'Valid till',
            'field' => 'expiry_date',
            'allow_sort' => true
        ],
        [
            'label' => 'Uses',
            'field' => 'used_count',
            'allow_sort' => true
        ],
        [
            'label' => 'Status',
            'field' => '',
            'allow_sort' => false
        ],
        [
            'label' => 'Created by',
            'field' => '',
            'allow_sort' => false
        ],
        [
            'label' => 'Actions',
            'field' => '',
            'allow_sort' => false
        ],
    ];
@endphp

@section('content')
<div class="p-4 sm:p-8 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:py-0 flex justify-between items-center text-center">
            <h1 class="text-[26px] font-bold tracking-wide">Promo codes List</h1>
            <a href="{{ route('promo-code.add') }}"
                class="font-medium bg-[#334a8b]  text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800  border border-[#334a8b]">
                + Add new promo code                    
            </a>
        </div>

        @if(auth()->user()->is_admin)
        <form method="GET" action="{{ route('promo-code.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"/>
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
                <x-seller-filter />
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
                <x-table-header 
                    :fields="$tableFields" 
                    routeName="promo-code.list"
                    :sortBy="request('sort_by')"
                    :sortOrder="request('sort_order', 'asc')"
                />
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
                                <div class="flex justify-center items-center text-center gap-4">
                                    <a href="{{ route('promo-code.edit', $code->id) }}" class="m-0 flex">
                                        <span class="material-symbols-outlined text-blue-500">
                                            edit_square
                                        </span>
                                    </a>
                                    <button onclick="openDeleteModal('{{ route('promo-code.delete', $code->id) }}', 'Delete {{ $code->code }}')"
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
            <input type="hidden" name="seller_term" value="{{ request('seller_term') }}">
            <input type="hidden" name="sellerId" value="{{ request('sellerId') }}">
            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"/>
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}"/>
            <label for="limit">Orders per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="cursor-pointer border border-gray-200 py-3 px-2 rounded-lg">
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
<x-delete-modal />
@endsection
