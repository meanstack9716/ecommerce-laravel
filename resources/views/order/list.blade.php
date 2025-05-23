@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:py-0">
            <h1 class="text-[26px] font-bold tracking-wide">Orders List</h1>
        </div>

        <form method="GET" action="{{ route('orders.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
                 @if(auth()->user()->is_admin)
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
                @endif

                 <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search by Status</p>
                    <div class="relative w-full">
                        <select name="status" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none w-full">
                            <option value="">All Status</option>
                            @foreach(\App\Enums\OrderStatus::cases() as $status)
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
                <thead class="bg-indigo-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Order number</th>
                        @if(auth()->user()->is_admin)
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Seller Name</th>
                        @endif
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Shipping Address</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Payment Method</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Total Amount</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Date</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider min-w-60">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-4 py-4 border text-center border-gray-200 font-medium">{{ $order->order_number }}</td>
                            @if(auth()->user()->is_admin)
                                <td class="px-4 py-4 border text-center border-gray-200">
                                    <div class="">{{ $order->seller->business_name }}</div>
                                </td>
                            @endif
                            <td class="px-4 py-4 border text-left border-gray-200">
                                <div class="">{{ $order->shipping_address_type }}</div>
                                <div class="text-sm text-gray-500">{{ $order->shipping_address }}</div>
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200">
                                <span class="px-4 py-1 text-sm font-semibold rounded-full 
                                    {{ $order->payment_method == 'COD' || $order->payment_method == 'Cash On Delivery' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }}">
                                    {{ $order->payment_method }}
                                </span>
                                <div class="text-sm mt-1 {{ $order->payment_status == 'Paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $order->payment_status }}
                                </div>
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200 font-medium">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-4 py-4 border text-center border-gray-200">
                                <form  action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="relative">
                                        <select name="status" id="status" onchange="this.form.submit()" 
                                            class="cursor-pointer mt-1 block appearance-none w-full border border-gray-200 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @foreach(\App\Enums\OrderStatus::cases() as $status)
                                                <option value="{{ $status->value }}" {{ $order->status === $status->value ? 'selected' : '' }}>
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
                            <td class="px-4 py-4 border text-center border-gray-200 text-gray-500">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 border text-center border-gray-200 font-medium">
                                <a href="{{ route('orders.details.show', $order->id) }}"
                                    class="text-sm font-medium  px-4 py-2 text-blue-600">
                                    <span class="material-symbols-outlined">
                                        open_in_new
                                    </span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->is_admin ? 8 : 7 }}" class="px-4 py-4 text-center text-sm text-gray-500">
                                No Order found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('orders.list') }}" class="">
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
                {{ $orders->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection
