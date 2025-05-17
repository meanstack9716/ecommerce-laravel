<form action="{{ route('products.add.step4.submit') }}" method="POST" class="mb-0" enctype="multipart/form-data" id="product-gallery-form">
    @csrf
    <div class="space-y-6">
        <h3 class="text-xl font-medium text-gray-900">Product Gallery</h3>
        <div class="pb-4">
            <x-file-upload 
                id="thumbnail-image"
                name="thumbnail"
                label="Upload Thumbnail Image for products"
                required
                helpText="Image (PNG, JPG, JPEG) up to 5MB"
                accept="image/png,image/jpeg,image/jpg"
            />
            @error('thumbnail')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p id="thumbnail-error" class="mt-2 text-sm text-red-600 hidden"></p>
        </div>

        @forelse ($selectedColors as $color)
            <div class="color-image-group" data-color="{{ $color }}">
                <x-file-upload 
                    id="product-images-{{ $color }}"
                    name="images[{{ $color }}][]"
                    label="Upload Images for {{ $color }} color"
                    multiple
                    helpText="Multiple images (PNG, JPG, JPEG) up to 5MB each"
                    accept="image/png,image/jpeg,image/jpg"
                />
                @error('images.'.$color)
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.'.$color.'.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="images-{{ $color }}-error" class="mt-2 text-sm text-red-600 hidden"></p>
            </div>
        @empty
            <div class="color-image-group" data-color="default">
                <x-file-upload 
                    id="product-images-default"
                    name="images[default][]"
                    label="Upload Product Images"
                    multiple
                    helpText="Multiple images (PNG, JPG, JPEG) up to 5MB each"
                    accept="image/png,image/jpeg,image/jpg"
                />
                @error('images.default')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.default.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="images-default-error" class="mt-2 text-sm text-red-600 hidden"></p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" onclick="window.location.href='{{ route('products.add.step3') }}'" class="inline-flex justify-center py-2 px-6 border border-[#334a8b] shadow-sm font-medium rounded-md text-[#334a8b] bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bg-blue-950 cursor-pointer">
            Back
        </button>
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
            Add Product
        </button>
    </div>
</form>

<script>
document.getElementById('product-gallery-form').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Clear previous error messages
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    
    // Check thumbnail
    const thumbnailInput = document.getElementById('thumbnail-image');
    const thumbnailError = document.getElementById('thumbnail-error');
    if (!thumbnailInput.files || thumbnailInput.files.length === 0) {
        isValid = false;
        thumbnailError.textContent = 'Thumbnail image is required';
        thumbnailError.classList.remove('hidden');
    }
    
    // Check color images
    const colorGroups = document.querySelectorAll('.color-image-group');
    colorGroups.forEach(group => {
        const color = group.dataset.color;
        const fileInput = group.querySelector('input[type="file"]');
        const errorElement = document.getElementById(`images-${color}-error`);
        
        if (!fileInput.files || fileInput.files.length === 0) {
            isValid = false;
            errorElement.textContent = `Please upload at least one image for ${color === 'default' ? 'product' : color + ' color'}`;
            errorElement.classList.remove('hidden');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        
        // Scroll to first error
        const firstErrorElement = document.querySelector('[id$="-error"]:not(.hidden)');
        if (firstErrorElement) {
            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});
</script>