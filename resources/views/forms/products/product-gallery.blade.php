<form action="{{ route('products.add.step4.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900 border-b pb-2 border-gray-300">Product Gallery</h3>
        <div class="pb-4">
            <x-file-upload 
                id="thumbnail-images"
                name="thumbnail"
                label="Upload Thumbnail Image for products"
                required
                helpText="Image (PNG, JPG, JPEG) upto 5MB"
            />
            @error('thumbnail')
                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <x-file-upload 
                id="product-images"
                name="images[]"
                label="Upload Images for products"
                multiple
                helpText="Images (PNG, JPG, JPEG) upto 5MB"
            />
            @error('images')
                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('products.add.step3') }}'" class="inline-flex justify-center py-2 px-6 border border-[#334a8b] shadow-sm font-medium rounded-md text-[#334a8b] bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bg-blue-950 cursor-pointer">
            Back
        </button>
        <button type="submit" class="ml-3 nline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Add Product
        </button>
    </div>
</form>
