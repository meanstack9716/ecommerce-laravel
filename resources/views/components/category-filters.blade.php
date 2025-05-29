@props([
    'showCategoryFilter' => true, 
    'showSubCategoryFilter' => false, 
    'showSubSubCategoryFilter' => false
])

@if($showCategoryFilter)
<div class="flex flex-col items-start gap-2">
    <p class="m-0 text-gray-600 font-medium">Search by Category</p>
    <div class="relative w-full">
        <input 
            type="text" 
            id="category-search" 
            name="category_term" 
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Search category type"
            value="{{ request('category_term') }}"
            autocomplete="off"
        >
        <div id="category-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 pointer-events-none">
            search
        </span>
    </div>
    <input 
        type="hidden" 
        id="category-filter" 
        name="categoryId" 
        value="{{ request('categoryId') }}"
    >
</div>
@endif

@if($showSubCategoryFilter)
<div class="flex flex-col items-start gap-2">
    <p class="m-0 text-gray-600 font-medium">Search by Sub Category</p>
    <div class="relative w-full">
        <input 
            type="text" 
            id="sub-category-search" 
            name="sub_category_term" 
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Search sub category type"
            value="{{ request('sub_category_term') }}"
            autocomplete="off"
        >
        <div id="sub-category-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 pointer-events-none">
            search
        </span>
    </div>
    <input 
        type="hidden" 
        id="sub-category-filter" 
        name="subCategoryId" 
        value="{{ request('subCategoryId') }}"
    >
</div>
@endif

@if($showSubSubCategoryFilter)
<div class="flex flex-col items-start gap-2">
    <p class="m-0 text-gray-600 font-medium">Search by Sub Sub Category</p>
    <div class="relative w-full">
        <input 
            type="text" 
            id="sub-sub-category-search" 
            name="sub_sub_category_term" 
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Search sub sub category type"
            value="{{ request('sub_sub_category_term') }}"
            autocomplete="off"
        >
        <div id="sub-sub-category-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 pointer-events-none">
            search
        </span>
    </div>
    <input 
        type="hidden" 
        id="sub-sub-category-filter" 
        name="subSubCategoryId" 
        value="{{ request('subSubCategoryId') }}"
    >
</div>
@endif



