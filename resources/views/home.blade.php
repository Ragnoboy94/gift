@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1>{{ __('messages.hello') }}</h1>
                </div>
            </div>
            @php
                $ordersCount = app('App\Http\Controllers\OrderController')->getActiveOrdersCount();
            @endphp
            @if ($errors->any())
                <div class="alert text-danger text-center">
                    <ul class="list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('message'))
                <div class="alert alert-info text-center">
                    {{ session('message') }}
                </div>
            @endif
            <div id="carouselExampleIndicators" class="carousel carousel-dark slide" >
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{asset('images/ord-slide.webp')}}"
                             srcset="{{asset('images/ord-slide-large.webp')}} 768w, {{asset('images/ord-slide.webp')}} 1920w"
                             class="d-block w-100" alt="{{__('trans.ord_slide')}}">
                        <div class="carousel-caption d-none d-lg-block text-white">
                            <h2 class="lead">{{__('trans.ord_slide')}}</h2>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{asset('images/elf-slide.webp')}}"
                             srcset="{{asset('images/elf-slide-large.webp')}} 768w, {{asset('images/elf-slide.webp')}} 1920w"
                             class="d-block w-100" alt="{{__('trans.eld_slide')}}">
                        <div class="carousel-caption d-none d-lg-block text-white">
                            <h2 class="lead">{{__('trans.eld_slide')}}</h2>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{asset('images/gift-slide.webp')}}"
                             srcset="{{asset('images/gift-slide-large.webp')}} 768w, {{asset('images/gift-slide.webp')}} 1920w"
                             class="d-block w-100" alt="{{__('trans.gift_slide')}}">
                        <div class="carousel-caption d-none d-lg-block text-white">
                            <h2 class="lead">{{__('trans.gift_slide')}}</h2>
                        </div>
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <div class="row mt-2">
                @foreach ($celebrations_3 as $key => $celebration)
                    <div class="col-md-4">
                        <div class="card mb-4" data-bs-toggle="modal" data-bs-target="#celebrationModal{{$key}}">
                            <img
                                src="{{ asset('images/' . $celebration['image']) }}"
                                srcset="{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_small.webp') }} 320w,
            {{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_medium.webp') }} 768w,
            {{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_large.webp') }} 1200w"
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

                    <div class="modal fade" id="celebrationModal{{$key}}" tabindex="-1"
                         aria-labelledby="celebrationModal{{$key}}Label" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="celebrationModal{{$key}}Label">{{ $celebration['name'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img
                                                src="{{ asset('images/' . $celebration['image']) }}"
                                                srcset="{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_small.webp') }} 320w,
{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_medium.webp') }} 768w,
{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_large.webp') }} 1200w"
                                                sizes="(max-width: 767px) 320px,
   (max-width: 1199px) 768px,
   1200px"
                                                alt="{{ $celebration['name'] }}"
                                                class="img-fluid"
                                            >
                                            <p class="lead">{{ $celebration['description'] }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><b>{{ __('messages.plus') }}:</b></p>
                                            <ul>
                                                @foreach ($celebration['benefits'] as $benefit)
                                                    <li>{{ $benefit }}</li>
                                                @endforeach
                                            </ul>
                                            @if (Auth::check())
                                                <form method="POST"
                                                      action="{{ route('order.create', ['celebration' => $celebration['id']]) }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="sum-{{ $key }}">{{ __('messages.budget') }}</label>
                                                        <input type="number" name="sum" id="sum-{{ $key }}"
                                                               class="form-control" placeholder="1000" required>
                                                        <div id="orderDetails-{{ $key }}"></div>
                                                    </div>

                                                    @if ($celebration['id'] !== 6)
                                                        <div class="form-group">
                                                            <label
                                                                for="gender">{{ __('messages.select_gender') }}</label>
                                                            <select name="gender" id="gender" class="form-control"
                                                                    required>
                                                                <option value="male">{{ __('messages.male') }}</option>
                                                                <option
                                                                    value="female">{{ __('messages.female') }}</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="hobby">{{ __('messages.hobby') }}</label>
                                                        <textarea name="hobby" id="hobby" class="form-control"
                                                                  placeholder="{{__('app.hobby_example')}}"></textarea>
                                                    </div>
                                                    @if ($ordersCount < 3)
                                                        <button type="submit"
                                                                class="btn btn-primary mt-1">{{ __('messages.order_button') }}</button>
                                                    @else
                                                        <p class="text-danger">{{ __('messages.order_limit_reached') }}</p>
                                                    @endif
                                                </form>

                                            @else
                                                <a href="{{ route('login') }}"
                                                   class="btn btn-primary">{{ __('messages.inter_order') }}</a>
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
    <script>
        function getMaxOrderAmount(ratingLevel) {
            switch (ratingLevel) {
                case 1:
                    return 1000;
                case 2:
                    return 3000;
                case 3:
                    return 6000;
                case 4:
                    return 9000;
                case 5:
                default:
                    return Infinity;
            }
        }
        @if(Auth::check())
        const rating = {{ Auth::user()->role_user->where('role_id', 1)->first()->rating }};
        const ratingLevel = Math.floor(rating);
        @endif
        document.querySelectorAll("[id^='sum-']").forEach((sumInput) => {
            sumInput.addEventListener("input", (event) => {
                const totalAmount = parseFloat(event.target.value);
                const key = event.target.id.split("-")[1];
                const orderDetails = document.getElementById("orderDetails-" + key);

                const maxOrderAmount = getMaxOrderAmount(ratingLevel);

                if (totalAmount < 700) {
                    orderDetails.innerHTML = "<span class='text-danger'>{{__('trans.summa700')}}</span";
                    return;
                } else if (totalAmount > maxOrderAmount) {
                    orderDetails.innerHTML = `<span class='text-danger'>{{__('trans.summabig1')}} ${maxOrderAmount} {{__('modal.rubles')}}</span>`;
                    return;
                } else if (totalAmount >= 700) {
                    const feeAmount = 200 + ((totalAmount - 625) / 100 * 15);
                    const giftsAmount = totalAmount - feeAmount;

                    orderDetails.innerHTML = `{{__('trans.summa_gift')}}: <span class="lead">${Math.round(giftsAmount)}</span> {{__('modal.rubles')}}<br>
{{__('trans.money_elf')}}: <span class="lead">${Math.round(feeAmount)}</span> {{__('modal.rubles')}}`;
                } else {
                    orderDetails.innerHTML = "";
                    return;
                }
            });
        });
    </script>
@endsection
