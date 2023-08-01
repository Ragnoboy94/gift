@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>{{__('app.be_elf')}}</h1>
                <h4>{{__('app.elf_text')}}</h4>
                @auth
                    @if (!Auth::user()->is_elf)
                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! \Illuminate\Support\Facades\Auth::user()->hasVerifiedEmail())
                            <p>{{__('app.elf_confirm')}}</p>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-md"
                                    data-bs-toggle="modal"
                                    data-bs-target="#emailVerificationModal">
                                {{ __('auth.verify_email') }}
                            </button>
                        @else
                            <p>{{__('app.elf_team_comfirm')}}</p>
                            <form method="POST" action="{{ route('become-elf.submit') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">{{__('app.elf_yes')}}</button>
                            </form>
                        @endif
                    @endif
                @else
                    <p>Чтобы стать эльфом, пожалуйста, зарегистрируйтесь на нашем сайте.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary">Зарегистрироваться</a>
                @endauth
            </div>
        </div>
    </div>
    <!-- Модальное окно подтверждения email -->
    <div class="modal fade" id="emailVerificationModal" tabindex="-1" aria-labelledby="emailVerificationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailVerificationModalLabel">{{ __('auth.verify_email') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('session.cancel') }}"></button>
                </div>
                <div class="modal-body">
                    @livewire('profile.update-profile-information-form')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('session.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
