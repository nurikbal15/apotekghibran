@extends('layout')
@section('content')
<div class="container-fluid">

    <div class="text-center">
        <div class="error mx-auto" data-text="401">401</div>
        <p class="lead text-gray-800 mb-5">Unauthorized</p>
        <p class="text-gray-500 mb-0">Anda Tidak Memiliki Akses</p>
        <a href="{{ route ('dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>

</div>
@endsection
