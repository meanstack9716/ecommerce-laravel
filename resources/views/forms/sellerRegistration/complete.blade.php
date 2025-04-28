<form action="{{ route('seller.register.complete.submit') }}" method="POST" class="mb-0">
    @csrf

    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900">Review Your Information</h3>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-900">Personal Details</h4>
            <div class="mt-2 grid grid-cols-1 gap-y-2 gap-x-4 sm:grid-cols-2">
                @foreach(session('client_registration_data.personal') as $key => $value)
                    <div>
                        <span class="text-sm font-medium text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-900">Personal Address Details</h4>
            <div class="mt-2 grid grid-cols-1 gap-y-2 gap-x-4 sm:grid-cols-2">
                @foreach(session('client_registration_data.userAddress') as $key => $value)
                    <div>
                        <span class="text-sm font-medium text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $value ?? "--" }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-900">Business Details</h4>
            <div class="mt-2 grid grid-cols-1 gap-y-2 gap-x-4 sm:grid-cols-2">
                @foreach(session('client_registration_data.business') as $key => $value)
                    <div>
                        <span class="text-sm font-medium text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-900">Business Address Details</h4>
            <div class="mt-2 grid grid-cols-1 gap-y-2 gap-x-4 sm:grid-cols-2">
                @foreach(session('client_registration_data.businessAddress') as $key => $value)
                    <div>
                        <span class="text-sm font-medium text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $value ?? "--" }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-900">Identity Proof</h4>
            <div class="mt-2 grid grid-cols-1 gap-y-2 gap-x-4 sm:grid-cols-2">
                @foreach(session('client_registration_data.identity') as $key => $value)
                    @if(!in_array($key, ['id_front', 'id_back']))
                        <div>
                            <span class="text-sm font-medium text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $value }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <div class="mt-4">
            <div class="flex items-center">
                <div class="flex items-center h-5">
                    <input id="terms" name="terms" type="checkbox" required
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="font-medium text-gray-700">I agree to the terms and conditions</label>
                    <p class="text-gray-500">By submitting this form, you agree to our terms of service and privacy policy.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('seller.register.identity') }}'" class="inline-flex justify-center py-2 px-6 border border-[#334a8b] shadow-sm font-medium rounded-md text-[#334a8b] bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bg-blue-950 cursor-pointer">
            Back
        </button>
        <button type="submit" class="ml-3 nline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Submit Registration
        </button>
    </div>
</form>