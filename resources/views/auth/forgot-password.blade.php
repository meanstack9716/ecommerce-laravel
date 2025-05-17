@extends('auth.main')

@section('content')
<div class="flex p-6 lg:p-8 items-center justify-center min-h-screen flex-col bg-contain bg-center" style="background-image: url('{{ asset('images/bg-blue.png') }}')">
    <div class="z-20 p-6 sm:p-8 bg-white rounded-4xl shadow-md max-w-lg w-full 3xl:max-w-2xl">
        <img src="{{ asset('icons/message.png') }}" alt="Logo" class="pointer-events-none mx-auto w-1/4 3xl:w-1/5 mb-2">
        <div class="text-center mb-2">
            <h1 class="text-center text-3xl 3xl:text-5xl font-bold text-gray-900">
                Forgot password
            </h1>
        </div>
        <p class="text-[#515151] text-center w-3/4 mx-auto 3xl:text-xl">Enter your email address and we will send you an OTP to reset your password.</p>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="space-y-4 3xl:space-y-6 border-0">
                <div>
                    <label for="email" class="font-medium 3xl:text-xl 3xl:font-semibold">Email address</label>
                    <input id="email" name="email" type="text"
                        class="appearance-none rounded-md relative block w-full px-3 mt-2 3xl:mt-3 py-2 3xl:py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm 3xl:text-lg"
                        placeholder="Email address" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <button type="submit"
                    class="group w-full cursor-pointer relative flex justify-center py-2 px-4 3xl:py-3.5 border border-transparent text-sm 3xl:text-xl font-medium rounded-md text-white bg-[#334b8c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334b8c]">
                    Reset Password
                </button>
                <div class="flex items-center justify-center mt-2">
                    <div class="text-sm">
                        <p class="font-medium text-neutral-600 3xl:text-lg">
                            Remember your password. 
                            <a href="{{ route('login') }}" class="font-medium text-blue-600 underline">
                                Go to login. 
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
        <div class="grid grid-cols-3 pt-8 pb-4 items-center w-2/3 mx-auto gap-2">
            <img src="{{ asset('images/logo-blue.png') }}" alt="company-name" class="col-span-3 pointer-events-none mx-auto">   
        </div>
    </div>
</div>
@endsection