<form action="{{ route('seller.register.business.submit') }}" method="POST" class="mb-0">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900">Business Information</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-6">
                <label for="business_name" class="font-medium 3xl:text-xl 3xl:font-semibold">Business Name
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="business_name" id="business_name"
                    placeholder="Enter your business name" value="{{ old('business_name', session('client_registration_data.business.business_name')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('business_name')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="business_type" class="font-medium 3xl:text-xl 3xl:font-semibold">Business Type
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <select name="business_type" id="business_type"
                        class="mt-1 block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select business type</option>
                        @foreach (\App\Enums\BusinessType::options() as $key => $label)
                            <option value="{{ $key }}" {{ old('business_type') == $key || session('client_registration_data.business.business_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                        chevron_right
                    </span>
                </div>
                @error('business_type')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="business_email" class="font-medium 3xl:text-xl 3xl:font-semibold">Business Email
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="business_email" id="business_email"
                    placeholder="Enter your business email address" value="{{ old('business_email', session('client_registration_data.business.business_email')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('business_email')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="business_mobile" class="font-medium 3xl:text-xl 3xl:font-semibold">Business Mobile Number
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="business_mobile" id="business_mobile"
                    placeholder="Enter your mobile number" value="{{ old('business_mobile', session('client_registration_data.business.business_mobile')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('business_mobile')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="gst_num" class="font-medium 3xl:text-xl 3xl:font-semibold">GST Number
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="gst_num" id="gst_num"
                    placeholder="Enter your GST number" value="{{ old('gst_num', session('client_registration_data.business.gst_num')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('gst_num')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <h3 class="text-xl mt-4 font-medium text-gray-900">Business Address Details</h3>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

            <div class="sm:col-span-6">
                <label for="address_type" class="font-medium 3xl:text-xl 3xl:font-semibold">Address Type
                    <span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="address_type" id="address_type" value="{{ old('address_type', session('client_registration_data.businessAddress.type')) }}">
                <div class="mt-3 flex flex-wrap items-center gap-4 text-center address-type-options">
                    @foreach (\App\Enums\AddressType::options() as $key => $label)
                        <div class="min-w-20 cursor-pointer py-2 border
                            {{ old('address_type') == $key || session('client_registration_data.businessAddress.type') == $key ? 'bg-blue-500 text-white border-blue-500' : 'border-neutral-500 text-neutral-500' }} px-6 rounded-3xl font-semibold"
                            data-value="{{ $key }}">
                            {{ $label }}
                        </div>
                    @endforeach
                </div>
                @error('address_type')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address_line1" class="font-medium 3xl:text-xl 3xl:font-semibold">Street Line 1
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address_line1" id="address_line1"
                    placeholder="" value="{{ old('address_line1', session('client_registration_data.businessAddress.line1')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_line1')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address_line2" class="font-medium 3xl:text-xl 3xl:font-semibold">Street Line 2
                </label>
                <input type="text" name="address_line2" id="address_line2"
                    placeholder="" value="{{ old('address_line2', session('client_registration_data.businessAddress.line2')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_line2')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address_city" class="font-medium 3xl:text-xl 3xl:font-semibold">City
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address_city" id="address_city"
                    placeholder="" value="{{ old('address_city', session('client_registration_data.businessAddress.city')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_city')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address_state" class="font-medium 3xl:text-xl 3xl:font-semibold">State
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address_state" id="address_state"
                    placeholder="" value="{{ old('address_state', session('client_registration_data.businessAddress.state')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_state')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address_code" class="font-medium 3xl:text-xl 3xl:font-semibold">Pin Code
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address_code" id="address_code"
                    placeholder="" value="{{ old('address_code', session('client_registration_data.businessAddress.postal_code')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_code')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address_country" class="font-medium 3xl:text-xl 3xl:font-semibold">Country
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address_country" id="address_country"
                    placeholder="" value="{{ old('address_country', session('client_registration_data.businessAddress.country')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_country')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('seller.register.personal') }}'" class="inline-flex justify-center py-2 px-6 border border-[#334a8b] shadow-sm font-medium rounded-md text-[#334a8b] bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bg-blue-950 cursor-pointer">
            Back
        </button>
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Next: Identity Proof
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const options = document.querySelectorAll('.address-type-options div');
    const hiddenInput = document.getElementById('address_type');
    
    options.forEach(option => {
        option.addEventListener('click', function() {
            options.forEach(opt => {
                opt.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
                opt.classList.add('border-neutral-500', 'text-neutral-500');
            });
            
            this.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
            this.classList.remove('border-neutral-500', 'text-neutral-500');
            
            hiddenInput.value = this.getAttribute('data-value');
        });
    });
});
</script>