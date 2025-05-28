@extends('layouts.main')

@section('content')
<?php
    use App\Constants\Constants;
?>
@php
    $tableFields = [
        [
            'label' => 'First Name',
            'field' => 'first_name',
            'allow_sort' => true
        ],
        [
            'label' => 'Last Name',
            'field' => 'last_name',
            'allow_sort' => true
        ],
        [
            'label' => 'Email',
            'field' => 'email',
            'allow_sort' => true
        ],
        [
            'label' => 'Role',
            'field' => 'role',
            'allow_sort' => false
        ],
        [
            'label' => 'Phone Number',
            'field' => 'phone_number',
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
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:py-0 flex justify-between items-center text-center">
            <h1 class="text-[26px] font-bold tracking-wide">All users</h1>
            <a href="{{ route('user.create') }}"
                class="font-medium bg-[#334a8b]  text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800  border border-[#334a8b]">
                + Add new User                    
            </a>
        </div>

        <form method="GET" action="{{ route('user.list') }}" class="flex flex-col justify-between sm:flex-row gap-3 mb-4 ">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
            <input type="text" name="sort_by" value="{{ request('sort_by') }}" class="hidden"/>
            <input type="text" name="sort_order" value="{{ request('sort_order', 'asc') }}" class="hidden"/>
            <div class="flex xl:items-center flex-col xl:flex-row gap-y-3 gap-x-8">
                <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Search User</p>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="border border-gray-300 rounded-lg px-4 py-2 w-full min-w-64 sm:min-w-72 pr-10"
                        />
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-4 text-gray-500">
                            search
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Select Role</p>
                    <div class="relative">
                        <select name="role" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none min-w-40 xl:min-w-56 pr-10">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                        chevron_right
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-2">
                    <p class="m-0 text-gray-600 font-medium">Select Status</p>
                    <div class="relative">
                        <select name="status" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none min-w-40 xl:min-w-56 pr-10">
                            <option value="">All Status</option>
                            <option value="{{ Constants::STATUS_ACTIVATED }}"  {{ request('status') ==  Constants::STATUS_ACTIVATED ? 'selected' : ''}}>{{ Constants::STATUS_ACTIVATED }}</option>
                            <option value="{{ Constants::STATUS_DEACTIVATED }}"  {{ request('status') ==  Constants::STATUS_DEACTIVATED ? 'selected' : ''}}>{{ Constants::STATUS_DEACTIVATED }}</option>
                            <option value="{{ Constants::STATUS_ON_HOLD }}"  {{ request('status') ==  Constants::STATUS_ON_HOLD ? 'selected' : ''}}>{{ Constants::STATUS_ON_HOLD }}</option>
                        </select>
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                            chevron_right
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-end gap-5">
                <button type="submit" class="cursor-pointer bg-[#334a8b] text-white px-4 py-2 3xl:px-6 rounded-lg 3xl:text-lg hover:bg-blue-800 font-medium border border-[#334a8b]">
                    Apply Filters
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('user.list', ['limit' => request('limit', 10)]) }}"
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
                    routeName="user.list"
                    :sortBy="request('sort_by')"
                    :sortOrder="request('sort_order', 'asc')"
                />
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $user->first_name ?? "--" }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $user->last_name ?? "--" }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $user->email }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                @if ($user->is_admin) 
                                    Admin
                                @else
                                    {{ $user->role->name ?? '--' }}
                                @endif
                            </td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $user->phone_number ?? '--' }}</td>   
                            <td class="px-2 py-3 text-sm text-center text-gray-800">
                                <span class="text-sm px-4 py-1 rounded font-medium
                                    {{ $user->status == Constants::STATUS_ON_HOLD ? 'text-yellow-500 bg-yellow-200' : '' }}
                                    {{ $user->status == Constants::STATUS_ACTIVATED ? 'text-green-500 bg-green-100' : 'text-green-500 bg-green-100' }}
                                    {{ $user->status == Constants::STATUS_DEACTIVATED ? 'text-red-500 bg-red-100' : '' }}">
                                    {{ $user->status ?? "Activated"}}
                                </span>
                            </td>   
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                <div class="flex justify-center items-center text-center gap-4">
                                    <a href="{{ route('user.edit', $user->id) }}" class="m-0 flex">
                                        <span class="material-symbols-outlined text-blue-500">
                                            edit_square
                                        </span>
                                    </a>
                                </div>
                            </td>   
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form method="GET" action="{{ route('user.list') }}" class="">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="role" value="{{ request('role') }}">
                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"/>
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}"/>
                <input type="hidden" name="status" value="{{ request('status') }}">
                <label for="limit">Users per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="cursor-pointer border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="">
                {{ $users->appends(['limit' => $limit])->links('pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection
