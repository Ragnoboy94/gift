@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Стать эльфом</h1>
                <h4>Присоединяйтесь к нам и помогайте создавать уникальные и незабываемые подарки для людей по всей
                    России. Ваш талант и креативность помогут сделать каждый праздник особенным и неповторимым!</h4>
                @auth
                    @if (Auth::user()->is_elf)
                        <p>Вы уже являетесь эльфом! Перейдите на вашу панель управления.</p>
                        <a href="{{ route('elf-dashboard') }}" class="btn btn-primary">Панель эльфа</a>
                    @else
                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! \Illuminate\Support\Facades\Auth::user()->hasVerifiedEmail())
                            <p>Для того, чтобы стать эльфом, нужно подтвердить ваш Email</p>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-md"
                                    data-bs-toggle="modal"
                                    data-bs-target="#emailVerificationModal">
                                {{ __('auth.verify_email') }}
                            </button>
                        @else
                            <p>Вы уверены, что хотите стать эльфом и присоединиться к нашей команде?</p>
                            <form method="POST" action="{{ route('become-elf.submit') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Да, хочу стать эльфом</button>
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
