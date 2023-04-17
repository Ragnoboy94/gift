@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1>{{ __('messages.hello') }}</h1>
                    <p>{{ __('messages.welcome_text') }}</p>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert text-danger text-center">
                    <ul class="list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                @foreach ($celebrations_3 as $key => $celebration)
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
                                                <form method="POST" action="{{ route('order.create', ['celebration' => $key]) }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="sum-{{ $key }}">{{ __('messages.budget') }}</label>
                                                        <input type="number" name="sum" id="sum-{{ $key }}" class="form-control" required>
                                                        <div id="orderDetails-{{ $key }}"></div>
                                                    </div>

                                                    @if ($celebration['name'] !== '8 марта')
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
                                                        <textarea name="hobby" id="hobby" class="form-control"></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-1">{{ __('messages.order_button') }}</button>
                                                </form>

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
        const rating = {{ Auth::user()->role_user->where('role_id', 1)->first()->rating }};
        const ratingLevel = Math.floor(rating);
        document.querySelectorAll("[id^='sum-']").forEach((sumInput) => {
            sumInput.addEventListener("input", (event) => {
                const totalAmount = parseFloat(event.target.value);
                const key = event.target.id.split("-")[1];
                const orderDetails = document.getElementById("orderDetails-" + key);

                const maxOrderAmount = getMaxOrderAmount(ratingLevel);

                if (totalAmount < 700) {
                    orderDetails.innerHTML = "<span class='text-danger'>Сумма должна быть не менее 700 рублей</span";
                    return;
                } else if (totalAmount > maxOrderAmount) {
                    orderDetails.innerHTML = `<span class='text-danger'>Ваш уровень не позволяет заказывать на суммы выше ${maxOrderAmount} рублей</span>`;
                    return;
                } else if (totalAmount >= 700) {
                    const feeAmount = 200 + ((totalAmount - 625) / 100 * 15);
                    const giftsAmount = totalAmount - feeAmount;

                    orderDetails.innerHTML = `Сумма на подарки: <span class="lead">${Math.round(giftsAmount)}</span> рублей<br>
Вознаграждение исполнителя: <span class="lead">${Math.round(feeAmount)}</span> рублей`;
                } else {
                    orderDetails.innerHTML = "";
                    return;
                }
            });
        });
    </script>
@endsection
