
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Maklumat Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Kemas kini maklumat profil dan alamat e-mel akaun anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nama -->
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Nama') }}</label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                value="{{ old('name', $user->name) }}" 
                required autofocus autocomplete="name">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Emel -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">{{ __('E-mel') }}</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                value="{{ old('email', $user->email) }}" 
                required autocomplete="username">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Alamat e-mel anda belum disahkan.') }}
                        <button form="send-verification" class="underline text-sm text-indigo-600 hover:text-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk menghantar semula e-mel pengesahan.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Pautan pengesahan baharu telah dihantar ke alamat e-mel anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Butang Simpan & Mesej Berjaya -->
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-blue-600 text-white font-bold px-4 py-2 rounded-md hover:bg-vivid blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bliue-500 ">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-transition 
                    x-init="setTimeout(() => show = false, 2000)" 
                    class="text-sm text-green-600">
                    {{ __('Berjaya dikemaskini!') }}
                </p>
            @endif
        </div>
    </form>
</div>

