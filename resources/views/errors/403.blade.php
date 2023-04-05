@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body text-center">
                        <h2>{{ __('messages.forbidden') }}</h2>
                        <p>{{ __('messages.forbidden_text') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
