<x-guest-layout> 
    <div class="auth-container">
        <h2 class="auth-title">Daftar Akaun</h2>

        <!-- Success Message -->
        @if(session('success'))
        <!-- Popup Modal -->
        <div id="successModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>{{ session('success') }}</h3>
                <button id="redirectToLogin" class="btn btn-success">OK</button>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <!-- Nama -->
            <div>
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <!-- Emel -->
            <div>
                <label for="email">Alamat Emel</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <!-- Kata Laluan -->
            <div class="password-field">
                <label for="password">Kata Laluan</label>
                <input id="password" type="password" name="password" required>
                <span id="togglePassword" style="cursor: pointer;">
                    <i id="eye-icon" class="fa fa-eye"></i> <!-- Eye Icon -->
                </span>
            </div>

            <!-- Sahkan Kata Laluan -->
            <div class="password-field">
                <label for="password_confirmation">Sahkan Kata Laluan</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
                <span id="togglePasswordConfirmation" style="cursor: pointer;">
                    <i id="eye-icon-confirmation" class="fa fa-eye"></i> <!-- Eye Icon -->
                </span>
            </div>

            <!-- Negeri -->
            <div>
                <label for="negeri">Negeri</label>
                <select id="negeri" name="negeri" required>
                    <option value="IBU PEJABAT">IBU PEJABAT</option>
                    <option value="JOHOR">JOHOR</option>
                    <option value="KEDAH">KEDAH</option>
                    <option value="KELANTAN">KELANTAN</option>
                    <option value="MELAKA">MELAKA</option>
                    <option value="NEGERI SEMBILAN">NEGERI SEMBILAN</option>
                    <option value="PAHANG">PAHANG</option>
                    <option value="PULAU PINANG">PULAU PINANG</option>
                    <option value="PERAK">PERAK</option>
                    <option value="PERLIS">PERLIS</option>
                    <option value="SELANGOR">SELANGOR</option>
                    <option value="TERENGGANU">TERENGGANU</option>
                    <option value="SARAWAK">SARAWAK</option>
                    <option value="WILAYAH PERSEKUTUAN KUALA LUMPUR">WILAYAH PERSEKUTUAN KUALA LUMPUR</option>
                    <option value="WILAYAH PERSEKUTUAN LABUAN">WILAYAH PERSEKUTUAN LABUAN</option>
                    <option value="WILAYAH PERSEKUTUAN PUTRAJAYA">WILAYAH PERSEKUTUAN PUTRAJAYA</option>
                    <option value="FRAM WILAYAH UTARA">FRAM WILAYAH UTARA</option>
                    <option value="FRAM WILAYAH TIMUR">FRAM WILAYAH TIMUR</option>
                    <option value="FRAM SABAH">FRAM SABAH</option>
                    <option value="FRAM SARAWAK">FRAM SARAWAK</option>
                </select>
            </div>

            <!-- Peranan -->
            <div>
                <label for="role">Peranan</label>
                <select id="role" name="role" required>
                    <option value="admin_negeri"> Penyelia Negeri</option>
                </select>
            </div>

            <button type="submit" class="auth-button">Daftar</button>

            <div class="auth-links">
                <p>Sudah ada akaun? <a href="{{ route('login') }}">Log Masuk</a></p>
            </div>
        </form>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");
        const togglePasswordConfirmation = document.getElementById("togglePasswordConfirmation");
        const passwordConfirmationInput = document.getElementById("password_confirmation");

        togglePassword.addEventListener("click", function () {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;

            const icon = document.getElementById("eye-icon");
            if (type === "password") {
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        });

        togglePasswordConfirmation.addEventListener("click", function () {
            const type = passwordConfirmationInput.type === "password" ? "text" : "password";
            passwordConfirmationInput.type = type;

            const icon = document.getElementById("eye-icon-confirmation");
            if (type === "password") {
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        });

        // Show the modal
        const modal = document.getElementById("successModal");
        const btn = document.getElementById("redirectToLogin");
        const closeBtn = document.getElementsByClassName("close")[0];

        // Show modal after page load
        modal.style.display = "block";

        // When the user clicks the "OK" button, close the modal and redirect to login
        btn.onclick = function() {
            modal.style.display = "none";
            window.location.href = "{{ route('login') }}";
        }

        // When the user clicks the close button, close the modal
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</x-guest-layout>
