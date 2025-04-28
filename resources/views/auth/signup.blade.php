@extends('auth.main')

@section('content')
<div class="bg-[#134c9b] flex items-center justify-center min-h-screen flex-col bg-left-top bg-no-repeat">
    <div class="flex flex-col-reverse lg:grid lg:grid-cols-5 h-full w-full z-20">
        <div class="flex justify-center items-center w-full h-full lg:col-span-3">
            <img src="{{ asset('images/auth-bg-1.png') }}" alt="Logo" class="w-full lg:h-screen">
        </div>
        <div class="lg:col-span-2 flex relative w-full">
            <div class="bg-[#0a316c] hidden lg:block lg:absolute bottom-0 w-full h-[30%]"></div>
            <div class="flex flex-col justify-center items-center h-full w-full py-6 sm:py-8 lg:py-4 xl:py-12 3xl:py-20 pl-6 sm:pl-8 lg:pl-0 pr-6 sm:pr-8 lg:pr-4 xl:pr-12 3xl:pr-20">
                <div class="z-20 p-6 sm:p-8 3xl:p-12 bg-white rounded-4xl shadow-lg w-full h-fit-content">
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h1 class="text-center text-4xl 3xl:text-6xl font-bold text-gray-900">
                        Register your account
                    </h1>
                    <form class="mt-8 space-y-6" method="POST" action="{{ route('signup.submit') }}">
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
                            <x-password-field 
                                name="password" 
                                label="Password" 
                                value="{{ old('password') }}"
                                id="signup-password"
                            />
                            <x-password-field 
                                name="password_confirmation" 
                                label="Password Confirmation" 
                                value="{{ old('password_confirmation') }}"
                                id="signup-password-confirmation"
                            />
                        </div>
                        <div>
                            <button type="submit"
                                class="group cursor-pointer relative w-full flex justify-center py-2 px-4 3xl:py-3.5 border border-transparent text-sm 3xl:text-xl font-medium rounded-md text-white bg-[#334b8c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334b8c]">
                                Register
                            </button>
                            <div class="flex items-center justify-center mt-2">
                                <div class="text-sm">
                                    <p class="font-medium text-neutral-600 3xl:text-lg">
                                        Already have an account.  
                                        <a href="{{ route('login') }}" class="font-medium text-blue-600 underline">
                                            Go to login
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="grid grid-cols-4 pt-8 pb-4 items-center w-4/5 mx-auto gap-2">
                        <img src="{{ asset('images/favicon.png') }}" alt="Logo" class=" pointer-events-none mx-auto">   
                        <img src="{{ asset('images/logo-blue.png') }}" alt="company-name" class="col-span-3 pointer-events-none mx-auto">   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
