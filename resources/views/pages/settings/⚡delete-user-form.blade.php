<?php

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $password = '';
    public bool $showModal = false;

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="bg-white dark:bg-zinc-800 rounded-xl border border-red-200 dark:border-red-900/50 p-6">
    <div class="mb-5">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Delete account') }}</h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Delete your account and all of its resources') }}</p>
    </div>

    <button
        type="button"
        x-data
        @click="$wire.showModal = true"
        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition-colors"
        data-test="delete-user-button"
    >
        {{ __('Delete account') }}
    </button>

    <!-- Delete Confirmation Modal -->
    <div
        x-data="{ open: @entangle('showModal') }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
                class="fixed inset-0 bg-slate-500/75 dark:bg-zinc-900/75 transition-opacity"
            ></div>

            <!-- Modal panel -->
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            >
                <form wire:submit="deleteUser" class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                            {{ __('Are you sure you want to delete your account?') }}
                        </h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label for="delete-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('Password') }}
                        </label>
                        <input
                            id="delete-password"
                            wire:model="password"
                            type="password"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors"
                        />
                        @error('password')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="open = false"
                            class="px-4 py-2 border border-slate-300 dark:border-zinc-600 text-slate-700 dark:text-slate-300 font-medium rounded-md hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors"
                        >
                            {{ __('Cancel') }}
                        </button>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition-colors"
                            data-test="confirm-delete-user-button"
                        >
                            {{ __('Delete account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
