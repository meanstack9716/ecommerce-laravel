<form action="{{ route('seller.register.complete.submit') }}" method="POST" class="mb-0">
    @csrf

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('seller.register.identity') }}'" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Back
        </button>
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Submit Registration
        </button>
    </div>
</form>