<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Padam Akaun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setelah akaun anda dipadam, semua data dan maklumat berkaitan akan dipadam secara kekal. Sebelum meneruskan, sila muat turun atau simpan sebarang maklumat yang anda ingin kekalkan.') }}
        </p>
    </header>

    <!-- Butang Padam Akaun -->
    <button 
        x-data 
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        {{ __('Padam Akaun') }}
    </button>

    <!-- Modal Pengesahan -->
    <div x-data="{ showModal: false }" x-show="showModal" 
         x-on:open-modal.window="showModal = $event.detail === 'confirm-user-deletion'"
         x-on:keydown.escape.window="showModal = false"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;">
        
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Adakah anda pasti ingin memadam akaun anda?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                {{ __('Setelah akaun dipadam, semua data dan sumber yang berkaitan akan hilang secara kekal. Sila masukkan kata laluan anda untuk mengesahkan pemadaman akaun.') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
                @csrf
                @method('delete')

                <!-- Input Kata Laluan -->
                <div>
                    <label for="password" class="sr-only">{{ __('Kata Laluan') }}</label>
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Masukkan kata laluan') }}"
                        required />
                    
                    @error('password')
                        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Butang Pengendalian -->
                <div class="mt-6 flex justify-end">
                    <button 
                        type="button"
                        x-on:click="showModal = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        {{ __('Batal') }}
                    </button>

                    <button 
                        type="submit"
                        class="ml-3 bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                        {{ __('Padam Akaun') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>