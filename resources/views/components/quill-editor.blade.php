@props([
    'name', 
    'label', 
    'value' => '', 
    'required' => false, 
    'id' => null,
    'height' => '150px',
    'editorId' => 'quill-editor'
])

@php
    $id = $id ?? $name;
@endphp

<style>
    #{{ $editorId }} {
        min-height: {{ $height }};
        border: 1px solid #ccc;
    }

    .ql-toolbar.ql-snow {
        border-radius: 6px 6px 0 0;
    }

    .ql-container.ql-snow {
        border-radius: 0 0 6px 6px;
    }
</style>


@if($label ?? null)
    <label for="{{ $id }}" class="font-medium 3xl:text-xl 3xl:font-semibold"> {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
@endif

<div class="pt-1.5">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}"  value="{!! old($name, $value) !!}">
    <div id="{{ $editorId }}" ></div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#{{ $editorId }}', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['clean'],
                    ['link', 'image']
                ]
            }
        });

        quill.root.innerHTML = document.getElementById('{{ $id }}').value;

        quill.on('text-change', function() {
            document.getElementById('{{ $id }}').value = quill.root.innerHTML;
        });

        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('{{ $id }}').value =  quill.root.innerHTML;
        });
    });
    
</script>