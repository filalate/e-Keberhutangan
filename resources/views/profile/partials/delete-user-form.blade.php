<div class="space-y-5">
    @if (session('status') === 'account-deleted')
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Akaun berjaya dipadam!</span>
        </div>
    @endif

    <p class="text-sm text-gray-600">
        {{ __('Setelah akaun anda dipadam, semua data dan maklumat berkaitan akan dipadam secara kekal.') }}
    </p>

    <button 
        x-data 
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        {{ __('Padam Akaun') }}
    </button>

    <div x-data="{ showModal: false }" 
         x-show="showModal" 
         x-on:open-modal.window="showModal = $event.detail === 'confirm-user-deletion'"
         x-on:keydown.escape.window="showModal = false"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
         style="display: none;"> <!-- Tambahkan style="display: none;" -->
        
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Adakah anda pasti ingin memadam akaun anda?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                {{ __('Tindakan ini tidak boleh dibatalkan. Sila masukkan kata laluan anda untuk mengesahkan.') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-4 space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="sr-only">{{ __('Kata Laluan') }}</label>
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3"
                        placeholder="{{ __('Kata Laluan Anda') }}"
                        required />
                    
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        type="button"
                        x-on:click="showModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        {{ __('Batal') }}
                    </button>

                    <button 
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        {{ __('Padam Akaun') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>