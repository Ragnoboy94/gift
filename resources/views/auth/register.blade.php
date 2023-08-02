<x-guest-layout>
    <x-navbar></x-navbar>
    <x-authentication-card>
        <div class="row mt-3">
            <div class="col-md-12 lead">{{__('messages.enter_register')}}:</div>
            <div class="col-md-6 my-3">
                <a id="vkButton" href="{{ route('auth.vk') }}" class="btn btn-primary form-control">
                    <img src="{{ asset('images/vk.png') }}" alt="{{ __('messages.vk') }}" width="16" height="16" class="me-2 mb-1">{{ __('messages.vk') }}
                </a>
            </div>
            <div class="col-md-6 my-3">
                <a id="yandexButton" href="{{ route('auth.yandex') }}" class="btn btn-primary form-control">
                    <img src="{{ asset('images/yandex.png') }}" alt="{{ __('messages.yandex') }}" width="16" height="16" class="me-2 mb-1">{{ __('messages.yandex') }}
                </a>
            </div>

        </div>

        @if ($errors->any())
            <div {{ $attributes }}>

                <ul class="mt-3 list-unstyled text-sm text-danger">
                    @foreach ($errors->all() as $error)
                        <li class="ms-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $error }}
                        </li>
                    @endforeach
                </ul>

            </div>
        @endif

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="form-check row mx-0 my-2">
                <input type="checkbox" id="agreeTerms" class="form-check col-1" style="margin-left: -25px;margin-bottom: -2.675rem;">
                <label for="agreeTerms" class="form-check-label blockquote-footer text-muted">
                    {!! __('messages.i_agree_to_terms_and_privacy_policy', [
                            'terms_of_service_d' => '<a target="_blank" href="'.route('terms1.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.terms_of_service_d').'</a>',
                            'privacy_policy_d' => '<a target="_blank" href="'.route('policy.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.privacy_policy_d').'</a>',
                    ]) !!}
                </label>
            </div>
        @endif
        <script>
            const agreeTermsCheckbox = document.getElementById('agreeTerms');
            const vkButton = document.getElementById('vkButton');
            const yandexButton = document.getElementById('yandexButton');
            const vkRoute = '{{ route('auth.vk') }}';
            const yandexRoute = '{{ route('auth.yandex') }}';
            function checkAgreeTerms() {
                if (agreeTermsCheckbox.checked) {
                    vkButton.classList.remove('disabled');
                    yandexButton.classList.remove('disabled');
                } else {
                    vkButton.classList.add('disabled');
                    yandexButton.classList.add('disabled');
                }
            }

            checkAgreeTerms();
            agreeTermsCheckbox.addEventListener('change', checkAgreeTerms);
        </script>

    </x-authentication-card>
    <x-footer></x-footer>
</x-guest-layout>
