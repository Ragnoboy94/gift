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

@if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
    <div class="row mx-0">
        <label class="blockquote-footer text-muted">
            {!! __('messages.i_agree_to_terms_and_privacy_policy', [
                    'terms_of_service_d' => '<a target="_blank" href="'.route('terms1.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.terms_of_service_d').'</a>',
                    'privacy_policy_d' => '<a target="_blank" href="'.route('policy.show').'" class="text-decoration-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('messages.privacy_policy_d').'</a>',
            ]) !!}
        </label>
    </div>
@endif

