@extends('layouts.app')

@section('content')

    <div class="container mx-auto mt-6">
        <h1 class="text-xl font-bold text-gray-700">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-gray-600">Anda log masuk sebagai <strong class="text-blue-500">Superadmin</strong>.</p>
    </div>

    @if(auth()->user()->role === 'superadmin')
    <div class="card">
        <div class="card-header">
            <h4>Pilih Negeri</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard') }}" method="GET">
                <select name="negeri" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Pilih Negeri --</option>
                    @foreach(['IBU PEJABAT','JOHOR','KEDAH','KELANTAN','MELAKA','NEGERI SEMBILAN','PAHANG','PULAU PINANG','PERAK','PERLIS','SELANGOR','TERENGGANU','SARAWAK','WILAYAH PERSEKUTUAN KUALA LUMPUR','WILAYAH PERSEKUTUAN LABUAN','WILAYAH PERSEKUTUAN PUTRAJAYA','FRAM WILAYAH UTARA','FRAM WILAYAH TIMUR','FRAM SABAH','FRAM SARAWAK'] as $negeri)
                        <option value="{{ $negeri }}" {{ request('negeri') == $negeri ? 'selected' : '' }}>{{ $negeri }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->user()->role === 'superadmin' && request('negeri'))
    <div class="card mt-4">
        <div class="card-header">
            <h4>Data Negeri: {{ request('negeri') }}</h4>
        </div>
        <div class="card-body">
            <a href="{{ route('penyata-gaji.index', ['negeri' => request('negeri')]) }}" class="btn btn-primary">Lihat Penyata Gaji</a>
            <a href="{{ route('pinjaman-perumahan.index', ['negeri' => request('negeri')]) }}" class="btn btn-success">Lihat Pinjaman Perumahan</a>
            <a href="{{ route('borang.index', ['negeri' => request('negeri')]) }}" class="btn btn-success">Lihat SKAI07</a>
        </div>
    </div>
    @endif


    
@endsection
