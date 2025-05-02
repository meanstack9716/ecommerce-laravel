@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-8 w-full">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="py-5 xl:pt-0 border-b border-[#F1F1F1]">
            <h1 class="text-[26px] font-bold tracking-wide">All users</h1>
        </div>

        <form method="GET" action="{{ route('user.list') }}" class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-4">
            <input type="text" name="limit" value="{{ $limit }}" class="hidden"/>
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
                        <select name="role" class="border cursor-pointer border-gray-300 rounded-lg px-4 py-2 appearance-none min-w-40 pr-10">
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
            </div>
            <div class="flex items-center gap-5">
                <button type="submit" class="bg-[#334a8b] text-white px-4 py-2 3xl:px-6 rounded-lg text-sm 3xl:text-lg hover:bg-blue-800 font-medium">
                    Apply Filters
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('user.list', ['limit' => request('limit', 10)]) }}"
                        class="text-sm font-medium  px-4 py-2 border border-red-500 rounded-lg text-red-500">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
            <table class="min-w-full table-auto">
                <thead class="bg-indigo-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center text-sm font-semibold min-w-40">Name</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold min-w-40">Email</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold min-w-40">Role</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold min-w-40">Phone Number</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold min-w-40">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                @if ($user && ($user->first_name || $user->last_name))
                                   {{ $user->first_name ?? "" }}{{" "}}{{ $user->last_name ?? "" }}
                                @else 
                                    --
                                @endif 
                            </td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $user->email }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">
                                @if ($user->is_admin) 
                                    Admin
                                @else
                                    {{ $user->role->name ?? '--' }}
                                @endif
                            </td>
                            <td class="px-6 py-3 text-sm text-center text-gray-800">{{ $user->phone_number ?? '--' }}</td>   
                            <td></td>   
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
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
                <label for="limit">Users per page:</label>
                <select name="limit" id="limit" onchange="this.form.submit()" class="border border-gray-200 py-3 px-2 rounded-lg">
                    @foreach([5, 10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="min-w-1/2">
                {{ $users->appends(['limit' => $limit])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
