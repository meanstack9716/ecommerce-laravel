<div class="flex flex-col items-start gap-2">
    <p class="m-0 text-gray-600 font-medium">Search by Seller Name</p>
    <div class="relative w-full">
        <input 
            type="text" 
            id="seller-search" 
            name="seller_term" 
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Search seller name"
            value="{{ request('seller_term') }}"
            autocomplete="off"
        >
        <div id="seller-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-gray-500 pointer-events-none">
            search
        </span>
    </div>
    <input 
        type="hidden" 
        id="seller-filter" 
        name="sellerId" 
        value="{{ request('sellerId') }}"
    >
</div>

<script>
    function setupSellerAutocomplete() {
        const searchInput = document.getElementById('seller-search');
        const resultsContainer = document.getElementById('seller-search-results');
        const selectedDataId = document.getElementById('seller-filter');

        let debounceTimer;

        if (!searchInput || !resultsContainer) return;

        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                selectedDataId.value = '';
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search/sellers?searchTerm=${encodeURIComponent(query)}`);
                    const data = await response.json();                
                    if (data?.data?.length > 0) {
                        resultsContainer.innerHTML = data.data.map(item => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                data-seller-id="${item.id}">
                                ${item.business_name}
                            </div>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No seller found</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } catch (error) {
                    resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error loading results</div>';
                    resultsContainer.classList.remove('hidden');
                    console.error('Error fetching sellers:', error);
                }
            }, 300);
        });

        resultsContainer.addEventListener('click', async function(e) {
            const selectedItem = e.target.closest('[data-seller-id]');

            if (selectedItem) {
                const selectedId = selectedItem.getAttribute('data-seller-id');
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
        setupSellerAutocomplete()
    });
</script>