<form action="{{ route('seller.register.personal.submit') }}" method="POST" class="mb-0">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900 border-b pb-2 border-gray-300">Personal Information</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="first_name" class="font-medium 3xl:text-xl 3xl:font-semibold">First Name
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="first_name" id="first_name"
                    value="{{ old('first_name', session('client_registration_data.personal.first_name')) }}"
                    placeholder="Enter your first name"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('first_name')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="last_name" class="font-medium 3xl:text-xl 3xl:font-semibold">Last Name
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="last_name" id="last_name"
                    value="{{ old('last_name', session('client_registration_data.personal.last_name')) }}"
                    placeholder="Enter your last name"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('last_name')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="email" class="font-medium 3xl:text-xl 3xl:font-semibold">Email Address
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="email" id="email"
                    value="{{ old('email', session('client_registration_data.personal.email')) }}"
                    placeholder="Enter your registered email address"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('email')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="phone_number" class="font-medium 3xl:text-xl 3xl:font-semibold">Mobile Number
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="phone_number" id="phone_number"
                    placeholder="Enter your mobile number"
                    value="{{ old('phone_number', session('client_registration_data.personal.phone_number')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('phone_number')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <h3 class="text-xl mt-4 font-medium text-gray-900 border-b pb-2 border-gray-300">Address Details</h3>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

            <div class="sm:col-span-3">
                <label for="address_line1" class="font-medium 3xl:text-xl 3xl:font-semibold">Street Line 1
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address_line1" id="address_line1"
                    value="{{ old('address_line1', session('client_registration_data.userAddress.line1')) }}"
                    placeholder=""
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_line1')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address_line2" class="font-medium 3xl:text-xl 3xl:font-semibold">Street Line 2
                </label>
                <input type="text" name="address_line2" id="address_line2"
                    placeholder=""  value="{{ old('address_line2', session('client_registration_data.userAddress.line2')) }}"
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
                    placeholder="" value="{{ old('address_city', session('client_registration_data.userAddress.city')) }}"
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
                    placeholder="" value="{{ old('address_state', session('client_registration_data.userAddress.state')) }}"
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
                    placeholder="" value="{{ old('address_code', session('client_registration_data.userAddress.postal_code')) }}"
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
                    placeholder="" value="{{ old('address_country', session('client_registration_data.userAddress.country')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address_country')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Next
        </button>
    </div>
</form>