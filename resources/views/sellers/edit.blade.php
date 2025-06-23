@extends('layouts.main')

@section('content')
<?php
    use App\Constants\Constants;
?>
<?php
    $businessTypes = [];
    foreach (\App\Enums\BusinessType::options() as $value => $label) {
        $businessTypes[] = [
            'label' => $label,
            'value' => $value,
        ];
    };

    $statusTypes = [
        [
            'label' => Constants::STATUS_PENDING,
            'value' => Constants::STATUS_PENDING
        ],
        [
            'label' => Constants::STATUS_VERIFIED,
            'value' => Constants::STATUS_VERIFIED
        ],
        [
            'label' => Constants::STATUS_REJECTED,
            'value' => Constants::STATUS_REJECTED
        ],
    ];

    $sellerStatus = [
        [
            'label' => Constants::STATUS_PENDING,
            'value' => Constants::STATUS_PENDING
        ],
        [
            'label' => Constants::STATUS_APPROVED,
            'value' => Constants::STATUS_APPROVED
        ],
        [
            'label' => Constants::STATUS_REJECTED,
            'value' => Constants::STATUS_REJECTED
        ],
    ];

    $identificationTypes = [];
    foreach (\App\Enums\IdentificationType::options() as $value => $label) {
        $identificationTypes[] = [
            'label' => $label,
            'value' => $value,
        ];
    };
?>
@php
    $personalFields = [
        [
            'name' => 'email',
            'label' => 'Email',
            'placeholder' => 'Enter user email address',
            'type' => 'text',
            'required' => 'true',
        ],
        [
            'name' => 'first_name',
            'label' => 'First Name',
            'placeholder' => 'Enter user first name',
            'type' => 'text',
            'required' => 'true',
        ],
        [
            'name' => 'last_name',
            'label' => 'Last Name',
            'placeholder' => 'Enter user last name',
            'type' => 'text',
            'required' => 'true',
        ],
        [
            'name' => 'phone_number',
            'label' => 'Phone Number',
            'placeholder' => 'Enter user phone number',
            'type' => 'number',
            'required' => 'true',
        ],
    ];

    $businessFields = [
        [
            'name' => 'business_name',
            'label' => 'Business Name',
            'placeholder' => 'Enter user business address',
            'type' => 'text',
            'required' => 'true',
        ],
        [
            'name' => 'business_type',
            'label' => 'Business Type',
            'type' => 'select',
            'options' => $businessTypes,
            'placeholder' => 'Select business type',
            'required' => 'true',
        ],
        [
            'name' => 'business_email',
            'label' => 'Business Email',
            'type' => 'text',
            'placeholder' => 'Enter your business email address',
            'required' => 'true',
        ],
        [
            'name' => 'business_mobile',
            'label' => 'Business Mobile Number',
            'type' => 'text',
            'placeholder' => 'Enter your business mobile number',
            'required' => 'true',
        ],
        [
            'name' => 'gst_num',
            'label' => 'GST Number',
            'type' => 'text',
            'placeholder' => 'Enter your GST number',
            'required' => 'true',
        ],
    ];
    $identificationFields = [
        [
            'name' => 'pan_number',
            'label' => 'Pan Card Number',
            'placeholder' => 'Enter your pan card number',
            'type' => 'text',
            'span' => 1,
            'required' => 'true',
        ],
        [
            'name' => 'pan_verify_status',
            'label' => 'Pan card verification status',
            'type' => 'select',
            'options' => $statusTypes,
            'span' => '1',
            'placeholder' => 'Select status',
            'required' => 'true',
        ],
        [
            'name' => 'pan_front_path',
            'label' => 'Front Side of PAN Card',
            'type' => 'file',
            'span' => 2,
            'required' => 'true',
            'helperText' => 'Upload a clear photo, doc or pdf of the front side of your PAN card'
        ],
        [
            'name' => 'pan_back_path',
            'label' => 'Back Side of PAN Card',
            'type' => 'file',
            'span' => 2,
            'required' => 'true',
            'helperText' => 'Upload a clear photo, doc or pdf of the back side of your PAN card'
        ],
        [
            'name' => 'id_type',
            'label' => 'Select Identification Proof ID type',
            'type' => 'select',
            'span' => 2,
            'placeholder' => 'Select identity type',
            'required' => 'true',
            'options' => $identificationTypes,
        ],
        [
            'name' => 'id_number',
            'label' => 'ID Number',
            'placeholder' => 'Enter your identification ID number',
            'type' => 'text',
            'span' => 1,
            'required' => 'true',
        ],
        [
            'name' => 'id_verify_status',
            'label' => 'Identity Proof verification status',
            'type' => 'select',
            'options' => $statusTypes,
            'span' => '1',
            'placeholder' => 'Select status',
            'required' => 'true',
        ],
        [
            'name' => 'id_front_path',
            'label' => 'Front Side of ID proof Card',
            'type' => 'file',
            'span' => 2,
            'required' => 'true',
            'helperText' => 'Upload a clear photo, doc or pdf of the front side of your ID'
        ],
        [
            'name' => 'id_back_path',
            'label' => 'Back Side of ID proof Card',
            'type' => 'file',
            'span' => 2,
            'required' => false,
            'helperText' => 'Upload a clear photo, doc or pdf of the back side of your ID if required'
        ],
    ]

@endphp
<div class="p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Seller</h1>
            <p class="mt-2 text-sm text-gray-600">Update the seller details below
            </p>
        </div>
        
        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form action="{{ route('seller.update', $seller->id) }}" id="edit-seller-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <h3 class="text-xl font-medium text-gray-900">Personal Information</h3>
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        @foreach ($personalFields as $field)
                            <div class="">
                                <x-textfield 
                                    name="{{ $field['name'] }}" 
                                    label="{{ $field['label'] }}" 
                                    value="{{ old($field['name'], $seller['userDetails'][$field['name']] ?? '') }}"
                                    id="{{ $field['name'] }}"
                                    :required="$field['required'] ?? false"
                                    placeholder="{{ $field['placeholder'] }}"
                                />
                                <p class="{{ $field['name'] }}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                            </div>
                        @endforeach
                    </div>

                    <h3 class="text-xl font-medium text-gray-900 mt-8">Business Information</h3>
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-select-field 
                                name="status" 
                                    label="Status" 
                                    value="{{ old('status', $seller->status?->value ?? $seller['status'] ?? '') }}"
                                    id="status"
                                    :required="true"
                                    :options="$sellerStatus"
                                    placeholder="Select a status"
                                />
                                <p class="status-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                            </div>
                        @foreach ($businessFields as $field)
                            @if($field['type'] == 'text')
                            <div class="">
                                <x-textfield 
                                    name="{{ $field['name'] }}" 
                                    label="{{ $field['label'] }}" 
                                    value="{{ old($field['name'], $seller[$field['name']] ?? '') }}"
                                    id="{{ $field['name'] }}"
                                    :required="$field['required'] ?? false"
                                    placeholder="{{ $field['placeholder'] }}"
                                />
                                <p class="{{ $field['name'] }}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                            </div>
                            @elseif($field['type'] == 'select')
                            <div class="">
                                <x-select-field 
                                    name="{{ $field['name'] }}" 
                                    label="{{ $field['label'] }}" 
                                    value="{{ old($field['name'], $seller[$field['name']]?->value ?? $seller[$field['name']] ?? '') }}"
                                    id="{{ $field['name'] }}"
                                    :required="$field['required'] ?? false"
                                    :options="$field['options']"
                                    placeholder="{{ $field['placeholder'] }}"
                                />
                                <p class="{{ $field['name'] }}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <h3 class="text-xl font-medium text-gray-900 mt-8">Identification Information</h3>
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        @foreach ($identificationFields as $field)
                            @if($field['type'] == 'text')
                            <div class="">
                                <x-textfield 
                                    name="{{ $field['name'] }}" 
                                    label="{{ $field['label'] }}" 
                                    value="{{ old($field['name'], $seller['identityProof'][$field['name']] ?? '') }}"
                                    id="{{ $field['name'] }}"
                                    :required="$field['required'] ?? false"
                                    placeholder="{{ $field['placeholder'] }}"
                                />
                                <p class="{{ $field['name'] }}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                            </div>
                            @elseif($field['type'] == 'select')
                            <div class="sm:col-span-{{ $field['span'] }}">
                                <x-select-field 
                                    name="{{ $field['name'] }}" 
                                    label="{{ $field['label'] }}" 
                                    value="{{ old($field['name'], $seller['identityProof'][$field['name']]?->value ?? $seller['identityProof'][$field['name']] ?? '') }}"
                                    id="{{ $field['name'] }}"
                                    :required="$field['required'] ?? false"
                                    :options="$field['options']"
                                    placeholder="{{ $field['placeholder'] }}"
                                />
                                <p class="{{ $field['name'] }}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                            </div>
                            @elseif($field['type'] == 'file')
                            <div class="sm:col-span-{{ $field['span'] }}">
                                <label for="{{ $field['name'] }}" class="font-medium 3xl:text-xl 3xl:font-semibold">{{ $field['label'] }}
                                    @if($field['required'])
                                    <span class="text-red-600">*</span>
                                    @endif
                                </label>
                                <input type="file" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="mt-1 text-sm text-gray-500">{{ $field['helperText'] }}</p>
                            
                                @if($seller['identityProof'] && $seller['identityProof'][$field['name']])
                                    @php
                                        $filePath = 'storage/' . $seller['identityProof'][$field['name']];
                                        $fileExtension = pathinfo($seller['identityProof'][$field['name']], PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($fileExtension), ['png', 'jpg', 'jpeg', 'gif']);
                                    @endphp
        
                                    <div class="mt-3">
                                        <p class="text-sm font-medium text-gray-700">Current File:</p>
                                        @if($isImage)
                                            <img src="{{ asset($filePath) }}" 
                                                alt="{{ $field['name'] }}" 
                                                class="mt-1 h-40 w-full max-w-80 object-cover rounded-md"
                                            >
                                        @else
                                        <div class="mt-2 flex items-center gap-2 p-2 border rounded-md w-fit">
                                            <span class="material-symbols-outlined text-gray-500">
                                                @switch(strtolower($fileExtension))
                                                    @case('pdf')
                                                        picture_as_pdf
                                                        @break
                                                    @case('doc')
                                                    @case('docx')
                                                        description
                                                        @break
                                                    @default
                                                        insert_drive_file
                                                @endswitch
                                            </span>
                                            <a href="{{ asset($filePath) }}" 
                                                target="_blank" 
                                                class="text-blue-600 hover:underline"
                                                download>
                                                View {{ strtoupper($fileExtension) }} file
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                @endif

                            
                                @error($field['name'])
                                    <p class="text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                                @enderror
                                <p class="{{ $field['name'] }}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <h3 class="text-xl mt-4 font-medium text-gray-900">Address Details</h3>
                    <div id="address-container">
                    </div>
                    <div>
                        <button type="button" id="add-address-btn" class="mt-4 px-6 py-2 bg-blue-800 hover:bg-blue-900 font-medium text-white rounded-md cursor-pointer">
                            + Add Another address
                        </button>
                    </div>


                </div>

                <div class="mt-8 flex justify-center space-x-4">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        Update seller
                    </button>
                    
                    <a href="{{ route('seller.list') }}" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const addresses = @json($seller->userDetails->addresses);

    const config = {
        addressTypes : [
            @foreach(\App\Enums\AddressType::options() as $key => $label)
                { 
                    label: '{{ $label }}',
                    value: '{{ $key }}'
                },
            @endforeach
        ],

        states : [
            @foreach(\App\Enums\States::options() as $key => $label)
                { 
                    label: '{{ $label }}',
                    value: '{{ $key }}'
                },
            @endforeach
        ],

        countries : [
            { label: "India", value: "India" }
        ]
    };

    const addressFields = [
        {
            name: 'line1',
            label: 'Street Line 1',
            placeholder: 'Enter address line 1',
            required: true,
            type: 'text'
        },
        {
            name: 'line2',
            label: 'Street Line 2',
            placeholder: 'Enter address line 2',
            required: false,
            type: 'text'
        },
        {
            name: 'city',
            label: 'City',
            placeholder: 'Enter city name',
            required: true,
            type: 'text'
        },
        {
            name: 'state',
            label: 'State',
            placeholder: 'Select a state',
            required: true,
            type: 'select',
            options: config.states,
        },
        {
            name: 'postal_code',
            label: 'Pin Code',
            placeholder: 'Enter pin code',
            required: true,
            type: 'text'
        },
        {
            name: 'country',
            label: 'Country',
            placeholder: 'Select a country',
            required: true,
            type: 'select',
            options: config.countries,
        },
    ]

    const form = document.getElementById('edit-seller-form');

    const addressContainer = document.getElementById('address-container');
    const addAddressBtn = document.getElementById('add-address-btn');

    let addressCounter = 0;

    if (addresses.length) {
        addresses.forEach((addressDetails) => addAddressBlock(null, addressDetails))
    } else {
        addAddressBlock(null, null)
    }

    // Event Listeners
    addAddressBtn.addEventListener('click', addAddressBlock);
    form.addEventListener('submit', validateForm);

    // Functions
    function addAddressBlock(event, addressDetails) {
        const addressId = `address_${addressCounter++}`;

        const addressBlock = document.createElement('div');
        addressBlock.className = 'address-block mb-6 p-4 border border-gray-200 rounded-lg';
        addressBlock.dataset.addressId = addressId;

        const fieldsHTML = addressFields.map(field => {
            if (field.type === 'text') {
                return `
                    <div class="w-full">
                        <label for="addresses[${addressId}][${field.name}]" class="font-medium 3xl:text-xl 3xl:font-semibold">
                            ${field.label}
                            ${field.required ? '<span class="text-red-600">*</span>' : ''}
                        </label>
                        <input 
                            type="text" 
                            name="addresses[${addressId}][${field.name}]" 
                            id="addresses[${addressId}][${field.name}]"
                            placeholder="${field.placeholder}"
                            value="${addressDetails ? addressDetails[field.name] : ''}"
                            class="mt-1 block w-full border disabled:bg-neutral-200 border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <p class="addresses-${addressId}-${field.name}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                    </div>
                `;
            } else if (field.type === 'select') {
                return `
                    <div class="w-full">
                        <label for="addresses[${addressId}][${field.name}]" class="font-medium 3xl:text-xl 3xl:font-semibold">
                            ${field.label}
                            ${field.required ? '<span class="text-red-600">*</span>' : ''}
                        </label>
                        <div class="relative">
                            <select name="addresses[${addressId}][${field.name}]" id="addresses[${addressId}][${field.name}]"
                                class="mt-1 cursor-pointer block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                ${field.placeholder ? `<option value="">${field.placeholder }</option>` : ''}
                                ${field.options.map(option => `
                                    <option value="${option.value}" ${addressDetails && addressDetails[field.name] == option.value ? 'selected' : ''}>${option.label}</option>`
                                ).join('')}
                            </select>
                            <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                                chevron_right
                            </span>
                        </div>
                        <p class="addresses-${addressId}-${field.name}-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                    </div>
                `;
            }
            return ''; // Handle other field types here if needed
        }).join('');

        
        addressBlock.innerHTML = `
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:col-span-2">   
                    <div>
                        <label for="addresses[${addressId}][address_type]" class="font-medium 3xl:text-xl 3xl:font-semibold">Address Type
                            <span class="text-red-600">*</span>
                        </label>
                        <input type="hidden" 
                            name="addresses[${addressId}][address_type]" 
                           id="addresses[${addressId}][address_type]" 
                           value="${addressDetails ? addressDetails.type : ''}"
                        >
                        <div class="mt-3 flex flex-wrap items-center gap-4 text-center address-type-options">
                            ${config.addressTypes.map(option => `
                            <div class="min-w-20 cursor-pointer py-2 border
                                ${addressDetails && addressDetails.type == option.value ? 'bg-blue-500 text-white border-blue-500' : 'border-neutral-500 text-neutral-500'}
                                px-6 rounded-3xl font-semibold"
                                data-value="${option.value}"
                            >
                                ${option.label}
                            </div>`
                            ).join('')}
                        </div>
                        <p class="type-error mt-1 text-sm font-medium text-red-500 hidden"></p>
                    </div>
                    <div class="flex sm:justify-end h-fit shrink-0">
                        <button type="button" class="h-fit w-fit cursor-pointer font-medium remove-address-btn px-5 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">
                            Remove
                        </button>
                    </div>
                </div>
                <input 
                    type="text" 
                    name="addresses[${addressId}][address_id]" 
                    id="addresses[${addressId}][address_id]"
                    value="${addressDetails ? addressDetails.id : null}"
                    placeholder="Enter custom size" 
                    class="hidden"
                >
                ${fieldsHTML}
            </div>
        `;

        const options = addressBlock.querySelectorAll('.address-type-options div');
        const hiddenInput = addressBlock.querySelector('input[type="hidden"]');
    
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

        addressContainer.appendChild(addressBlock);
        setupAddressBlockEvents(addressBlock);
        updateRemoveButtons();
    }

    function setupAddressBlockEvents(addressBlock) {
        const removeBtn = addressBlock.querySelector('.remove-address-btn');

        removeBtn.addEventListener('click', function() {
            addressBlock.remove();
            updateRemoveButtons();
        });
    }

    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-address-btn');
        removeButtons.forEach(btn => {
            btn.style.display = document.querySelectorAll('.address-block').length > 1 ? 'block' : 'none';
        });
    }

    function formatLabel(name) {
        return name
            .replace(/_/g, ' ') // Replace underscores with spaces
            .replace(/\b\w/g, (char) => char.toUpperCase()); // Capitalize each word
    }

    function validateTextField(field, minLength = 1, maxLength = 255, errorClass) {
        const value = field.value.trim();
        console.log(errorClass)
        const errorElement = document.querySelector(`.${errorClass ? errorClass : field.name}-error`);
        
        if (!value) {
            errorElement.textContent = `${field.label || 'This field'} is required`;
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        if (value.length < minLength) {
            errorElement.textContent = `${field.label || 'This field'} must be at least ${minLength} characters`;
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        if (maxLength && value.length > maxLength) {
            errorElement.textContent = `${field.label || 'This field'} must not exceed ${maxLength} characters`;
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateEmail(field) {
        const value = field.value.trim();
        const errorElement = document.querySelector(`.${field.name}-error`);
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!value) {
            errorElement.textContent = `${field.label || 'Email'} is required`;
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        if (!emailRegex.test(value)) {
            errorElement.textContent = 'Please enter a valid email address';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validatePhoneNumber(field) {
        const value = field.value.trim();
        const errorElement = document.querySelector(`.${field.name}-error`);
        const phoneRegex = /^[0-9]{10}$/;
        
        if (!value) {
            errorElement.textContent = 'Phone number is required';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        if (!phoneRegex.test(value)) {
            errorElement.textContent = 'Phone number must be 10 digits';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateSelectField(field, errorClass) {
        const value = field.value;
        const errorElement = document.querySelector(`.${errorClass ? errorClass : field.name}-error`);
        
        if (!value) {
            errorElement.textContent = `${field.label || 'This field'} is required`;
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateGSTNumber(field) {
        const value = field.value.trim();
        const errorElement = document.querySelector(`.${field.name}-error`);
        const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
        
        if (!value) {
            errorElement.textContent = 'GST number is required';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        if (!gstRegex.test(value)) {
            errorElement.textContent = 'Invalid GST number format';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validatePANNumber(field) {
        const value = field.value.trim();
        const errorElement = document.querySelector(`.${field.name}-error`);
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        
        if (!value) {
            errorElement.textContent = 'PAN number is required';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        if (!panRegex.test(value)) {
            errorElement.textContent = 'Invalid PAN number format (e.g., AAAAA9999A)';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateFileUpload(field, isRequired = true) {
        const fileInput = document.getElementById(field.name);
        const errorElement = document.querySelector(`.${field.name}-error`);
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        // Skip validation if file exists in database and no new file is uploaded
        const existingFile = field.existingFile;
        if (existingFile && !fileInput.files.length) {
            errorElement.classList.add('hidden');
            return true;
        }
        
        if (isRequired && !fileInput.files.length) {
            errorElement.textContent = `${field.label} is required`;
            errorElement.classList.remove('hidden');
            return false;
        }
        
        if (fileInput.files.length) {
            const file = fileInput.files[0];
            
            if (!allowedTypes.includes(file.type)) {
                errorElement.textContent = 'File must be JPEG, PNG, JPG, DOC, DOCX or PDF';
                errorElement.classList.remove('hidden');
                return false;
            }
            
            if (file.size > maxSize) {
                errorElement.textContent = 'File size must not exceed 5MB';
                errorElement.classList.remove('hidden');
                return false;
            }
        }
        
        errorElement.classList.add('hidden');
        return true;
    }

    function validatePostalCode(field, errorClass) {
        const value = field.value.trim();
        const errorElement = document.querySelector(`.${errorClass ? errorClass : field.name}-error`);
        const postalRegex = /^[1-9][0-9]{5}$/;
        
        if (!value) {
            errorElement.textContent = 'Postal code is required';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        if (!postalRegex.test(value)) {
            errorElement.textContent = 'Invalid postal code (must be 6 digits, starting with 1-9)';
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            return false;
        }
        
        errorElement.classList.add('hidden');
        field.classList.remove('border-red-500');
        return true;
    }

    function validateAddresses() {
        const addressBlocks = document.querySelectorAll('.address-block');
        let blockIsValid = true;
        let isValid = true;
        
        if (addressBlocks.length === 0) {
            const errorElement = document.createElement('p');
            errorElement.className = 'text-red-500 text-sm font-medium';
            errorElement.textContent = 'At least one address is required';
            addressContainer.insertAdjacentElement('afterend', errorElement);
            return false;
        }
        
        addressBlocks.forEach(block => {
            const addressId = block.dataset.addressId;

            const addressTypeInput = block.querySelector(`input[name="addresses[${addressId}][address_type]"]`);
            const typeErrorElement = block.querySelector('.type-error');
        
            if (!addressTypeInput || !addressTypeInput.value) {
                typeErrorElement.textContent = 'Address type is required';
                typeErrorElement.classList.remove('hidden');
                blockIsValid = false;
            } else {
                typeErrorElement.classList.add('hidden');
            }
            
            
            // Validate address fields
            const requiredFields = [
                { name: `line1`, label: 'Address Line 1', minLength: 3 },
                { name: `city`, label: 'City', minLength: 3 },
                { name: `state`, label: 'State' },
                { name: `postal_code`, label: 'Postal Code' },
                { name: `country`, label: 'Country' }
            ];

            requiredFields.forEach(field => {
                const input = block.querySelector(`[name="addresses[${addressId}][${field.name}]"]`);
                if (input) {
                    const dataset = `addresses-${addressId}-${field.name}`
                    input.label = field.label;
                    let fieldIsValid = true;
                
                    if (field.name.includes('postal_code')) {
                        fieldIsValid = validatePostalCode(input, dataset );
                    } else if (input.tagName === 'SELECT') {
                        fieldIsValid = validateSelectField(input, dataset);
                    } else {
                        fieldIsValid = validateTextField(input, field.minLength || 1, null, dataset);
                    }
                
                    if (!fieldIsValid) {
                        blockIsValid = false;
                    }
                }
            });

            if (!blockIsValid) {
                isValid = false;
            }
        });
        
        return isValid;
    }


    function validateForm(e) {
        e.preventDefault();
        let isValid = true;
        
        // Clear all errors first
        document.querySelectorAll('.text-red-500').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        
        // Validate personal information
        const personalFields = [
            { name: 'first_name', minLength: 3 },
            { name: 'last_name', minLength: 3 },
            { name: 'email', type: 'email' },
            { name: 'phone_number', type: 'phone' }
        ];
        
        personalFields.forEach(field => {
            const input = document.getElementById(field.name);
            input.label = field.label || formatLabel(field.name) || 'This field';
            
            if (field.type === 'email') {
                if (!validateEmail(input)) isValid = false;
            } else if (field.type === 'phone') {
                if (!validatePhoneNumber(input)) isValid = false;
            } else {
                if (!validateTextField(input, field.minLength)) isValid = false;
            }
        });
        
        // Validate business information
        const businessFields = [
            { name: 'status', type: 'select' },
            { name: 'business_name', minLength: 4 },
            { name: 'business_email', type: 'email' },
            { name: 'business_mobile', type: 'phone' },
            { name: 'business_type', type: 'select' },
            { name: 'gst_num', type: 'gst' }
        ];
        
        businessFields.forEach(field => {
            const input = document.getElementById(field.name);
            if (input) {
                input.label = field.label || formatLabel(field.name) || 'This field';
                
                if (field.type === 'email') {
                    if (!validateEmail(input)) isValid = false;
                } else if (field.type === 'phone') {
                    if (!validatePhoneNumber(input)) isValid = false;
                } else if (field.type === 'select') {
                    if (!validateSelectField(input)) isValid = false;
                } else if (field.type === 'gst') {
                    if (!validateGSTNumber(input)) isValid = false;
                } else {
                    if (!validateTextField(input, field.minLength)) isValid = false;
                }
            }
        });
        
        // // Validate identification information
        const identificationFields = [
            { name: 'pan_number', type: 'pan' },
            { name: 'pan_verify_status', type: 'select' },
            { name: 'pan_front_path', type: 'file', existingFile: @json($seller['identityProof']['pan_front_path'] ?? null) },
            { name: 'pan_back_path', type: 'file', existingFile: @json($seller['identityProof']['pan_back_path'] ?? null) },
            { name: 'id_type', type: 'select' },
            { name: 'id_number' },
            { name: 'id_verify_status', type: 'select' },
            { name: 'id_front_path', type: 'file', existingFile: @json($seller['identityProof']['id_front_path'] ?? null) },
            { name: 'id_back_path', type: 'file', required: false, existingFile: @json($seller['identityProof']['id_back_path'] ?? null) }
        ];
        
        identificationFields.forEach(field => {
            const input = document.getElementById(field.name);
            if (input) {
                input.label = field.label || formatLabel(field.name) || 'This field';
                
                if (field.type === 'select') {
                    if (!validateSelectField(input)) isValid = false;
                } else if (field.type === 'pan') {
                    if (!validatePANNumber(input)) isValid = false;
                } else if (field.type === 'file') {
                    if (!validateFileUpload(field, field.required !== false)) isValid = false;
                } else if (field.name === 'id_number') {
                    // Special validation for ID number based on ID type
                    const idType = document.getElementById('id_type').value;
                    const idNumber = input.value.trim();
                    const errorElement = document.querySelector(`.${field.name}-error`);
                    
                    if (!idNumber) {
                        errorElement.textContent = 'ID number is required';
                        errorElement.classList.remove('hidden');
                        input.classList.add('border-red-500');
                        isValid = false;
                    } else if (idType === 'Aadhar Card' && !/^\d{12}$/.test(idNumber)) {
                        errorElement.textContent = 'Aadhar number must be 12 digits';
                        errorElement.classList.remove('hidden');
                        input.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        errorElement.classList.add('hidden');
                        input.classList.remove('border-red-500');
                    }
                }
            }
        });
        
        // // Validate addresses
        if (!validateAddresses()) isValid = false;
        
        // Submit form if all validations pass
        if (isValid) {
            form.submit();
        } else {
            // Scroll to the first error
            const firstError = document.querySelector('.text-red-500:not(.hidden)');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }
});
</script>

@endsection