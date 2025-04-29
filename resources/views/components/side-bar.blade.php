<div class="h-[calc(100vh-72px)] 3xl:h-[calc(100vh-80px)] bg-white shadow-md overflow-y-auto py-8 px-3 space-y-5" id="sidebar-wrap">
    <div class="flex flex-col gap-2 px-3">
        <p class="text-gray-400 font-semibold">Main Home</p>

        <!-- Dashborad -->
        <a href="{{ route('dashboard') }}" class="flex items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-3 transition-all duration-300 rounded-lg {{ Request::is('*dashboard*') ? 'text-blue-500 bg-blue-100' : '' }}">
            <span class="material-symbols-outlined">
                dashboard
            </span>
            <span class="font-medium">Dashboard</span>
        </a>
    </div>

    <div class="flex flex-col gap-2 px-3">
        <p class="text-gray-400 font-semibold">All Pages</p>

        <!-- User -->
        <div class="group">
            <div class="flex justify-between items-center cursor-pointer hover:text-blue-500 hover:bg-blue-100 px-3 transition-all duration-300 group-[.open]:bg-blue-100 group-[.open]:text-blue-500 rounded-md py-2 {{ Request::is('users*') ? 'text-blue-500 bg-blue-100' : '' }}" 
                onclick="toggleMenu(this)"
            >
                <div class="flex items-center text-center gap-3">
                    <span class="material-symbols-outlined">
                        person
                    </span>
                    <span class="font-medium tracking-wide">User</span>
                </div>
                <i class="fa-solid fa-chevron-down text-sm mr-2"></i>
            </div>

            <ul class="px-2 hidden group-[.open]:block space-y-2 my-3">
                <li>
                    <a href="{{ route('user.list') }}" class="flex text-sm items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('users/list*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            groups
                        </span>
                        <span class="font-medium">All user</span>
                    </a>
                </li>
                <li>
                    <a href="" class="flex items-center text-center gap-3 text-sm hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('*users/register*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            person_add
                        </span>
                        <span class="font-medium">Add New User</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="group">
            <div class="flex justify-between items-center cursor-pointer hover:text-blue-500 hover:bg-blue-100 px-3 transition-all duration-300 group-[.open]:bg-blue-100 group-[.open]:text-blue-500 rounded-md py-2 {{ Request::is('sellers*') ? 'text-blue-500 bg-blue-100' : '' }}" 
                onclick="toggleMenu(this)"
            >
                <div class="flex items-center text-center gap-3">
                    <span class="material-symbols-outlined">
                        supervisor_account
                    </span>
                    <span class="font-medium tracking-wide">Sellers</span>
                </div>
                <i class="fa-solid fa-chevron-down text-sm mr-2"></i>
            </div>

            <ul class="px-2 hidden group-[.open]:block space-y-2 my-3">
                <li>
                    <a href="{{ route('seller.list') }}" class="flex text-sm items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('sellers/list*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            groups
                        </span>
                        <span class="font-medium">List All sellers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.register.personal') }}" class="flex text-sm items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('*sellers/register*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            person_add
                        </span>
                        <span class="font-medium">Add New Seller</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Categories -->
        <div class="group">
            <div class="flex justify-between items-center cursor-pointer hover:text-blue-500 hover:bg-blue-100 px-3 transition-all duration-300 group-[.open]:bg-blue-100 group-[.open]:text-blue-500 rounded-md py-2 {{ Request::is('category*') ? 'text-blue-500 bg-blue-100' : '' }}" 
                onclick="toggleMenu(this)"
            >
                <div class="flex items-center text-center gap-3">
                    <span class="material-symbols-outlined">
                        stacks
                    </span>
                    <span class="font-medium tracking-wide">Categories</span>
                </div>
                <i class="fa-solid fa-chevron-down text-sm mr-2"></i>
            </div>

            <ul class="px-2 hidden group-[.open]:block space-y-2 my-3">
                <li>
                    <a href="{{ route('category.list') }}" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('category/list*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            category
                        </span>
                        <span class="font-medium">Categories List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('category.add') }}" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('category/add*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            add_circle
                        </span>
                        <span class="font-medium">Add New Category</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sub-category.list') }}" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('category/sub/list*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            inventory
                        </span>
                        <span class="font-medium">Sub Categories List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sub-category.add') }}" class="flex text-sm items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('category/sub/add*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            add_box
                        </span>
                        <span class="font-medium">Add Sub-Category</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product-type.list') }}" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('category/product-type/list*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            format_list_bulleted
                        </span>
                        <span class="font-medium">List Product Types</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product-type.add') }}" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('category/product-type/add*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            add_circle
                        </span>
                        <span class="font-medium">Add New Product Type</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Order -->
        <a href="" class="flex items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-3 transition-all duration-300 rounded-lg {{ Request::is('*orders*') ? 'text-blue-500 bg-blue-100' : '' }}">
            <span class="material-symbols-outlined">
                receipt_long
            </span>
            <span class="font-medium">Orders</span>
        </a>
    </div>

    <div class="flex flex-col gap-2 px-3">
        <p class="text-gray-400 font-semibold">Settings</p>

        <!-- User -->
        <div class="group">
            <div class="flex justify-between items-center cursor-pointer hover:text-blue-500 hover:bg-blue-100 px-3 transition-all duration-300 group-[.open]:bg-blue-100 group-[.open]:text-blue-500 rounded-md py-2 {{ Request::is('*faq*') ? 'text-blue-500 bg-blue-100' : '' }}" 
                onclick="toggleMenu(this)"
            >
                <div class="flex items-center text-center gap-3">
                    <span class="material-symbols-outlined">
                        help
                    </span>
                    <span class="font-medium tracking-wide">FAQs</span>
                </div>
                <i class="fa-solid fa-chevron-down text-sm mr-2"></i>
            </div>

            <ul class="px-2 hidden group-[.open]:block space-y-2 my-3">
                <li>
                    <a href="{{ route('user.list') }}" class="flex text-sm items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('*faq/list*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            list_alt
                        </span>
                        <span class="font-medium">List All FAQs</span>
                    </a>
                </li>
                <li>
                    <a href="" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('*faq/add*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            add_circle
                        </span>
                        <span class="font-medium">Add New FAQ</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Categories -->
        <div class="group">
            <div class="flex justify-between items-center cursor-pointer hover:text-blue-500 hover:bg-blue-100 px-1.5 transition-all duration-300 group-[.open]:bg-blue-100 group-[.open]:text-blue-500 rounded-md py-2 {{ Request::is('terms*') ? 'text-blue-500 bg-blue-100' : '' }}" 
                onclick="toggleMenu(this)"
            >
                <div class="flex items-center text-center gap-3">
                    <span class="material-symbols-outlined">
                        contract
                    </span>
                    <span class="font-medium tracking-wide">Terms & Conditions</span>
                </div>
                <i class="fa-solid fa-chevron-down text-sm mr-2"></i>
            </div>

            <ul class="px-2 hidden group-[.open]:block space-y-2 my-3">
                <li>
                    <a href="" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('terms/list*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            list_alt
                        </span>
                        <span class="font-medium">Terms list List</span>
                    </a>
                </li>
                <li>
                    <a href="" class="flex items-center text-sm text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ Request::is('terms/add*') ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                        <span class="material-symbols-outlined">
                            contract_edit
                        </span>
                        <span class="font-medium">Add New Terms</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="flex flex-col gap-2 px-3">
    </div>

</div>


<script>
    function toggleMenu(element) {
        const parentLi = element.parentElement;
        parentLi.classList.toggle('open');
        const arrowIcon = element.querySelector('.fa-chevron-down');
        arrowIcon.classList.toggle('rotate-180');
    }
</script>