<x-guest-layout> 
    <div class="auth-container">
        <h2 class="auth-title">Log Masuk</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Error Messages Popup -->
        @if ($errors->any())
            <div id="errorModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>{{ $errors->first() }}</h3>
                    <button id="closeErrorModal" class="btn btn-danger">OK</button>
                </div>
            </div>
        @endif

        <!-- Notyf Success Notification (if available in session) -->
        @if (session('success'))
            <script>
                // Create a new Notyf instance
                const notyf = new Notyf({
                    duration: 3000,  // Show for 3 seconds
                    ripple: true,    // Apply ripple effect
                    position: { x: 'right', y: 'top' },  // Set position to top-right
                    dismissible: true // Make the notification dismissible
                });
                
                // Display the success message from session
                notyf.success("{{ session('success') }}");
            </script>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email">Alamat Emel</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            </div>

            <!-- Password -->
            <div class="password-field">
                <label for="password">Kata Laluan</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                <span id="eye-icon" class="fa fa-eye" onclick="togglePassword()"></span> <!-- Eye Icon -->
            </div>

            <!-- Remember Me (Ingat Saya) -->
            <div class="auth-remember">
                <label for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember">
                    Ingat Saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa Kata Laluan?</a>
                @endif
            </div>

            <button type="submit" class="auth-button">Log Masuk</button>

            <div class="auth-links">
                <p>Belum ada akaun? <a href="{{ route('register') }}">Daftar</a></p>
            </div>
        </form>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordField.type === "password") {
                passwordField.type = "text";  // Show password
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");  // Change icon to slash
            } else {
                passwordField.type = "password";  // Hide password
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");  // Change icon back to eye
            }
        }

        // Handling the error modal
        const errorModal = document.getElementById("errorModal");
        if (errorModal) {
            const closeErrorBtn = document.getElementById("closeErrorModal");
            const closeErrorIcon = document.getElementsByClassName("close")[0];

            // Show the error modal if there are errors
            errorModal.style.display = "block";

            // Close the modal when the user clicks the "OK" button
            closeErrorBtn.onclick = function() {
                errorModal.style.display = "none";
            }

            // Close the modal when the user clicks the close button (X)
            closeErrorIcon.onclick = function() {
                errorModal.style.display = "none";
            }

            // Close the modal if the user clicks outside the modal content
            window.onclick = function(event) {
                if (event.target == errorModal) {
                    errorModal.style.display = "none";
                }
            }
        }
    </script>
</x-guest-layout>
