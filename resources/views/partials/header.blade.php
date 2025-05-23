@auth
    <header class="h-18 shadow-md 3xl:h-20 fixed top-0 left-0 right-0 z-100 flex items-center justify-between bg-[#334a8b] px-4 lg:px-6 xl:px-8 3xl:px-12">
        <div class="flex gap-6 items-center">
            <div class="lg:hidden cursor-pointer" id="sidebar-toggle">
                <i class="fa-solid fa-bars text-xl text-neutral-200" id="menu-icon"></i>
            </div>
            <!-- <div class="hidden lg:block cursor-pointer" id="menu-toggle">
                <i class="fa-solid fa-bars text-xl text-neutral-200" id="menu-icon"></i>
            </div> -->
            <img src="{{ asset('images/logo.png') }}" alt="company-name" class="h-10  pointer-events-none hidden lg:block">
            <img src="{{ asset('images/favicon-white.png') }}" alt="Logo" class=" pointer-events-none h-12 lg:hidden">
        </div>
        <div class="flex items-center gap-6">

            <div class="relative">
                <div id="notification-toggle" onclick="toggleFullscreen()" class="h-9 w-9 3xl:h-10 3xl:w-10 text-center flex items-center justify-center bg-neutral-300 rounded-full cursor-pointer relative">
                    <i class="fa-regular fa-bell text-lg text-gray-700"></i>
                    <!-- <div class="absolute -top-1 -right-1.5">
                        <span class="relative flex size-4">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-500 opacity-75"></span>
                            <span class="relative flex justify-center size-4 text-[10px] font-medium text-white rounded-full bg-sky-600">1</span>
                        </span>
                    </div> -->
                </div>
                <div 
                    id="notification-dropdown" 
                    class="opacity-0 scale-95 hidden transition-all duration-200 ease-out absolute right-0 mt-3 w-68 sm:w-80 bg-white rounded-lg shadow-lg z-50 p-4 space-y-5"
                >
                    <div class="pb-3 border-b border-slate-200 font-bold text-xl text-gray-800">
                        Notifications
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.99 14.993 6-6m6 3.001c0 1.268-.63 2.39-1.593 3.069a3.746 3.746 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043 3.745 3.745 0 0 1-3.068 1.593c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-3.296-1.043 3.746 3.746 0 0 1-1.043-3.297 3.746 3.746 0 0 1-1.593-3.068c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 0 1 1.043-3.297 3.745 3.745 0 0 1 3.296-1.042 3.745 3.745 0 0 1 3.068-1.594c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.297 3.746 3.746 0 0 1 1.593 3.068ZM9.74 9.743h.008v.007H9.74v-.007Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 4.5h.008v.008h-.008v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold m-0 leading-tight">Discount Available</p>
                            <p class="text-[#515151] text-xs">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="bg-red-100 rounded-full w-12 h-12 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-truck text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold m-0 leading-tight">Order Pending</p>
                            <p class="text-[#515151] text-xs">Lorem Ipsum is simply dummy text</p>
                        </div>
                    </div>

                    <button class="w-full border-2 border-blue-500 rounded-xl py-2 font-medium bg-blue-500 text-white hover:bg-white hover:text-black cursor-pointer"> View All</button>
                </div>
            </div>

            <div class="relative">
                <div id="profile-toggle" class="flex items-center gap-2 cursor-pointer relative">
                    <img 
                        src="{{ $user->profile_path ? asset('storage/' . $user->profile_path) : asset('images/user.jpeg') }}"
                        alt="User Avatar" 
                        class="h-10 w-10 3xl:h-12 3xl:w-12 rounded-full object-cover"
                    />
                    <div class="flex flex-col">
                        @if ($user && ($user->first_name || $user->last_name))
                            <p class="text-white font-medium text-xs sm:text-sm m-0">{{ $user->first_name ?? "" }}{{" "}}{{ $user->last_name ?? "" }}</p>
                        @else 
                            <p class="text-white font-medium text-xs sm:text-sm m-0">{{ $user->email }}</p>
                        @endif 
                        @if ($user->is_admin) 
                            <p class="text-white font-medium text-xs sm:text-xs m-0">Admin</p>
                        @else
                            <p class="text-[#F1F1F1] text-xs sm:text-xs m-0">{{ $user->role->name }}</p>
                        @endif
                    </div>
                </div>
                <div 
                    id="profile-dropdown" 
                    class="opacity-0 hidden scale-95 transition-all duration-200 ease-out absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg z-50 p-4 space-y-4"
                >
                    <a class="text-gray-800 font-medium flex items-center gap-2 hover:text-sky-600 cursor-pointer group">
                        <i class="fa-regular fa-user text-gray-400 group-hover:text-sky-600"></i>
                        <span>Profile</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-fit text-gray-800 font-medium flex items-center text-center gap-2 hover:text-sky-600 cursor-pointer group">
                            <i class="fa-solid fa-right-from-bracket text-gray-400 group-hover:text-sky-600"></i>
                            <p class="m-0">Logout</p>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="-translate-x-full lg:hidden w-screen h-screen absolute top-0 left-0 bg-zinc-300 backdrop-blur-lg shadow-lg transition-transform duration-300 pt-18 z-50" id="mobile-sidebar">
        <div class="w-72">
            <x-side-bar />
        </div>
    </div>
    @endauth

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profileToggle = document.getElementById('profile-toggle');
        const profileDropdown = document.getElementById('profile-dropdown');

        const notificationToggle = document.getElementById('notification-toggle');
        const notificationDropdown = document.getElementById('notification-dropdown');

        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('mobile-sidebar');
        const menuIcon = document.getElementById('menu-icon');

        const toggleDropdown = (dropdown) => {
            if (dropdown.classList.contains('opacity-0')) {
                dropdown.classList.remove('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
                dropdown.classList.add('opacity-100', 'scale-100');
            } else {
                dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
                dropdown.classList.remove('opacity-100', 'scale-100');
            }
        };

        profileToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            notificationDropdown?.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
            notificationDropdown?.classList.remove('opacity-100', 'scale-100');
            toggleDropdown(profileDropdown);
        });

        profileDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        notificationToggle?.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown?.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
            profileDropdown?.classList.remove('opacity-100', 'scale-100');
            toggleDropdown(notificationDropdown);
        });

        notificationDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        document.addEventListener('click', function () {
            profileDropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
            profileDropdown.classList.remove('opacity-100', 'scale-100');

            notificationDropdown?.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'hidden');
            notificationDropdown?.classList.remove('opacity-100', 'scale-100');
        });

        sidebarToggle.addEventListener('click', function () {
            const isOpen = !sidebar.classList.contains('-translate-x-full');

            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                menuIcon.classList.replace('fa-xmark', 'fa-bars');
            } else {
                sidebar.classList.remove('-translate-x-full');
                menuIcon.classList.replace('fa-bars', 'fa-xmark');
            }
        });
    });
</script>