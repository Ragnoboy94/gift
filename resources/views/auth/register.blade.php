<x-guest-layout>
    <x-navbar></x-navbar>
    <x-authentication-card>
        <div class="row mt-3">
            <div class="col-md-12 lead">Вход и регистрация через:</div>
            <div class="col-md-6 my-3">
                <a href="{{ route('auth.vk') }}" class="btn btn-primary form-control">
                    <img src="{{ asset('images/vk.png') }}" alt="ВКонтакте" width="16" height="16" class="me-2 mb-1">ВКонтакте
                </a>
            </div>
            <div class="col-md-6 my-3">
                <a href="{{ route('auth.yandex') }}" class="btn btn-primary form-control">
                    <img src="{{ asset('images/yandex.png') }}" alt="Яндекс" width="16" height="16" class="me-2 mb-1">Яндекс
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

        <div class="accordion" id="authAccordions">
            <div class="accordion-item">
                <h2 class="accordion-header" id="loginHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#login" aria-expanded="true" aria-controls="login">
                        {{ __('auth.login') }}
                    </button>
                </h2>
                <div id="login" class="accordion-collapse collapse" aria-labelledby="loginHeading" data-bs-parent="#authAccordions">
                    <div class="accordion-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required
                                       autofocus autocomplete="username">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('auth.password') }}</label>
                                <input id="password" class="form-control" type="password" name="password" required
                                       autocomplete="current-password">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                                <label class="form-check-label" for="remember_me">{{ __('auth.remember_me') }}</label>
                            </div>

                            <div class="d-flex justify-content-between">
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 me-3"
                                       href="{{ route('password.request') }}">
                                        {{ __('auth.forgot_password') }}
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-primary">
                                    {{ __('auth.login') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="registerHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#register" aria-expanded="false" aria-controls="register">
                        {{ __('auth.register') }}
                    </button>
                </h2>
                <div id="register" class="accordion-collapse collapse show" aria-labelledby="registerHeading" data-bs-parent="#authAccordions">
                    <div class="accordion-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('auth.name') }}</label>
                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required
                                       autofocus autocomplete="name">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required
                                       autocomplete="username">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('auth.password') }}</label>
                                <input id="password" class="form-control" type="password" name="password" required
                                       autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('auth.confirm_password') }}</label>
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation"
                                       required autocomplete="new-password">
                            </div>

                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                <div class="mb-3 form-check">

                                    <input type="checkbox" name="terms" id="terms" class="form-check-input" required>
                                    <label class="form-check-label ms-2" for="terms">
                                        {!! __('messages.i_agree_to_terms_and_privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms1.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.terms_of_service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.privacy_policy').'</a>',
                                        ]) !!}
                                    </label>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 me-3"
                                   href="{{ route('login') }}">
                                    {{ __('auth.already_registered') }}
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    {{ __('auth.register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-authentication-card>
    <x-footer></x-footer>
</x-guest-layout>
