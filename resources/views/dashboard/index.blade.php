@extends('layouts.main')

@section('content')
<p class="text-red-500">This is dashboard page {{ auth()->user()->email }}</p>
{{ Route::current()->uri() }}


{{ url()->current() }}


{{ Route::currentRouteName() }}
@endsection