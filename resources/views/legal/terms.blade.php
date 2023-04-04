@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('messages.terms_of_service') }}</div>

                    <div class="card-body">
                        <h3>{{ __('terms.introduction') }}</h3>
                        <p>{{ __('terms.introduction_text') }}</p>

                        <h3>{{ __('terms.usage_restrictions') }}</h3>
                        <p>{{ __('terms.usage_restrictions_text') }}</p>

                        <h3>{{ __('terms.intellectual_property') }}</h3>
                        <p>{{ __('terms.intellectual_property_text') }}</p>

                        <h3>{{ __('terms.liability_limitation') }}</h3>
                        <p>{{ __('terms.liability_limitation_text') }}</p>

                        <h3>{{ __('terms.confidentiality') }}</h3>
                        <p>{{ __('terms.confidentiality_text') }}</p>

                        <h3>{{ __('terms.final_provisions') }}</h3>
                        <p>{{ __('terms.final_provisions_text') }}</p>

                        <p>{{ __('terms.invalid_provisions') }}</p>
                        <p>{{ __('terms.invalid_provisions_text') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
