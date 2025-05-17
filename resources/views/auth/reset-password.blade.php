@extends('auth.main')

@section('content')
<div class="flex p-6 lg:p-8 items-center justify-center min-h-screen flex-col bg-contain bg-center" style="background-image: url('{{ asset('images/bg-blue.png') }}')">
    <div class="z-20 p-6 sm:p-8 bg-white rounded-4xl shadow-md max-w-lg w-full 3xl:max-w-2xl relative">
       <a href="{{ route('forgot-password') }}" class="text-5xl absolute left-8 top-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg>
        </a>

        <img src="{{ asset('icons/lock.png') }}" alt="Logo" class="pointer-events-none mx-auto w-1/4 3xl:w-1/5 h-auto mb-5 aspect-square">
        <div class="text- mb-2 3xl:mb-4">
            <h1 class="text-center text-3xl 3xl:text-5xl font-bold text-gray-900">
                Reset New Password
            </h1>
        </div>
        <p class="text-[#515151] text-center w-3/4 mx-auto mt-2 3xl:text-xl">Enter the new password for your account.</p>
        <form class="mt-4" method="POST" action="{{ route('password.reset') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="code" value="{{ $code }}">
            <div class="space-y-4 3xl:space-y-6 border-0">
                <x-password-field 
                    name="password" 
                    label="New Password" 
                    value="{{ old('password') }}"
                    id="reset-password"
                />
                <x-password-field 
                    name="password_confirmation" 
                    label="New Password Confirmation" 
                    value="{{ old('password') }}"
                    id="reset-password-confirmation"
                />
                @if(session('error'))
                    <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                <button type="submit"
                    class="mt-6 group cursor-pointer relative w-full flex justify-center py-2 px-4 3xl:py-3.5 border border-transparent text-sm 3xl:text-xl font-medium rounded-md text-white bg-[#334b8c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334b8c]">
                    Reset New Password
                </button>
            </div>
        </form>
        <div  class="grid grid-cols-3 pt-8 pb-4 items-center w-2/3 mx-auto gap-2">
            <img src="{{ asset('images/logo-blue.png') }}" alt="company-name" class="col-span-3 pointer-events-none mx-auto">   
        </div>
    </div>
</div>
@endsection