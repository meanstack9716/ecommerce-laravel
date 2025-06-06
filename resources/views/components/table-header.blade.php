@props([
    'fields' => [], 
    'routeName' => '',
    'sortBy' => request('sort_by'),
    'sortOrder' => request('sort_order', 'asc'),
])

<thead class="bg-indigo-100 text-gray-700">
    <tr>
        @foreach ($fields as $field)
            @if(!isset($field['hide']) || $field['hide'] != true)
            @if($field['allow_sort'] && !empty($field['field']))
                <th class="px-6 py-3 text-center text-sm font-semibold uppercase tracking-wider min-w-60 text-gray-500 ">
                    <a href="{{ route($routeName, array_merge(request()->query(), [
                        'sort_by' => $field['field'], 
                        'sort_order' => $sortBy === $field['field'] && $sortOrder === 'asc' ? 'desc' : 'asc',
                        'page' => 1
                    ])) }}" 
                    class="flex items-center justify-center gap-1">
                        {{ $field['label'] }}
                        @if($sortBy === $field['field'])
                            <span class="material-symbols-outlined text-sm">
                                {{ $sortOrder === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down' }}
                            </span>
                        @endif
                    </a>
                </th>
            @else
                <th class="px-6 py-3 text-center text-sm font-semibold uppercase tracking-wider min-w-60 text-gray-500">
                    {{ $field['label'] }}
                </th>
            @endif
            @endif
        @endforeach
    </tr>
</thead>