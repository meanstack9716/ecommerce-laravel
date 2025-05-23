@php
    $sidebarRoutes = [
        [
            'label' => 'Main Home',
            'items' => [
                [
                    'title' => 'Dashboard',
                    'icon' => 'dashboard',
                    'route' => route('dashboard'),
                    'active' => Request::is('*dashboard*'),
                ],
            ],
        ],
        [
            'label' => 'All Pages',
            'items' => [
                [
                    'title' => 'User',
                    'icon' => 'person',
                    'submenu' => true,
                    'active' => Request::is('users*'),
                    'items' => [
                        [
                            'title' => 'All user',
                            'icon' => 'groups',
                            'route' => route('user.list'),
                            'active' => Request::is('users/list*'),
                        ],
                        [
                            'title' => 'Add New User',
                            'icon' => 'person_add',
                            'route' => '#',
                            'active' => Request::is('*users/register*'),
                        ],
                    ],
                ],
                [
                    'title' => 'Sellers',
                    'icon' => 'supervisor_account',
                    'submenu' => true,
                    'active' => Request::is('sellers*'),
                    'items' => [
                        [
                            'title' => 'List All sellers',
                            'icon' => 'groups',
                            'route' => route('seller.list'),
                            'active' => Request::is('sellers/list*'),
                        ],
                        [
                            'title' => 'Add New Seller',
                            'icon' => 'person_add',
                            'route' => route('seller.register.personal'),
                            'active' => Request::is('*sellers/register*'),
                        ],
                    ],
                ],
                [
                    'title' => 'Categories',
                    'icon' => 'stacks',
                    'submenu' => true,
                    'active' => Request::is('category*'),
                    'items' => [
                        [
                            'title' => 'Categories List',
                            'icon' => 'category',
                            'route' => route('category.list'),
                            'active' => Request::is('category/list*'),
                        ],
                        [
                            'title' => 'Add New Category',
                            'icon' => 'add_circle',
                            'route' => route('category.add'),
                            'active' => Request::is('*category/add*'),
                        ],
                        [
                            'title' => 'Sub Categories List',
                            'icon' => 'inventory',
                            'route' => route('sub-category.list'),
                            'active' => Request::is('category/sub/list*'),
                        ],
                        [
                            'title' => 'Add Sub-Category',
                            'icon' => 'add_box',
                            'route' => route('sub-category.add'),
                            'active' => Request::is('category/sub/add*'),
                        ],
                        [
                            'title' => 'List Sub Sub-Category',
                            'icon' => 'format_list_bulleted',
                            'route' => route('sub-sub-category.list'),
                            'active' => Request::is('category/sub-sub/list*'),
                        ],
                        [
                            'title' => 'Add Sub Sub-Category',
                            'icon' => 'add_circle',
                            'route' => route('sub-sub-category.add'),
                            'active' => Request::is('category/sub-sub/add*'),
                        ],
                    ],
                ],
                [
                    'title' => 'Products',
                    'icon' => 'store',
                    'submenu' => true,
                    'active' => Request::is('products*'),
                    'items' => [
                        [
                            'title' => 'Add New Product',
                            'icon' => 'add_circle',
                            'route' => route('products.add.step1'),
                            'active' => Request::is('products/add*'),
                        ],
                        [
                            'title' => 'Products List',
                            'icon' => 'table',
                            'route' => route('products.list'),
                            'active' => Request::is('products/list*'),
                        ],
                    ],
                ],
                [
                    'title' => 'Brands',
                    'icon' => 'gallery_thumbnail',
                    'submenu' => true,
                    'active' => Request::is('brands*'),
                    'items' => [
                        [
                            'title' => 'Brands List',
                            'icon' => 'list_alt',
                            'route' => route('products.brand.list'),
                            'active' => Request::is('brands/list*'),
                        ],
                        [
                            'title' => 'Add New Brand',
                            'icon' => 'add_circle',
                            'route' => route('products.brand.add'),
                            'active' => Request::is('brands/add*'),
                        ],
                    ],
                ],
                [
                    'title' => 'Orders',
                    'icon' => 'receipt_long',
                    'route' =>  route('orders.list'),
                    'active' => Request::is('*orders*'),
                ],
                [
                    'title' => 'Promo codes',
                    'icon' => 'redeem',
                    'submenu' => true,
                    'active' => Request::is('promo-code*'),
                    'items' => [
                        [
                            'title' => 'Add New Promo code',
                            'icon' => 'add_circle',
                            'route' => route('promo-code.add'),
                            'active' => Request::is('*promo-code/add*'),
                        ],
                        [
                            'title' => 'Promo codes list',
                            'icon' => 'list_alt',
                            'route' => route('promo-code.list'),
                            'active' => Request::is('*promo-code/list*'),
                        ],
                    ],
                ],
            ],
        ],
    ];
@endphp

<div class="h-[calc(100vh-72px)] 3xl:h-[calc(100vh-80px)] bg-white shadow-md overflow-y-auto py-8 px-3 space-y-5" id="sidebar-wrap">
    @foreach ($sidebarRoutes as $section)
        <div class="flex flex-col gap-2 px-3">
            <p class="text-gray-400 font-semibold">{{ $section['label'] }}</p>

            @foreach ($section['items'] as $item)
                @if (!empty($item['submenu']))
                    <div class="group">
                        <div class="flex justify-between items-center cursor-pointer hover:text-blue-500 hover:bg-blue-100 px-3 transition-all duration-300 group-[.open]:bg-blue-100 group-[.open]:text-blue-500 rounded-md py-2 
                            {{ $item['active'] ? 'text-blue-500 bg-blue-100' : '' }}" 
                            onclick="toggleMenu(this)"
                        >
                            <div class="flex items-center text-center gap-3">
                                <span class="material-symbols-outlined">
                                    {{ $item['icon'] }}
                                </span>
                                <span class="font-medium tracking-wide">{{ $item['title'] }}</span>
                            </div>
                            <span class="material-symbols-outlined mr-2 rotate-90">
                                chevron_right
                            </span>
                        </div>

                        <ul class="pl-4 hidden group-[.open]:block space-y-2 my-3">
                            @foreach ($item['items'] as $sub)
                                <li>
                                    <a href="{{ $sub['route'] }}" class="flex text-sm items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-2 transition-all duration-300 rounded-lg {{ $sub['active'] ? 'text-blue-500 bg-blue-100' : 'text-gray-600' }}">
                                        <span class="material-symbols-outlined">
                                            {{ $sub['icon'] }}
                                        </span>
                                        <span class="font-medium">{{ $sub['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <a href="{{ $item['route'] }}" class="flex items-center text-center gap-3 hover:text-blue-500 hover:bg-blue-100 py-2 px-3 transition-all duration-300 rounded-lg {{ $item['active'] ? 'text-blue-500 bg-blue-100' : '' }}">
                        <span class="material-symbols-outlined">
                            {{ $item['icon'] }}
                        </span>
                        <span class="font-medium">{{ $item['title'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    @endforeach
</div>


<script>
    function toggleMenu(element) {
        const parentLi = element.parentElement;
        parentLi.classList.toggle('open');
        const arrowIcon = element.querySelector('.fa-chevron-down');
        arrowIcon.classList.toggle('rotate-180');
    }
</script>