@extends('layouts.main')

@section('content')
<div class="p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4 xl:p-6 w-full space-y-6">
        <h2 class="text-center text-3xl font-bold tracking-wide text-gray-900">
            Seller Registration Form
        </h2>

        <div class="mt-8">
            <div class="flex w-full mx-auto justify-between mb-2 relative">
                @php
                    $steps = [
                        'personal' => 'Personal Details',
                        'business' => 'Business Details',
                        'identity' => 'Identity Proof',
                        'complete' => 'Complete'
                    ];
                    $currentStep = request()->route()->getName();
                    $currentStepName = explode('.', $currentStep)[2] ?? '';
                @endphp
        
                <div class="absolute top-4 left-0 right-0 h-1 bg-gray-200 z-10 mx-10">
                    <div class="bg-indigo-600 h-1 transition-all ml-4 duration-300" style="width: 
                        @if(str_contains($currentStep, 'personal')) 0%
                        @elseif(str_contains($currentStep, 'business')) 35%
                        @elseif(str_contains($currentStep, 'identity')) 68%
                        @else 100% @endif">
                    </div>
                </div>
        
                @foreach($steps as $route => $label)
                    @php
                        $isActive = str_contains($currentStep, $route);
                        $stepIndex = array_search($route, array_keys($steps));
                        $currentIndex = array_search($currentStepName, array_keys($steps));
                        $isCompleted = $stepIndex < $currentIndex;
                    @endphp
            
                    <div class="flex flex-col items-center z-10">
                        <!-- Step circle -->
                        <div class="w-8 h-8 rounded-full font-semibold flex items-center justify-center 
                            {{ $isActive ? 'bg-indigo-600 text-white border-2 border-indigo-600' : '' }}
                            {{ $isCompleted ? 'bg-green-500 text-white border-2 border-green-500' : '' }}
                            {{ !$isActive && !$isCompleted ? 'bg-white text-gray-600 border-2 border-gray-300' : '' }}">
                    
                            @if($isCompleted)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @else
                                {{ $stepIndex + 1 }}
                            @endif
                        </div>
                
                        <!-- Step label -->
                        <span class="font-medium mt-2 
                            {{ $isActive ? 'text-indigo-600' : '' }}
                            {{ $isCompleted ? 'text-green-500' : '' }}
                            {{ !$isActive && !$isCompleted ? 'text-gray-500' : '' }}">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Form Steps -->
        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            @if(str_contains($currentStep, 'personal'))
                @include('forms.sellerRegistration.personal-details')
            @elseif(str_contains($currentStep, 'business'))
                @include('forms.sellerRegistration.business-details')
            @elseif(str_contains($currentStep, 'identity'))
                @include('forms.sellerRegistration.identity-details')
            @else
                @include('forms.sellerRegistration.complete')
            @endif
        </div>
    </div>
</div>
@endsection
