@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <div class="row">
                @foreach ($celebrations as $key => $celebration)
                    <div class="col-md-4">
                        <div class="card mb-4" data-bs-toggle="modal" data-bs-target="#celebrationModal{{$key}}">
                            <img
                                src="{{ asset('images/' . $celebration['image']) }}"
                                srcset="{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_small.jpg') }} 320w,
            {{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_medium.jpg') }} 768w,
            {{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_large.jpg') }} 1200w"
                                sizes="(max-width: 767px) 320px,
           (max-width: 1199px) 768px,
           1200px"
                                alt="{{ $celebration['name'] }}"
                                class="card-img-top"
                            >
                            <div class="card-body">
                                <h5 class="card-title">{{ $celebration['name'] }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Celebration Modal -->
                    <div class="modal fade" id="celebrationModal{{$key}}" tabindex="-1" aria-labelledby="celebrationModal{{$key}}Label" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="celebrationModal{{$key}}Label">{{ $celebration['name'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
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
                                            @if (Auth::check())
                                                <a href="{{ route('order.create', ['celebration' => $key]) }}" class="btn btn-primary">Оформить заказ</a>
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.inter_order') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
