@extends('layouts.app')

@section('content')
    <div class="container">

        @section('content')
            <div class="container">
                <div class="row">
                    @if($celebration)
                        <div class="col-md-6">
                            <img
                                src="{{ asset('images/' . $celebration['image']) }}"
                                srcset="{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_small.jpg') }} 320w,
{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_medium.jpg') }} 768w,
{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_large.jpg') }} 1200w"
                                sizes="(max-width: 767px) 320px,
   (max-width: 1199px) 768px,
   1200px"
                                alt="{{ $celebration['name'] }}"
                                class="img-fluid"
                            >
                        </div>
                        <div class="col-md-6">
                            <p>{{ $celebration['description'] }}</p>
                            <p><b>{{ __('messages.plus') }}:</b></p>
                            <ul>
                                @foreach ($celebration['benefits'] as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                            @if (Auth::check())
                                <a href="{{ route('order.create', ['celebration' => $key]) }}" class="btn btn-primary">Оформить заказ</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.inter_order') }}</a>
                            @endif
                        </div>
                </div>
                @else
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>{{ __('messages.celebration_not_found') }}</h2>
                                <p>{{ __('messages.celebration_not_found_description') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endsection
    </div>
@endsection
