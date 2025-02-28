@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Kemaskini Kata Laluan') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akaun anda menggunakan kata laluan yang panjang dan rawak untuk kekal selamat.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Kata Laluan Semasa -->
        <div>
            <label for="current_password" class="block font-medium text-sm text-gray-700">{{ __('Kata Laluan Semasa') }}</label>
            <input 
                id="current_password" 
                name="current_password" 
                type="password" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                autocomplete="current-password"
                required>
            @error('current_password')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kata Laluan Baru -->
        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Kata Laluan Baru') }}</label>
            <input 
                id="password" 
                name="password" 
                type="password" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                autocomplete="new-password"
                required>
            @error('password')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sahkan Kata Laluan Baru -->
        <div>
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Sahkan Kata Laluan') }}</label>
            <input 
                id="password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                autocomplete="new-password"
                required>
            @error('password_confirmation')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Butang Simpan & Mesej Berjaya -->
        <div class="flex items-center gap-4">
            <button 
                type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-indigo-200">
                {{ __('Simpan') }}
            </button>

            <!-- Mesej Berjaya -->
            @if (session('status') === 'password-updated')
                <p 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-transition 
                    x-init="setTimeout(() => show = false, 2000)" 
                    class="text-sm text-green-600">
                    {{ __('Kata laluan berjaya dikemaskini!') }}
                </p>
            @endif
        </div>
    </form>
</div>
@endsection
