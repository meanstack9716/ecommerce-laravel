@php
    $isEdit = isset($user);
    $formAction = $isEdit ? route('user.update', $user->id) : route('user.create.submit');
    $pageTitle = $isEdit ? 'Edit user details' : 'Add New user';
    $pageDescription = $isEdit ? 'Update the user details below' : 'Fill in the details below to register a new user';
    $submitText = $isEdit ? 'Update User' : 'Add New User';
@endphp

@php
    $fields = [
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

@endphp

@extends('layouts.main')

@section('content')
<?php
    use App\Constants\Constants;
?>
<div class="p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-8 w-full space-y-6">
        <div class="text-center mb-8">
            
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-gray-600">{{ $pageDescription }}</p>
        </div>
        
        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form id="user-register-form" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        @foreach ($fields as $field)
                            <div class="">
                                <x-textfield 
                                    name="{{ $field['name'] }}" 
                                    label="{{ $field['label'] }}" 
                                    value="{{ old($field['name'], $user[$field['name']] ?? '') }}"
                                    id="{{ $field['name'] }}"
                                    :required="$field['required'] ?? false"
                                    placeholder="{{ $field['placeholder'] }}"
                                />
                            </div>
                        @endforeach
                        <div class="">
                            <label for="status" class="font-medium 3xl:text-xl 3xl:font-semibold">Status
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select name="status" id="status"
                                    class="mt-1 cursor-pointer block appearance-none w-full border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-8 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="" disabled>Select status</option>
                                    <option value="{{ Constants::STATUS_ACTIVATED }}"  {{ old('status', $user->status ?? "Constants::STATUS_ACTIVATED") ==  Constants::STATUS_ACTIVATED ? 'selected' : ''}}>{{ Constants::STATUS_ACTIVATED }}</option>
                                    <option value="{{ Constants::STATUS_DEACTIVATED }}"  {{ old('status', $user->status ?? "") ==  Constants::STATUS_DEACTIVATED ? 'selected' : ''}}>{{ Constants::STATUS_DEACTIVATED }}</option>
                                    <option value="{{ Constants::STATUS_ON_HOLD }}"  {{ old('status', $user->status ?? "") ==  Constants::STATUS_ON_HOLD ? 'selected' : ''}}>{{ Constants::STATUS_ON_HOLD }}</option>
                                </select>
                                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 rotate-90 pointer-events-none">
                                    chevron_right
                                </span>
                            </div>
                            @error('category')
                                <p class="mt-2 text-sm text-red-600 3xl:text-base">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center space-x-4">
                    <button type="submit" id="submitButton" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-[#334a8b] hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#334a8b] cursor-pointer">
                        {{ $submitText }}
                    </button>
                    @if($isEdit)
                        <a href="{{ route('user.list') }}" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('user-register-form').addEventListener('submit', function() {
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
    });
</script>

@endsection