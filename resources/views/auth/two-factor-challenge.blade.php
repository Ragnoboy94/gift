<x-guest-layout>
    <x-navbar></x-navbar>
    <div x-data="{ recovery: false }">
        <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
            {{ __('messages.confirm_access_authenticator_app') }}
        </div>

        <div class="mb-4 text-sm text-gray-600" x-show="recovery">
            {{ __('messages.confirm_access_recovery_codes') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="mt-4" x-show="! recovery">
                <x-label for="code" value="{{ __('messages.code') }}" />
                <x-input id="code" class="block mt-1 w-full" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
            </div>

            <div class="mt-4" x-show="recovery">
                <x-label for="recovery_code" value="{{ __('messages.recovery_code') }}" />
                <x-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                        x-show="! recovery"
                        x-on:click="
                            recovery = true;
                            $nextTick(() => { $refs.recovery_code.focus() })
                        ">
                    {{ __('messages.use_recovery_code') }}
                </button>

                <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                        x-show="recovery"
                        x-on:click="
                            recovery = false;
                            $nextTick(() => { $refs.code.focus() })
                        ">
                    {{ __('messages.use_authentication_code') }}
                </button>

                <x-button class="ml-4">
                    {{ __('messages.log_in') }}
                </x-button>
            </div>
        </form>
    </div>
    <x-footer></x-footer>
</x-guest-layout>
