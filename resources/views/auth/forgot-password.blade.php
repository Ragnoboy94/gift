<x-guest-layout>
    <x-navbar></x-navbar>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('messages.forgot_password_text') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" :value="__('messages.email')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('messages.email_password_reset_link') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
    <x-footer></x-footer>
</x-guest-layout>
