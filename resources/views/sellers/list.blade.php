@extends('layouts.main')

@section('content')
<?php
    use App\Constants\Constants;
?>

@php
    $tableFields = [
        [
            'label' => 'Name',
            'field' => 'name',
            'allow_sort' => false
        ],
        [
            'label' => 'Email',
            'field' => 'email',
            'allow_sort' => false
        ],
        [
            'label' => 'Phone Number',
            'field' => 'phone_number',
            'allow_sort' => false
        ],
        [
            'label' => 'Business Name',
            'field' => 'business_name',
            'allow_sort' => true
        ],
        [
            'label' => 'Business Type',
            'field' => 'business_type',
            'allow_sort' => true
        ],
        [
            'label' => 'Business Email',
            'field' => 'business_email',
            'allow_sort' => true
        ],
        [
            'label' => 'Business Mobile',
            'field' => 'business_mobile',
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
<div class="p-4 sm:p-6 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-4">
        <div class="py-5 xl:py-0 flex justify-between items-center text-center">
            <h1 class="text-[26px] font-bold tracking-wide">All Sellers</h1>
            <a href="{{ route('seller.register.personal') }}"
                class="font-medium bg-[#334a8b]  text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800  border border-[#334a8b]">
                + Add new seller                    
            </a>
        </div>
        <form method="GET" action="{{  route('seller.list') }}" class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <input type="text" name="sort_by" value="{{ request('sort_by') }}" class="hidden"/>
            <input type="text" name="sort_order" value="{{ request('sort_order', 'asc') }}" class="hidden"/>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-y-3 gap-x-8 w-full">
                <div class="flex flex-col items-start gap-2 w-full">
                    <p class="m-0 text-gray-600 font-medium">Search user</p>
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="border border-gray-300 rounded-lg px-4 py-2 w-full pr-10"
                        />
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-4 text-gray-500">
                            search
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search by Status</p>
                    <div class="relative w-full">
                        <select name="status" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none w-full">
                            <option value="">All</option>
                            <option value="{{ Constants::STATUS_PENDING }}"  {{ request('status') ==  Constants::STATUS_PENDING ? 'selected' : ''}}>{{ Constants::STATUS_PENDING }}</option>
                            <option value="{{ Constants::STATUS_APPROVED }}"  {{ request('status') ==  Constants::STATUS_APPROVED ? 'selected' : ''}}>{{ Constants::STATUS_APPROVED }}</option>
                            <option value="{{ Constants::STATUS_REJECTED }}"  {{ request('status') ==  Constants::STATUS_REJECTED ? 'selected' : ''}}>{{ Constants::STATUS_REJECTED }}</option>
                        </select>
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                            chevron_right
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-end gap-5 sm:min-w-1/4 sm:justify-end">
                <button type="submit" class="bg-[#334a8b] cursor-pointer border border-[#334a8b] text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('sub-category.list', ['limit' => request('limit', 10)]) }}"
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
                    routeName="seller.list"
                    :sortBy="request('sort_by')"
                    :sortOrder="request('sort_order', 'asc')"
                />
                <tbody class="divide-y divide-gray-200">
                    @forelse ($sellers as $seller)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                @if ($seller && ($seller->userDetails->first_name || $seller->userDetails->last_name))
                                   {{ $seller->userDetails->first_name ?? "" }}{{" "}}{{ $seller->userDetails->last_name ?? "" }}
                                @else 
                                    --
                                @endif 
                            </td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $seller->userDetails->email }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                {{ $seller->userDetails->phone_number ?? '--' }}
                            </td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $seller->business_name ?? '--' }}</td>   
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $seller->business_type ?? '--' }}</td>   
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $seller->business_email ?? '--' }}</td>   
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $seller->business_mobile ?? '--' }}</td>   
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                <div class="flex items-center text-center justify-center gap-2
                                    {{ $seller->status == Constants::STATUS_PENDING ? 'text-yellow-500' : '' }}
                                    {{ $seller->status == Constants::STATUS_APPROVED ? 'text-green-500' : '' }}
                                    {{ $seller->status == Constants::STATUS_REJECTED ? 'text-red-500' : '' }}">
                                    @if( $seller->status == Constants::STATUS_APPROVED)
                                        <span class="material-symbols-outlined">
                                            verified
                                        </span>
                                    @elseif ($seller->status == Constants::STATUS_PENDING)
                                        <span class="material-symbols-outlined">
                                            pending
                                        </span>
                                    @elseif ($seller->status == Constants::STATUS_REJECTED)
                                        <span class="material-symbols-outlined">
                                            cancel
                                        </span>
                                    @endif
                                    <span>
                                        {{ $seller->status ?? '--' }}
                                    </span>
                                </div>
                            </td>   
                            <td  class="px-6 py-3 text-sm text-center text-gray-800">
                                <div class="flex justify-center items-center text-center gap-4">
                                    <a href="{{ route('seller.edit', $seller->id) }}" class="m-0 flex">
                                        <span class="material-symbols-outlined text-blue-500">
                                            edit_square
                                        </span>
                                    </a>
                                </div>
                            </td>   
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-4 text-center text-sm text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('seller.list') }}" class="">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"/>
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}"/>
                <label for="limit">Users per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="">
                {{ $sellers->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection
