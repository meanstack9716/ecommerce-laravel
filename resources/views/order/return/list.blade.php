@extends('layouts.main')
@php
    $tableFields = [
        [
            'label' => 'Order Number',
            'field' => '',
            'allow_sort' => false
        ],
        [
            'label' => 'Seller Name',
            'field' => '',
            'hide' => auth()->user()->is_admin ? false  : true,
            'allow_sort' => false
        ],
        [
            'label' => 'Customer Name',
            'field' => '',
            'allow_sort' => false
        ],
        [
            'label' => 'Product Name',
            'field' => '',
            'allow_sort' => false
        ],
        [
            'label' => 'Reason',
            'field' => 'reason',
            'allow_sort' => true
        ],
        [
            'label' => 'Refund amount',
            'field' => 'reason',
            'allow_sort' => true
        ],
        [
            'label' => 'Requested At',
            'field' => 'requested_at',
            'allow_sort' => true
        ],
        [
            'label' => 'Status',
            'field' => 'status',
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
        <div class="py-5 xl:py-0">
            <h1 class="text-[26px] font-bold tracking-wide">Orders Return Requests</h1>
        </div>

        <form method="GET" action="{{ route('orders.return.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"/>
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
                @if(auth()->user()->is_admin)
                <x-seller-filter />
                @endif

                 <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search by Status</p>
                    <div class="relative w-full">
                        <select name="status" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none w-full">
                            <option value="">All Status</option>
                            @foreach(\App\Enums\ReturnRequestStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                    {{ ucfirst($status->value) }}
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
                @if(request('status') || request('sellerId'))
                    <a href="{{ route('orders.list', ['limit' => request('limit', 10)]) }}"
                        class="font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
            <table class="min-w-full table-auto">
                <x-table-header 
                    :fields="$tableFields" 
                    routeName="orders.return.list"
                    :sortBy="request('sort_by')"
                    :sortOrder="request('sort_order', 'asc')"
                />
                <tbody class="divide-y divide-gray-200">
                     @forelse ($requests as $request)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-4 py-4 border text-center border-gray-200 font-medium">{{ $request->order->order_number }}</td>
                            @if(auth()->user()->is_admin)
                                <td class="px-4 py-4 border text-center border-gray-200">
                                    <div class="">{{ $request->seller->business_name }}</div>
                                </td>
                            @endif
                            <td class="px-4 py-4 border text-center border-gray-200">
                                <div>{{ $request->user->email}}</div>
                                <div>{{ $request->user->first_name ?? "" }}{{" "}}{{ $request->user->last_name ?? "" }}</div>
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200">
                                {{ $request->product->title }}
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200">
                                {{ $request->reason }}
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200">
                                {{ $request->refund_amount }}
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200">
                                {{ \Carbon\Carbon::parse($request->requested_at)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200">
                                <form method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="relative">
                                        <select name="status" id="status" onchange="this.form.submit()" 
                                            class="cursor-pointer mt-1 block appearance-none w-full border border-gray-200 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @foreach(\App\Enums\ReturnRequestStatus::cases() as $status)
                                                <option value="{{ $status->value }}" {{ $request->status === $status->value ? 'selected' : '' }}>
                                                    {{ ucfirst($status->value) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                                            chevron_right
                                        </span>
                                    </div>
                                </form>
                            </td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">
                                <a href=""
                                    class="text-sm font-medium  px-4 py-2 text-blue-600">
                                    <span class="material-symbols-outlined">
                                        open_in_new
                                    </span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->is_admin ? 9 : 8 }}" class="px-4 py-4 text-center text-sm text-gray-500">
                                No Request found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('orders.return.list') }}" class="">
            <input type="hidden" name="seller_term" value="{{ request('seller_term') }}">
            <input type="hidden" name="sellerId" value="{{ request('sellerId') }}">
            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"/>
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}"/>
            <label for="limit">Requests per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="">
                {{ $requests->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection
