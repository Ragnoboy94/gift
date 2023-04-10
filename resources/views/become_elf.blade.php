@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Стать эльфом</h1>
                <h4>Присоединяйтесь к нам и помогайте создавать уникальные и незабываемые подарки для людей по всей России. Ваш талант и креативность помогут сделать каждый праздник особенным и неповторимым!</h4>
                @auth
                    @if (Auth::user()->is_elf)
                        <p>Вы уже являетесь эльфом! Перейдите на вашу панель управления.</p>
                        <a href="{{ route('elf-dashboard') }}" class="btn btn-primary">Панель эльфа</a>
                    @else
                        <p>Вы уверены, что хотите стать эльфом и присоединиться к нашей команде?</p>
                        <form method="POST" action="{{ route('become-elf.submit') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Да, хочу стать эльфом</button>
                        </form>
                    @endif
                @else
                    <p>Чтобы стать эльфом, пожалуйста, зарегистрируйтесь на нашем сайте.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary">Зарегистрироваться</a>
                @endauth
            </div>
        </div>
    </div>
@endsection
