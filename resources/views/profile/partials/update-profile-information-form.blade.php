<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Maklumat Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Kemas kini maklumat profil dan alamat e-mel akaun anda.") }}
        </p>
    </header>

    <div class="mt-6 space-y-5">
        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 3000)"
                 class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ __('Berjaya dikemaskini!') }}</span>
            </div>
        @endif

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nama') }}</label>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" 
                    value="{{ old('name', $user->name) }}" 
                    required 
                    autofocus
                    autocomplete="name"
                    placeholder="Nama anda">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Emel -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('E-mel') }}</label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" 
                    value="{{ old('email', $user->email) }}" 
                    required
                    autocomplete="email"
                    placeholder="example@domain.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-3 text-sm text-gray-800 bg-gray-50 rounded-md">
                        <p>
                            {{ __('Alamat e-mel anda belum disahkan.') }}
                            <button form="send-verification" class="underline text-blue-600 hover:text-blue-800">
                                {{ __('Klik di sini untuk menghantar semula e-mel pengesahan.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm text-green-600 font-medium">
                                {{ __('Pautan pengesahan baharu telah dihantar ke alamat e-mel anda.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Butang Simpan -->
            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Simpan') }}
                </button>
            </div>
        </form>
    </div>
</div>