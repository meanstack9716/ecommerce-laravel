<form action="{{ route('seller.register.identity.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900">Identity Verification</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-4">
                <label for="pan_number" class="font-medium 3xl:text-xl 3xl:font-semibold">PAN Card number
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="pan_number" id="pan_number"
                    placeholder="Enter your PAN number" value="{{ old('pan_number', session('client_registration_data.identity.pan_number')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('pan_number')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="pan_front" class="font-medium 3xl:text-xl 3xl:font-semibold">Front Side of PAN Card
                    <span class="text-red-600">*</span>
                </label>
                <input type="file" name="pan_front" id="pan_front"
                    value="{{ old('pan_front') }}"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-sm text-gray-500">Upload a clear photo, doc or pdf of the front side of your PAN card</p>

                @error('pan_front')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="pan_back" class="font-medium 3xl:text-xl 3xl:font-semibold">Back Side of PAN Card
                    <span class="text-red-600">*</span>
                </label>
                <input type="file" name="pan_back" id="pan_back"
                    value="{{ old('pan_back') }}"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-sm text-gray-500">Upload a clear photo, doc or pdf of the back side of your PAN card.</p>
                @error('pan_back')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-4">
                <label for="id_type" class="font-medium 3xl:text-xl 3xl:font-semibold">Select Identification Proof ID type
                    <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <select name="id_type" id="id_type"
                        class="mt-1 block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select ID type</option>
                        @foreach (\App\Enums\IdentificationType::options() as $key => $label)
                            <option value="{{ $key }}" {{ old('id_type') == $key || session('client_registration_data.identity.id_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                        chevron_right
                    </span>
                </div>
                @error('id_type')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-4">
                <label for="id_number" class="font-medium 3xl:text-xl 3xl:font-semibold">ID Number
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="id_number" id="id_number"
                    value="{{ old('id_number', session('client_registration_data.identity.id_number')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('id_number')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="id_front" class="font-medium 3xl:text-xl 3xl:font-semibold">Front Side of ID
                    <span class="text-red-600">*</span>
                </label>                
                <input type="file" name="id_front" id="id_front"
                    value="{{ old('id_front') }}"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-sm text-gray-500">Upload a clear photo, doc or pdf of the front side of your ID</p>
                @error('id_front')
                    <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="id_back" class="font-medium 3xl:text-xl 3xl:font-semibold">Back Side of ID (if applicable)</label>
                <input type="file" name="id_back" id="id_back"
                    value="{{ old('id_back') }}"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-sm text-gray-500">Upload a clear photo, doc or pdf of the back side of your ID if required</p>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('seller.register.business') }}'" class="inline-flex justify-center py-2 px-6 border border-[#334a8b] shadow-sm font-medium rounded-md text-[#334a8b] bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bg-blue-950 cursor-pointer">
            Back
        </button>
        <button type="submit" class="ml-3 nline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Complete Registration
        </button>
    </div>
</form>