<script>

    function setupCategoryAutocomplete(hasSub, hasSubSub) {
        const searchInput = document.getElementById('category-search');
        const resultsContainer = document.getElementById('category-search-results');
        const selectedDataId = document.getElementById('category-filter');

        const subCategorySearch = document.getElementById('sub-category-search');
        const subCategoryFilter = document.getElementById('sub-category-filter');

        const subSubCategorySearch = document.getElementById('sub-sub-category-search');
        const subSubCategoryFilter = document.getElementById('sub-sub-category-filter');
        let debounceTimer;

        if (!searchInput || !resultsContainer) return;

        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                selectedDataId.value = '';
                if (hasSub) {
                    subCategorySearch.value = '';
                    subCategoryFilter.value = '';
                }
                if (hasSubSub) {
                    subSubCategorySearch.value = '';
                    subSubCategoryFilter.value = '';
                }
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search/categories?searchTerm=${encodeURIComponent(query)}`);
                    const data = await response.json();                
                    if (data?.data?.length > 0) {
                        resultsContainer.innerHTML = data.data.map(item => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                data-category-id="${item.id}">
                                ${item.name}
                            </div>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No category found</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } catch (error) {
                    resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error loading results</div>';
                    resultsContainer.classList.remove('hidden');
                    console.error('Error fetching categories:', error);
                }
            }, 300);
        });

        resultsContainer.addEventListener('click', async function(e) {
            const selectedItem = e.target.closest('[data-category-id]');

            if (selectedItem) {
                const selectedId = selectedItem.getAttribute('data-category-id');
                const selectedName = selectedItem.textContent.trim();
                
                searchInput.value = selectedName;
                selectedDataId.value = selectedId;
                resultsContainer.classList.add('hidden');
                if (hasSub) {
                    subCategorySearch.value = '';
                    subCategoryFilter.value = '';
                }
                if (hasSubSub) {
                    subSubCategorySearch.value = '';
                    subSubCategoryFilter.value = '';
                }
            }

        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                if (!resultsContainer.classList.contains('hidden')) {
                    resultsContainer.classList.add('hidden');
                    searchInput.value = '';
                }
            }
        });
    }

    function setupSubCategoryAutocomplete(hasSubSub) {
        const searchInput = document.getElementById('sub-category-search');
        const resultsContainer = document.getElementById('sub-category-search-results');
        const selectedDataId = document.getElementById('sub-category-filter');
        const categoryFilter = document.getElementById('category-filter');

        const subSubCategorySearch = document.getElementById('sub-sub-category-search');
        const subSubCategoryFilter = document.getElementById('sub-sub-category-filter');
        let debounceTimer;

        if (!searchInput || !resultsContainer) return;

        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
            const categoryId = categoryFilter.value;

            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                selectedDataId.value = '';

                if (hasSubSub) {
                    subSubCategorySearch.value = '';
                    subSubCategoryFilter.value = '';
                }
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                try {
                    let url = `/api/search/sub-categories?searchTerm=${encodeURIComponent(query)}`
                    url += categoryId ?  `&categoryId=${categoryId}` : '';
                    const response = await fetch(url);
                    const data = await response.json();
                    if (data?.data?.length > 0) {
                        resultsContainer.innerHTML = data.data.map(item => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                data-sub-category-id="${item.id}">
                                ${item.name}
                            </div>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No sub category found</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } catch (error) {
                    resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error loading results</div>';
                    resultsContainer.classList.remove('hidden');
                    console.error('Error fetching sub categories:', error);
                }
            }, 300);
        });

        resultsContainer.addEventListener('click', async function(e) {
            const selectedItem = e.target.closest('[data-sub-category-id]');
            if (selectedItem) {
                const selectedId = selectedItem.getAttribute('data-sub-category-id');
                const selectedName = selectedItem.textContent.trim();
                
                searchInput.value = selectedName;
                selectedDataId.value = selectedId;
                resultsContainer.classList.add('hidden');

                if (hasSubSub) {
                    subSubCategorySearch.value = '';
                    subSubCategoryFilter.value = '';
                }
            }
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                if (!resultsContainer.classList.contains('hidden')) {
                    resultsContainer.classList.add('hidden');
                    searchInput.value = '';
                }
            }
        });
    }

    function setupSubSubCategoryAutocomplete() {
        const searchInput = document.getElementById('sub-sub-category-search');
        const resultsContainer = document.getElementById('sub-sub-category-search-results');
        const selectedDataId = document.getElementById('sub-sub-category-filter');
        const categoryFilter = document.getElementById('category-filter');
        const subCategoryFilter = document.getElementById('sub-category-filter');

        let debounceTimer;
        if (!searchInput || !resultsContainer) return;

        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
            const categoryId = categoryFilter.value;
            const subCategoryId = subCategoryFilter.value;
                
            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                selectedDataId.value = '';
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                try {
                    let url = `/api/search/sub-sub-categories?searchTerm=${encodeURIComponent(query)}`
                    url += categoryId ?  `&categoryId=${categoryId}` : '';
                    url += subCategoryId ?  `&subCategoryId=${subCategoryId}` : '';
                    const response = await fetch(url);
                    const data = await response.json();
                    if (data?.data?.length > 0) {
                        resultsContainer.innerHTML = data.data.map(item => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                data-sub-sub-category-id="${item.id}">
                                ${item.name}
                            </div>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No sub sub category found</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } catch (error) {
                    resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error loading results</div>';
                    resultsContainer.classList.remove('hidden');
                    console.error('Error fetching sub categories:', error);
                }
            }, 300);
        });

        resultsContainer.addEventListener('click', async function(e) {
            const selectedItem = e.target.closest('[data-sub-sub-category-id]');
            if (selectedItem) {
                const selectedId = selectedItem.getAttribute('data-sub-sub-category-id');
                const selectedName = selectedItem.textContent.trim();
                
                searchInput.value = selectedName;
                selectedDataId.value = selectedId;
                resultsContainer.classList.add('hidden');
            }
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                if (!resultsContainer.classList.contains('hidden')) {
                    resultsContainer.classList.add('hidden');
                    searchInput.value = '';
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const showCategoryFilter = @json($showCategoryFilter);
        const showSubCategoryFilter = @json($showSubCategoryFilter);
        const showSubSubCategoryFilter = @json($showSubSubCategoryFilter);

        if (showCategoryFilter) setupCategoryAutocomplete(showSubCategoryFilter, showSubSubCategoryFilter);
        if (showSubCategoryFilter) setupSubCategoryAutocomplete(showSubSubCategoryFilter);
        if (showSubSubCategoryFilter) setupSubSubCategoryAutocomplete();
    });
</script>