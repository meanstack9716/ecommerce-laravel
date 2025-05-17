@extends('auth.main')

@section('content')
<div class="flex p-6 lg:p-8 items-center justify-center min-h-screen flex-col bg-contain bg-center" style="background-image: url('{{ asset('images/bg-blue.png') }}')">
    <div class="z-20 p-6 sm:p-8 bg-white rounded-4xl shadow-md max-w-lg w-full 3xl:max-w-2xl relative">
       <a href="{{ route('forgot-password') }}" class="text-5xl absolute left-8 top-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg>
        </a>

        <img src="{{ asset('icons/verify.png') }}" alt="Logo" class="pointer-events-none mx-auto w-1/4 3xl:w-1/5 h-auto mb-5">
        <div class="text- mb-2 3xl:mb-4">
            <h1 class="text-center text-3xl 3xl:text-5xl font-bold text-gray-900">
                Verify Code
            </h1>
        </div>
        <p class="text-[#515151] text-center w-3/4 mx-auto mt-2 3xl:text-xl">Enter the 6 digits verification code send to your email.</p>
        <div class="text-center mt-4">
            <p class="text-sm 3xl:text-base text-gray-600">
                Code expires in: <span id="countdown" class="font-semibold">10:00</span>
            </p>
        </div>
        <form class="mt-4" method="POST" action="{{ route('password.submit-code') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="grid grid-cols-6 justify-center gap-x-2 sm:gap-x-3 3xl:w-[90%] 3xl:gap-x-5 mx-auto" id="otp-container">
                <input type="text" name="otp1" maxlength="1" 
                    class="aspect-square text-2xl text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#334b8c] focus:border-transparent otp-input"
                    onkeydown="handleOtpInput(event, 'otp1', '', 'otp2')" autofocus id="otp1">
                <input type="text" name="otp2" maxlength="1" 
                    class="aspect-square text-2xl text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#334b8c] focus:border-transparent otp-input"
                    onkeydown="handleOtpInput(event, 'otp2', 'otp1', 'otp3')" id="otp2">
                <input type="text" name="otp3" maxlength="1" 
                    class="aspect-square text-2xl text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#334b8c] focus:border-transparent otp-input"
                    onkeydown="handleOtpInput(event, 'otp3', 'otp2', 'otp4')" id="otp3">
                <input type="text" name="otp4" maxlength="1" 
                    class="aspect-square text-2xl text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#334b8c] focus:border-transparent otp-input"
                    onkeydown="handleOtpInput(event, 'otp4', 'otp3', 'otp5')" id="otp4">
                <input type="text" name="otp5" maxlength="1" 
                    class="aspect-square text-2xl text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#334b8c] focus:border-transparent otp-input"
                    onkeydown="handleOtpInput(event, 'otp5', 'otp4', 'otp6')" id="otp5">
                <input type="text" name="otp6" maxlength="1" 
                    class="aspect-square text-2xl text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#334b8c] focus:border-transparent otp-input"
                    onkeydown="handleOtpInput(event, 'otp6', 'otp5', '')" id="otp6">
            </div>
            <p></p>
            @if(session('error'))
            <div class="my-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
            @endif
            @error('code')
                <p class="mt-2 text-sm text-red-600 3xl:text-base text-center">{{ $message }}</p>
            @enderror
            <input type="hidden" name="code" id="full-otp">
            <div>
                <button type="submit"
                    class="group w-full mt-6 cursor-pointer relative flex justify-center py-2 px-4 3xl:py-3.5 border border-transparent text-sm 3xl:text-xl font-medium rounded-md text-white bg-[#334b8c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334b8c]">
                    Continue 
                </button>
            </div>
        </form>
        <form class="flex items-center justify-center" action="{{ route('password.resend-code') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="flex items-center justify-center mt-3">
                <p class="font-medium text-neutral-600 3xl:text-lg text-sm">
                    Didn't receive a code? 
                </p>
                <button type="submit"
                    class="ml-1 w-fit cursor-pointer flex justify-center border-none text-sm 3xl:text-lg font-medium focus:outline-none focus:ring-0 text-blue-600">
                    Resend Code
                </button>
            </div>
        </form>
        <div class="grid grid-cols-3 pt-8 pb-4 items-center w-2/3 mx-auto gap-2">
            <img src="{{ asset('images/logo-blue.png') }}" alt="company-name" class="col-span-3 pointer-events-none mx-auto">   
        </div>
    </div>
</div>

<script>

    function startCountdown() {
        let minutes = 10;
        let seconds = 0;
        const countdownElement = document.getElementById('countdown');
        
        const countdownInterval = setInterval(() => {
            if (seconds === 0) {
                if (minutes === 0) {
                    clearInterval(countdownInterval);
                    countdownElement.textContent = "Expired!";
                    countdownElement.classList.add('text-red-500');
                    return;
                }
                minutes--;
                seconds = 59;
            } else {
                seconds--;
            }
            
            const formattedMinutes = minutes.toString().padStart(2, '0');
            const formattedSeconds = seconds.toString().padStart(2, '0');
            countdownElement.textContent = `${formattedMinutes}:${formattedSeconds}`;
        }, 1000);
    }

    window.onload = startCountdown;

    function handleOtpInput(event, currentFieldId, prevFieldId, nextFieldId) {
        const currentField = document.getElementById(currentFieldId);
        
        if (event.keyCode === 8 || event.keyCode === 46) {
            currentField.value = '';
            if (prevFieldId) {
                document.getElementById(prevFieldId).focus();
            }
            event.preventDefault();
            updateFullOtp();
            return;
        }
        
        if (event.keyCode < 48 || event.keyCode > 57) {
            if (event.keyCode < 96 || event.keyCode > 105) {
                event.preventDefault();
                return;
            }
        }
        
        setTimeout(() => {
            if (currentField.value.length >= currentField.maxLength) {
                if (nextFieldId) {
                    document.getElementById(nextFieldId).focus();
                }
            }
            updateFullOtp();
        }, 10);
    }
    
    // Update the combined OTP value
    function updateFullOtp() {
        const otp1 = document.querySelector('input[name="otp1"]').value;
        const otp2 = document.querySelector('input[name="otp2"]').value;
        const otp3 = document.querySelector('input[name="otp3"]').value;
        const otp4 = document.querySelector('input[name="otp4"]').value;
        const otp5 = document.querySelector('input[name="otp5"]').value;
        const otp6 = document.querySelector('input[name="otp6"]').value;
        
        document.getElementById('full-otp').value = otp1 + otp2 + otp3 + otp4 + otp5 + otp6;
    }
    
    // Handle paste event for OTP
    document.querySelectorAll('.otp-input').forEach(input => {
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
            const inputs = document.querySelectorAll('.otp-input');
            
            if (pasteData.length === 6) {
                inputs.forEach((input, index) => {
                    input.value = pasteData[index] || '';
                });
                document.getElementById('full-otp').value = pasteData;
                inputs[5].focus();
            }
        });
    });

    document.getElementById('resend-form').addEventListener('submit', function() {
        startCountdown();
    });
</script>
@endsection