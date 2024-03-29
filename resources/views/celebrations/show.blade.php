@extends('layouts.app')

@section('content')
    <div class="container">
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
        <div class="row">
            @if($celebration)
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
                                <label for="sum-{{ $celebration['id'] }}">{{ __('messages.budget') }}</label>
                                <input type="number" name="sum" id="sum-{{ $celebration['id'] }}"
                                       class="form-control" placeholder="1000" required>
                                <div id="orderDetails-{{ $celebration['id'] }}"></div>
                            </div>
                            @if ($celebration['id'] !== 6)
                                <div class="form-group">
                                    <label for="gender">{{ __('messages.select_gender') }}</label>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="male">{{ __('messages.male') }}</option>
                                        <option value="female">{{ __('messages.female') }}</option>
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="hobby">{{ __('messages.hobby') }}</label>
                                <textarea name="hobby" id="hobby" class="form-control" placeholder="{{__('app.hobby_example')}}"></textarea>
                            </div>
                            <button type="submit"
                                    class="btn btn-primary mt-1">{{ __('messages.order_button') }}</button>
                        </form>

                    @else
                        <a href="{{ route('login') }}"
                           class="btn btn-primary">{{ __('messages.inter_order') }}</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>

@endsection
