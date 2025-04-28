<form action="{{ route('seller.register.identity.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data">
    @csrf
    <div class="space-y-6">
        <h3 class="text-lg font-medium text-gray-900">Identity Verification</h3>
        
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-6">
                <label for="id_type" class="block text-sm font-medium text-gray-700">ID Type</label>
                <select name="id_type" id="id_type" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select ID type</option>
                    <option value="passport">Passport</option>
                    <option value="driving_license">Driving License</option>
                    <option value="national_id">National ID</option>
                </select>
            </div>

            <div class="sm:col-span-6">
                <label for="id_number" class="block text-sm font-medium text-gray-700">ID Number</label>
                <input type="text" name="id_number" id="id_number" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="sm:col-span-6">
                <label for="id_front" class="block text-sm font-medium text-gray-700">Front Side of ID</label>
                <input type="file" name="id_front" id="id_front" required
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-sm text-gray-500">Upload a clear photo of the front side of your ID</p>
            </div>

            <div class="sm:col-span-6">
                <label for="id_back" class="block text-sm font-medium text-gray-700">Back Side of ID (if applicable)</label>
                <input type="file" name="id_back" id="id_back"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-sm text-gray-500">Upload a clear photo of the back side of your ID if required</p>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('seller.register.business') }}'" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Back
        </button>
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Complete Registration
        </button>
    </div>
</form>