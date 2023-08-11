@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
{{--                <div class="col-md-3">--}}
{{--                    <a href="{{ route('translate.generate', ['sourceLanguage' => 'ru']) }}" class="btn btn-primary form-control disabled">Сгенерировать языковые пакеты</a>--}}
{{--                </div>--}}
            <div class="col-md-4 my-2">
                <a href="{{ route('problem.list_unresolved') }}" class="btn btn-warning form-control">Проблемы с заказом</a>
            </div>
            <div class="col-md-4 my-2">
                <a href="{{ route('admin.conversations') }}" class="btn btn-primary form-control">Обращения на сайте</a>
            </div>
            <div class="col-md-4 my-2">
                <a href="{{ route('admin.statistics') }}" class="btn btn-success form-control">Статистика по заказам</a>
            </div>
        </div>
    </div>
@endsection
