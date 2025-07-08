@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 space-y-8">
    <!-- Header Profil -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengurusan Profil</h1>
        <p class="mt-2 text-gray-600">Urus maklumat akaun dan keselamatan anda</p>
    </div>

    <!-- Grid Dua Kolum -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Kolum Kiri -->
        <div class="space-y-8">
            <!-- Maklumat Profil -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    Maklumat Profil
                </h2>
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Padam Akaun -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Padam Akaun
                </h2>
                @include('profile.partials.delete-user-form')
            </div>
        </div>

        <!-- Kolum Kanan -->
        <div class="space-y-8">
            <!-- Kemaskini Kata Laluan -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    Keselamatan Akaun
                </h2>
                @include('profile.partials.update-password-form')
            </div>

            <!-- Nota Keselamatan (Optional) -->
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                <h3 class="font-medium text-blue-800 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Tips Keselamatan
                </h3>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start gap-2">
                        <span>•</span> Gunakan kata laluan yang unik dan kompleks
                    </li>
                    <li class="flex items-start gap-2">
                        <span>•</span> Jangan berkongsi kata laluan dengan mana-mana individu
                    </li>
                    <li class="flex items-start gap-2">
                        <span>•</span> Kemaskini kata laluan secara berkala
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection