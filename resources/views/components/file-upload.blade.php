@props([
    'id',
    'label' => '',
    'name' => '',
    'accept' => 'image/*',
    'multiple' => false,
    'helpText' => '',
    'required' => false,
    'labelClasses' => 'block text-sm font-medium text-gray-700 mb-2',
    'existingFiles' => null // Add support for pre-existing files
])

<div class="flex flex-col gap-2">
    @if($label ?? null)
        <label for="{{ $id }}" class="font-medium 3xl:text-xl 3xl:font-semibold">
            {{ $label }}
            @if($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif
        
    <div class="mt-1 flex justify-center px-6 pt-5 pb-2 border-2 border-gray-300 border-dashed rounded-md cursor-pointer"
        id="{{ $id }}-dropzone">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"
                aria-hidden="true">
                <path
                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-gray-600 justify-center">
                <label for="{{ $id }}"
                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-800">
                    <span>Upload files</span>
                </label>

                <input type="file" name="{{ $name }}" id="{{ $id }}" @if($multiple) multiple @endif
                    class="upload_file_input hidden" onchange="handleImageUpload(this)">
                <p class="pl-1">or drag and drop</p>
            </div>
            @if($helpText)
                <p class="text-xs text-gray-500">{{ $helpText }}</p>
            @endif
        </div>
    </div>

    <div id="{{ $id }}-preview" class="pt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <!-- Existing files preview -->
        @if($existingFiles)
            @foreach($existingFiles as $index => $file)
                <div class="relative group mt-4">
                    <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                        <img src="{{ is_string($file) ? $file : $file->temporaryUrl() }}" 
                            alt="Preview {{ $index }}" 
                            class="w-full h-full object-cover transition-opacity group-hover:opacity-75">
                    </div>
                    <div class="mt-1 flex justify-between text-xs text-gray-500 truncate">
                        <span>{{ is_string($file) ? basename($file) : $file->getClientOriginalName() }}</span>
                        @if(!is_string($file))
                            <span>{{ formatFileSize($file->getSize()) }}</span>
                        @endif
                    </div>
                    <button type="button" class="absolute -top-2 -right-2 ml-2 cursor-pointer" onclick="removeImage(this, '{{ $id }}', {{ $index }}, true)">
                        <span class="material-symbols-outlined text-red-600 bg-red-100 rounded-full">
                            cancel
                        </span>
                    </button>
                </div>
            @endforeach
        @endif
    </div>
</div>

@once
    @push('scripts')
        <script>
            function handleImageUpload(input) {
                const previewContainer = document.getElementById(input.id + '-preview');
                
                // Only clear non-existing file previews
                const existingPreviews = previewContainer.querySelectorAll('[data-existing]');
                previewContainer.innerHTML = '';
                existingPreviews.forEach(preview => previewContainer.appendChild(preview));

                if (input.files && input.files.length > 0) {
                    Array.from(input.files).forEach((file, index) => {
                        // Skip if file is already in existingFiles
                        if (document.querySelector(`[data-file-name="${file.name}"]`)) return;

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'relative group';

                            previewDiv.innerHTML = `
                                <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                                    <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover transition-opacity group-hover:opacity-75">
                                </div>
                                <div class="mt-1 flex justify-between text-xs text-gray-500 truncate">
                                    <span>${file.name}</span>
                                    <span>${formatFileSize(file.size)}</span>
                                </div>
                                <button type="button" class="absolute -top-2 -right-2 ml-2 cursor-pointer" onclick="removeImage(this, '${input.id}', ${index})">
                                    <span class="material-symbols-outlined text-red-600 bg-red-100 rounded-full">
                                        cancel
                                    </span>
                                </button>
                            `;
                            previewContainer.appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            function removeImage(button, inputId, index, isExisting = false) {
                const input = document.getElementById(inputId);
                
                if (isExisting) {
                    // Handle existing file removal
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `remove_${input.name}[${index}]`;
                    hiddenInput.value = '1';
                    button.closest('div').remove();
                    document.getElementById(inputId + '-preview').appendChild(hiddenInput);
                } else {
                    // Handle new file removal
                    const files = Array.from(input.files);
                    files.splice(index, 1);

                    const dataTransfer = new DataTransfer();
                    files.forEach(file => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;

                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
            }

            function formatFileSize(bytes) {
                if (typeof bytes !== 'number') return '';
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }

            // Initialize drag and drop for all dropzones
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[id$="-dropzone"]').forEach(dropzone => {
                    const inputId = dropzone.id.replace('-dropzone', '');
                    const fileInput = document.getElementById(inputId);

                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropzone.addEventListener(eventName, preventDefaults, false);
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    dropzone.addEventListener('click', () => {
                        if (fileInput) {
                            fileInput.click();
                        }
                    });

                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropzone.addEventListener(eventName, highlight, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        dropzone.addEventListener(eventName, unhighlight, false);
                    });

                    function highlight() {
                        dropzone.classList.add('border-indigo-500');
                        dropzone.classList.remove('border-gray-300');
                    }

                    function unhighlight() {
                        dropzone.classList.remove('border-indigo-500');
                        dropzone.classList.add('border-gray-300');
                    }

                    dropzone.addEventListener('drop', handleDrop, false);

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;

                        if (files.length) {
                            const dataTransfer = new DataTransfer();

                            if (fileInput.multiple && fileInput.files) {
                                Array.from(fileInput.files).forEach(file => {
                                    dataTransfer.items.add(file);
                                });
                            }

                            Array.from(files).forEach(file => {
                                // Check if file type is accepted
                                if (fileInput.accept && !fileInput.accept.split(',').some(accept => {
                                    return new RegExp(accept.trim().replace('*', '.*').replace('.', '\\.')).test(file.type);
                                })) {
                                    return; // Skip invalid file types
                                }
                                dataTransfer.items.add(file);
                            });

                            if (dataTransfer.files.length > 0) {
                                fileInput.files = dataTransfer.files;
                                const event = new Event('change');
                                fileInput.dispatchEvent(event);
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endonce