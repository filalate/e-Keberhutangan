@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <div class="container mx-auto mt-6">
        <h1 class="text-xl font-bold text-gray-700">Senarai Penyelia Negeri yang Belum Disahkan</h1>

        @if(session('success'))
            <div class="bg-green-500 text-white p-2 rounded mt-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto mt-4">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="border border-gray-300 px-4 py-2">Bil.</th>
                        <th class="border border-gray-300 px-4 py-2">Nama</th>
                        <th class="border border-gray-300 px-4 py-2">Emel</th>
                        <th class="border border-gray-300 px-4 py-2">Negeri</th>
                        <th class="border border-gray-300 px-4 py-2">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $admin->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $admin->email }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $admin->negeri }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <form action="{{ route('verify.admin', $admin->id) }}" method="POST">
                                    @csrf
                                    <div class="text-center">
                                        <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 border-2 border-blue-500 hover:border-blue-700">
                                            Sahkan
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">
                                Tiada Admin Negeri yang menunggu pengesahan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
