@extends('layouts.main')

<?php
    use App\Constants\Constants;
?>

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Order Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Order #{{ $return->order->order_number }} Return Request</h1>
                    <p class="text-gray-500 mt-1">Placed on {{ \Carbon\Carbon::parse($return->requested_at)->format('F j, Y \a\t g:i A') }}</p>
                    <div class="mt-4">
                        <span class="px-4 py-2 rounded-sm text-sm font-medium 
                            {{  $return->status == Constants::STATUS_APPROVED ? 'text-green-600 bg-green-100' : '' }}
                            {{  $return->status == Constants::STATUS_REJECTED ? 'text-red-600 bg-red-100' : '' }}
                            {{  $return->status == Constants::STATUS_PENDING ? 'text-yellow-600 bg-yellow-100' : '' }}
                            {{  $return->status == Constants::STATUS_REFUNDED ? 'text-orange-600 bg-orange-100' : '' }}"
                        >
                            {{ ucfirst($return->status) }}
                        </span>
                        @if($return->refund_status)
                        <span class="ml-4 px-4 py-2 rounded-sm text-sm font-medium 
                            {{  $return->refund_status == Constants::STATUS_PROCESSED ? 'text-green-600 bg-green-100' : '' }}
                            {{  $return->refund_status == Constants::STATUS_FAILED ? 'text-red-600 bg-red-100' : '' }}
                            {{  $return->refund_status == Constants::STATUS_PENDING ? 'text-yellow-600 bg-yellow-100' : '' }}">  
                            Refund: {{ ucfirst($return->refund_status) }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-gray-500">Return ID: {{ $return->_id }}</p>
                    <p class="text-gray-500">Seller: {{ $return->seller->business_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Items and Return Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Items to Return</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-40 flex-shrink-0">
                                <div class="relative overflow-hidden rounded-lg bg-gray-100" style="padding-bottom: 125%;">
                                    @foreach($return->product->gallery as $image)
                                        @if($image->color == $return->orderItem->selected_color_name)
                                            <img src="{{ asset('storage/' . $image->img_path) }}"
                                                alt="{{ $return->product->title }} - {{ $image->color }}"
                                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 opacity-100">
                                        @endif
                                    @endforeach
                                </div>                                
                            </div>
                                
                            <!-- Product Details -->
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $return->product->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $return->product->description }}</p>
                                    
                                <div class="mt-3 flex items-center gap-2">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-medium">
                                        Size: {{ $return->orderItem->selected_size }}
                                    </span>
                                    <span class="flex items-center">
                                        <span class="w-4 h-4 rounded-full inline-block mr-1 border border-gray-300" 
                                            style="background-color: {{ $return->orderItem->selected_color }}"></span>
                                        {{ $return->orderItem->selected_color_name }}
                                    </span>
                                    <span class="text-gray-500 text-sm">× {{ $return->quantity }}</span>
                                </div>
                                    
                                <div class="mt-3">
                                    <span class="text-lg font-semibold">₹{{ number_format($return->orderItem->final_price * $return->orderItem->quantity, 2) }}</span>
                                    @if($return->orderItem->discount_percent > 0)
                                        <span class="ml-2 text-sm text-gray-500 line-through">₹{{ number_format($return->orderItem->price * $return->orderItem->quantity, 2) }}</span>
                                        <span class="ml-2 text-sm text-green-600">{{ $return->orderItem->discount_percent }}% off</span>
                                    @endif
                                </div>
                                    
                                    <!-- Product Specifications -->
                                <div class="mt-4 text-sm text-gray-600">
                                    {!! $return->product->details !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Details -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Return Details</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Reason for Return</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $return->reason }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Additional Notes</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $return->additional_notes ?? 'None provided' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Requested Refund Amount</h3>
                            <p class="mt-1 text-sm text-gray-900">${{ number_format($return->refund_amount ?? $return->orderItem->price * $return->quantity, 2) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Refund Method</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $return->refund_method ?? 'Original payment method' }}</p>
                        </div>
                        @if($return->images)
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">Attached Images</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($return->images as $image)
                                <div class="w-24 h-24">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Return proof" class="w-full h-full object-cover rounded-md">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Return Timeline</h2>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <span class="bg-blue-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                    <!-- Heroicon name: solid/check -->
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Return request submitted</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $return->requested_at }}">{{ \Carbon\Carbon::parse($return->requested_at)->format('M j, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                @if($return->approved_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex items-center text-center space-x-3">
                                            <div class="relative">
                                                <span class="bg-green-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                    <!-- Heroicon name: solid/check -->
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4 text-left">
                                                <div>
                                                    <p class="text-sm text-gray-500">Return approved</p>
                                                    @if($return->processed_by)
                                                    <p class="text-xs text-gray-400">by {{ $return->processedBy->business_name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $return->approved_at }}">{{ \Carbon\Carbon::parse($return->approved_at)->format('M j, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                @if($return->refunded_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <span class="bg-purple-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                    <!-- Heroicon name: solid/check -->
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Refund processed</p>
                                                    <p class="text-xs text-gray-400">${{ number_format($return->refund_amount, 2) }} via {{ $return->refund_method }}</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $return->refunded_at }}">{{ \Carbon\Carbon::parse($return->refunded_at)->format('M j, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                @if($return->status === 'rejected')
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <span class="bg-red-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                    <!-- Heroicon name: solid/x -->
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Return rejected</p>
                                                    @if($return->processed_by)
                                                    <p class="text-xs text-gray-400">by {{ $return->processedBy->business_name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $return->approved_at }}">{{ \Carbon\Carbon::parse($return->approved_at)->format('M j, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Customer Info and Admin Actions -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Name</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $return->user->first_name ?? "" }}{{" "}} {{ $return->user->last_name ?? "-"}}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Email</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $return->user->email }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Phone</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $return->user->phone_number ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 border-b border-t border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Shipping Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Shipped to</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $return->order->contact_name ?? "--" }}</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $return->order->contact_number ?? "--" }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Delivery Information</h3>
                                <div class="mt-2">
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 flex-shrink-0 text-indigo-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ $return->order->shipping_address_type }} Address</h3>
                                            <p class="mt-1 text-sm text-gray-600 whitespace-pre-line">{{ $return->order->shipping_address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Update Return Status</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('orders.return.update', $return->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <div class="relative">
                                        <select name="status" id="status"
                                            class="cursor-pointer mt-1 block appearance-none w-full min-w-40  border border-gray-200 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @foreach(\App\Enums\ReturnRequestStatus::cases() as $status)
                                                <option value="{{ $status->value }}" {{ $return->status === $status->value ? 'selected' : '' }}>
                                                    {{ ucfirst($status->value) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                                            chevron_right
                                        </span>
                                    </div>
                                </div>

                                <div id="refund-fields" class="{{ in_array($return->status, [Constants::STATUS_APPROVED, Constants::STATUS_REFUNDED]) ? '' : 'hidden' }}">
                                    <div>
                                        <label for="refund_amount" class="block text-sm font-medium text-gray-700">Refund Amount</label>
                                        <x-textfield 
                                            name="refund_amount" 
                                            type="number"
                                            value="{{ $return->refund_amount ?? $return->orderItem->price * $return->quantity }}"
                                            id="refund_amount"
                                        />
                                    </div>

                                    <!-- <div class="mt-4">
                                        <label for="refund_method" class="block text-sm font-medium text-gray-700">Refund Method</label>
                                        <select id="refund_method" name="refund_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="original" {{ ($return->refund_method ?? 'original') === 'original' ? 'selected' : '' }}>Original payment method</option>
                                            <option value="store_credit" {{ ($return->refund_method ?? '') === 'store_credit' ? 'selected' : '' }}>Store credit</option>
                                            <option value="bank_transfer" {{ ($return->refund_method ?? '') === 'bank_transfer' ? 'selected' : '' }}>Bank transfer</option>
                                            <option value="other" {{ ($return->refund_method ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div> -->

                                    <div class="mt-4">
                                        <label for="refund_status" class="block text-sm font-medium text-gray-700">Refund Status</label>
                                        <div class="relative">
                                            <select name="refund_status" id="refund_status"
                                                class="cursor-pointer mt-1 block appearance-none w-full min-w-40 border border-gray-200 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="{{ Constants::STATUS_PENDING }}" {{ $return->refund_status === Constants::STATUS_PENDING ? 'selected' : '' }}>
                                                    {{ Constants::STATUS_PENDING }}
                                                </option>
                                                <option value="{{ Constants::STATUS_PROCESSED }}" {{ $return->refund_status === Constants::STATUS_PROCESSED ? 'selected' : '' }}>
                                                    {{ Constants::STATUS_PROCESSED }}
                                                </option>
                                                <option value="{{ Constants::STATUS_FAILED }}" {{ $return->refund_status === Constants::STATUS_FAILED ? 'selected' : '' }}>
                                                    {{ Constants::STATUS_FAILED }}
                                                </option>
                                            </select>
                                            <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                                                chevron_right
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                                    <textarea id="admin_notes" name="admin_notes" rows="3" class="mt-1 block w-full border disabled:bg-neutral-200 border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ $return->admin_notes ?? '' }}</textarea>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" id="submitButton" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                                        Update Return Request
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection