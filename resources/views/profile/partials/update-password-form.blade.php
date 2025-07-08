<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Kemaskini Kata Laluan') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akaun anda menggunakan kata laluan yang panjang dan rawak untuk kekal selamat.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        @if (session('status') === 'password-updated')
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 3000)"
                 class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ __('Kata laluan berjaya dikemaskini!') }}</span>
            </div>
        @endif

        <!-- Current Password -->
        <div>
            <label for="current_pass" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Kata Laluan Semasa') }}</label>
            <div class="relative">
                <input 
                    id="current_pass" 
                    name="current_password" 
                    type="password" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 pr-10"
                    autocomplete="current-password"
                    required
                    placeholder="••••••••">
                <div onclick="togglePasswordVisibility('current_pass', this)" 
                    class="absolute inset-y-0 right-0 flex items-center justify-center w-10 h-full cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="new_pass" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Kata Laluan Baru') }}</label>
            <div class="relative">
                <input 
                    id="new_pass" 
                    name="password" 
                    type="password" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 pr-10"
                    autocomplete="new-password"
                    required
                    placeholder="Minimum 8 aksara">
                <div onclick="togglePasswordVisibility('new_pass', this)" 
                    class="absolute inset-y-0 right-0 flex items-center justify-center w-10 h-full cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="confirm_pass" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Sahkan Kata Laluan') }}</label>
            <div class="relative">
                <input 
                    id="confirm_pass" 
                    name="password_confirmation" 
                    type="password" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 pr-10"
                    autocomplete="new-password"
                    required
                    placeholder="Taip semula kata laluan">
                <div onclick="togglePasswordVisibility('confirm_pass', this)" 
                    class="absolute inset-y-0 right-0 flex items-center justify-center w-10 h-full cursor-pointer text-gray-500">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Save Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('Simpan') }}
            </button>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility(fieldId, button) {
        const input = document.getElementById(fieldId);
        const eyeIcon = button.querySelector('svg');

        if (input.type === 'password') {
            input.type = 'text';
            // Open eye icon with slash through it, no border around the eye icon
            eyeIcon.innerHTML = `
                <g>
                    <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    <line x1="4" y1="4" x2="16" y2="16" stroke="currentColor" stroke-width="2" style="pointer-events: none;"/>
                </g>
            `;
        } else {
            input.type = 'password';
            // Closed eye icon without slash, no border around the eye icon
            eyeIcon.innerHTML = `
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
            `;
        }
    }
</script>
