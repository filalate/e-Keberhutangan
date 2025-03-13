@extends('layouts.app')

@section('content')

    <div class="container mx-auto mt-6">
        <h1 class="text-xl font-bold text-gray-700">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-gray-600">Anda log masuk sebagai <strong class="text-blue-500">Admin</strong>.</p>
    </div>

@endsection
