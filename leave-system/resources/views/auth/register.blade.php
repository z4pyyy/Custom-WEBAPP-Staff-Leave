<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ showPass: false, showConfirm: false }">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password"
                            class="block mt-1 w-full pr-10"
                            x-bind:type="showPass ? 'text' : 'password'"
                            name="password"
                            required autocomplete="new-password" />

                <button type="button"
                        class="absolute inset-y-0 right-0 px-3 text-sm text-gray-600 focus:outline-none"
                        @click="showPass = !showPass">
                    <span x-show="!showPass">Show</span>
                    <span x-show="showPass">Hide</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <!-- Password Requirements -->
            <div class="mt-2 text-sm text-gray-600">
                Password must be at least 8 characters, include uppercase, lowercase and a number.
            </div>

            <!-- Password Strength Indicator   -->
            <div class="mt-2 text-sm" id="password-strength-text" style="color: red;"></div>


        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="password_confirmation"
                            class="block mt-1 w-full pr-10"
                            x-bind:type="showConfirm ? 'text' : 'password'"
                            name="password_confirmation"
                            required autocomplete="new-password" />

                <button type="button"
                        class="absolute inset-y-0 right-0 px-3 text-sm text-gray-600 focus:outline-none"
                        @click="showConfirm = !showConfirm">
                    <span x-show="!showConfirm">Show</span>
                    <span x-show="showConfirm">Hide</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>


        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmInput = document.querySelector('input[name="password_confirmation"]');
            const strengthText = document.getElementById('password-strength-text');
            const submitButton = document.querySelector('button[type="submit"]');

            // 🔧 建立提示文字顯示區域
            let matchText = document.createElement('div');
            matchText.classList.add('mt-1', 'text-sm');
            confirmInput.parentNode.appendChild(matchText);

            function validatePassword(val) {
                const hasUpper = /[A-Z]/.test(val);
                const hasLower = /[a-z]/.test(val);
                const hasNumber = /\d/.test(val);
                const longEnough = val.length >= 8;
                return hasUpper && hasLower && hasNumber && longEnough;
            }

            function updateValidation() {
                const passwordVal = passwordInput.value;
                const confirmVal = confirmInput.value;

                // 密碼強度
                let strength = 'Weak';
                let color = 'red';
                let valid = false;

                if (validatePassword(passwordVal)) {
                    strength = 'Strong';
                    color = 'green';
                    valid = true;
                } else if (passwordVal.length >= 6) {
                    strength = 'Medium';
                    color = 'orange';
                }

                strengthText.textContent = `Password strength: ${strength}`;
                strengthText.style.color = color;

                // 密碼一致性檢查
                if (confirmVal && passwordVal !== confirmVal) {
                    matchText.textContent = 'Passwords do not match';
                    matchText.style.color = 'red';
                    submitButton.disabled = true;
                } else {
                    matchText.textContent = '';
                    if (valid && passwordVal === confirmVal) {
                        submitButton.disabled = false;
                    } else {
                        submitButton.disabled = true;
                    }
                }
            }

            passwordInput.addEventListener('input', updateValidation);
            confirmInput.addEventListener('input', updateValidation);
        });
        </script>

    </form>
</x-guest-layout>
