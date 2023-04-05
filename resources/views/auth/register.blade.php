<x-guest-layout>
    <x-navbar></x-navbar>
    <x-authentication-card>
        <ul class="nav nav-tabs" id="authTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">{{ __('auth.register') }}</a>
        </li>
        </ul>
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('auth.name') }}</label>
                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}"  required autofocus autocomplete="name">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}"  required autocomplete="username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('auth.password') }}</label>
                <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('auth.confirm_password') }}</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-3 form-check">

                    <input type="checkbox" name="terms" id="terms" class="form-check-input" required>
                    <label class="form-check-label ms-2" for="terms">
                        {!! __('messages.i_agree_to_terms_and_privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.terms_of_service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.privacy_policy').'</a>',
                        ]) !!}
                    </label>
                </div>
            @endif

            <div class="d-flex justify-content-between mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 me-3" href="{{ route('login') }}">
                    {{ __('auth.already_registered') }}
                </a>

                <button type="submit" class="btn btn-primary">
                    {{ __('auth.register') }}
                </button>
            </div>
        </form>
    </x-authentication-card>
    <x-footer></x-footer>
</x-guest-layout>
