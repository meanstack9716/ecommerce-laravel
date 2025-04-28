<form action="{{ route('seller.register.personal.submit') }}" method="POST" class="mb-0">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900">Personal Information</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="first_name" class="font-medium 3xl:text-xl 3xl:font-semibold">First Name
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="first_name" id="first_name"
                    placeholder="Enter your first name" value="{{ old('first_name') }}"
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
                    placeholder="Enter your last name" value="{{ old('last_name') }}"
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
                    placeholder="Enter your registered email address" value="{{ old('email') }}"
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
                    placeholder="Enter your mobile number" value="{{ old('phone_number') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('phone_number')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-4">
                <label for="profile" class="font-medium 3xl:text-xl 3xl:font-semibold">Select a profile picture
                </label>
                <input type="file" name="profile" id="profile"
                     value="{{ old('profile') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('profile')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <h3 class="text-xl font-medium text-gray-900">Address Details</h3>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="address1_line1" class="font-medium 3xl:text-xl 3xl:font-semibold">Street Line 1
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address1_line1" id="address1_line1"
                    placeholder="" value="{{ old('address1_line1') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address1_line1')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address1_line2" class="font-medium 3xl:text-xl 3xl:font-semibold">Street Line 2
                </label>
                <input type="text" name="address1_line2" id="address1_line2"
                    placeholder="" value="{{ old('address1_line2') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address1_line2')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address1_city" class="font-medium 3xl:text-xl 3xl:font-semibold">City
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address1_city" id="address1_city"
                    placeholder="" value="{{ old('address1_city') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address1_city')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address1_state" class="font-medium 3xl:text-xl 3xl:font-semibold">State
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address1_state" id="address1_state"
                    placeholder="" value="{{ old('address1_state') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address1_state')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address1_code" class="font-medium 3xl:text-xl 3xl:font-semibold">Pin Code
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address1_code" id="address1_code"
                    placeholder="" value="{{ old('address1_code') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address1_code')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="address1_country" class="font-medium 3xl:text-xl 3xl:font-semibold">Country
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="address1_country" id="address1_country"
                    placeholder="" value="{{ old('address1_country') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('address1_country')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Next
        </button>
    </div>
</form>