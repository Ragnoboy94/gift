@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('translate.generate', ['sourceLanguage' => 'ru']) }}" class="btn btn-primary">Сгенерировать языковые пакет</a>
                </div>
        </div>
    </div>
@endsection
