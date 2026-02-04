<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                <div class="text-sm text-red-800 dark:text-red-200">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                    {{ __('Email address') }}
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="w-full px-3 py-2 border border-stone-300 dark:border-stone-600 rounded-md bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:outline-hidden focus:ring-2 focus:ring-accent focus:border-transparent transition-colors"
                />
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        {{ __('Password') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-accent hover:text-accent/80 transition-colors">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="{{ __('Password') }}"
                        class="w-full px-3 py-2 pr-10 border border-stone-300 dark:border-stone-600 rounded-md bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:outline-hidden focus:ring-2 focus:ring-accent focus:border-transparent transition-colors"
                    />
                    <button
                        type="button"
                        onclick="togglePasswordVisibility('password')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 transition-colors"
                        aria-label="Toggle password visibility"
                    >
                        <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="password-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    {{ old('remember') ? 'checked' : '' }}
                    class="w-4 h-4 text-accent border-stone-300 dark:border-stone-600 rounded focus:ring-accent focus:ring-2 focus:ring-offset-0 bg-white dark:bg-stone-800"
                />
                <label for="remember" class="ml-2 block text-sm text-stone-700 dark:text-stone-300">
                    {{ __('Remember me') }}
                </label>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="w-full px-4 py-2 bg-accent text-accent-foreground rounded-md font-medium hover:bg-accent/90 focus:outline-hidden focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-colors" data-test="login-button">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <a href="{{ route('register') }}" class="text-accent hover:text-accent/80 transition-colors">{{ __('Sign up') }}</a>
            </div>
        @endif
    </div>
</x-layouts::auth>

<script>
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    const eyeOff = document.getElementById(inputId + '-eye-off');
    
    if (!input || !eye || !eyeOff) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.add('hidden');
        eyeOff.classList.remove('hidden');
    } else {
        input.type = 'password';
        eye.classList.remove('hidden');
        eyeOff.classList.add('hidden');
    }
}
</script>
