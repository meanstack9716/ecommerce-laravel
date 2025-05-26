@props([
    'type' => 'info',
    'message' => 'Default message',
    'duration' => 5000
])

@php
    $styles = [
        'success' => 'bg-green-100 border-green-200 text-green-800',
        'error' => 'bg-red-100 border-red-200 text-red-800',
        'warning' => 'bg-yellow-100 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-100 border-blue-200 text-blue-800',
    ];

    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
        'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5.5a7 7 0 11-6.999 7.5A7 7 0 0112 5.5z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 18h.01" />',
    ];
@endphp

<div 
    class="fixed top-4 right-4 z-50 max-w-xs w-full toast-message"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-duration="{{ $duration }}"
>
    <div class="flex items-start p-4 rounded-lg shadow-lg border {{ $styles[$type] ?? $styles['info'] }}">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 {{ $type === 'success' ? 'text-green-600' : ($type === 'error' ? 'text-red-600' : ($type === 'warning' ? 'text-yellow-600' : 'text-blue-600')) }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                {!! $icons[$type] ?? $icons['info'] !!}
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">
            <p>{{ $message }}</p>
        </div>
        <button 
            onclick="this.closest('.toast-message').classList.add('toast-closing'); setTimeout(() => this.closest('.toast-message').remove(), 300)"
            class="ml-auto -mx-1.5 -my-1.5 p-1.5 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $type === 'success' ? 'focus:ring-green-500' : ($type === 'error' ? 'focus:ring-red-500' : ($type === 'warning' ? 'focus:ring-yellow-500' : 'focus:ring-blue-500')) }}"
            aria-label="Close toast message"
        >
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<style>
    .toast-message {
        animation: toast-in 0.3s ease-out;
    }

    .toast-closing {
        animation: toast-out 0.3s ease-in forwards;
    }

    @keyframes toast-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes toast-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toasts = document.querySelectorAll('.toast-message');
        toasts.forEach(toast => {
            const duration = parseInt(toast.dataset.duration) || 5000;
            setTimeout(() => {
                toast.classList.add('toast-closing');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        });
    });
</script>