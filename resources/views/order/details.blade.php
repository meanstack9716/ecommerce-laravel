@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Order Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-500 mt-1">Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-6 py-2 rounded-lg text-sm font-medium 
                        {{ $order->status == 'Pending' ? 'bg-orange-100 text-orange-600' : '' }}
                        {{ $order->status == 'Confirmed' ? 'bg-green-100 text-green-600' : '' }}
                        {{ $order->status == 'Processing' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status == 'Shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $order->status == 'Delivered' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status == 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-4">

                                <div class="w-full sm:w-40 flex-shrink-0">
                                    <div class="relative overflow-hidden rounded-lg bg-gray-100" style="padding-bottom: 125%;">
                                        @foreach($item->product->gallery as $image)
                                            @if($image->color == $item->selected_color_name)
                                                <img src="{{ asset('storage/' . $image->img_path) }}"
                                                    alt="{{ $item->product->title }} - {{ $image->color }}"
                                                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 opacity-100">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->product->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $item->product->description }}</p>
                                    
                                    <div class="mt-3 flex items-center gap-2">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-medium">
                                            Size: {{ $item->selected_size }}
                                        </span>
                                        <span class="flex items-center">
                                            <span class="w-4 h-4 rounded-full inline-block mr-1 border border-gray-300" 
                                                  style="background-color: {{ $item->selected_color }}"></span>
                                            {{ $item->selected_color_name }}
                                        </span>
                                        <span class="text-gray-500 text-sm">× {{ $item->quantity }}</span>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <span class="text-lg font-semibold">₹{{ number_format($item->final_price * $item->quantity, 2) }}</span>
                                        @if($item->discount_percent > 0)
                                        <span class="ml-2 text-sm text-gray-500 line-through">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        <span class="ml-2 text-sm text-green-600">{{ $item->discount_percent }}% off</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Specifications -->
                                    <div class="mt-4 text-sm text-gray-600">
                                        {!! $item->product->details !!}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seller Info -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900">Sold by</h4>
                                <div class="mt-2 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">{{ substr($item->product->seller->business_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ $item->product->seller->business_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->product->seller->business_type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Delivery Address -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Contact Information</h2>
                        <p class="font-medium mt-1 text-sm">Name:
                            <span class="text-gray-500 ml-1 font-normal" >{{ $order->contact_name ?? $order->user->first_name}}</span>
                        </p>
                        <p class="font-medium mt-1 text-sm">Number:
                            <span class="text-gray-500 ml-1 font-normal" >{{ $order->contact_number ?? $order->user->phone_number}}</span>
                        </p>
                    </div>
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Delivery Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex-shrink-0 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $order->shipping_address_type }} Address</h3>
                                <p class="mt-1 text-sm text-gray-600 whitespace-pre-line">{{ $order->shipping_address }}</p>
                                @if($order->order_note)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-900">Delivery Instructions</h4>
                                    <p class="mt-1 text-sm text-gray-600">{{ $order->order_note }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Order Summary -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Subtotal ({{ count($order->items) }} items)</span>
                                <span class="text-sm font-medium">₹{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Shipping</span>
                                <span class="text-sm font-medium text-green-600">FREE</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t border-gray-200">
                                <span class="text-base font-medium text-gray-900">Total</span>
                                <span class="text-base font-bold">₹{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Payment Method</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ $order->payment_method }}</p>
                            </div>
                            <span class="px-6 py-2 rounded-lg text-sm font-medium 
                                {{ $order->payment_status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-600' }}">
                                {{ $order->payment_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection