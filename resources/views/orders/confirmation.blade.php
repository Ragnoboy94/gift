@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('messages.order_confirmation') }}</h1>
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4">
                    <picture>
                        <source media="(min-width: 768px)"
                                srcset="{{ asset('images/' . pathinfo($celebration->image, PATHINFO_FILENAME) . '_medium.webp') }} 768w,
            {{ asset('images/' . pathinfo($celebration->image, PATHINFO_FILENAME) . '_large.webp') }} 1200w"
                                sizes="(max-width: 1199px) 768px,
            1200px"
                        >
                        <img
                            src="{{ asset('images/' . $celebration->image) }}"
                            alt="{{ $celebration->name }}"
                            class="img-fluid d-none d-md-block"
                        >
                    </picture>
                </div>
                <div class="col-md-8">
                    <p class="lead ms-3">{{ $celebration->description }}</p>
                    <p class="lead ms-3">{{ __('messages.order_summary') }}:</p>
                    <ul class="lead ms-3">
                        <li>{{ __('messages.budget1') }}: {{ $order->sum }}</li>
                        <li>{{ __('messages.gender1') }}: {{ __('messages.' . $order->gender) }}</li>
                        <li>{{ __('messages.hobby1') }}: {{ $order->hobby }}</li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('order.confirm', ['orderId' => $order->id]) }}"
                      onsubmit="return checkCity()">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="address">{{ __('messages.address') }}</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ $order->address }}" required>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="apartment">{{ __('messages.apartment') }}</label>
                            <input type="text" name="apartment" id="apartment" class="form-control" value="{{ $order->apartment }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="floor">{{ __('messages.floor') }}</label>
                            <input type="number" name="floor" id="floor" class="form-control" value="{{ $order->floor }}">
                        </div>
                    </div>
                    <div class="form-check form-group">
                        <input class="form-check-input" type="checkbox" name="intercom" id="intercom" @if ($order->intercom)
                            checked
                            @endif>
                        <label class="form-check-label" for="intercom">{{ __('messages.intercom') }}</label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">{{ __('messages.phone') }}</label>
                                <input type="text" value="{{$user->phone}}" name="phone" id="phone"
                                       placeholder="В формате +7XXX., 8XXX., 7XXX." class="form-control" required
                                       pattern="[+]?[78]\d{10}">
                            </div>
                            @if ($errors->any())
                                <div class="text-danger">
                                    {{ $errors->first('phone') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date">{{ __('messages.due_date') }}</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" value="{{$order->deadline }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 text-end">
                            <p class="text-muted">* {{ __('messages.no_due_date_info') }}</p>
                        </div>
                    </div>

                    <input type="hidden" name="city" id="city">
                    <div id="map" style="width: 100%; height: 400px;"></div>
                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! \Illuminate\Support\Facades\Auth::user()->hasVerifiedEmail())
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-md" data-bs-toggle="modal"
                                data-bs-target="#emailVerificationModal">
                            {{ __('auth.verify_email') }}
                        </button>
                    @else
                        <button type="submit"
                                class="btn btn-primary mt-1">{{ __('messages.proceed_to_payment') }}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <!-- Модальное окно подтверждения email -->
    <div class="modal fade" id="emailVerificationModal" tabindex="-1" aria-labelledby="emailVerificationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailVerificationModalLabel">{{ __('auth.verify_email') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('session.cancel') }}"></button>
                </div>
                <div class="modal-body">
                    @livewire('profile.update-profile-information-form')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('session.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://api-maps.yandex.ru/2.1/?apikey=470ab6bb-6d83-4388-8f3d-248d94a6a16f&lang=ru_RU"
                type="text/javascript"></script>
        <script type="text/javascript">
            ymaps.ready(init);

            async function getCityFromGeocode(geocode) {
                const locality = geocode.geoObjects.get(0).properties.get('metaDataProperty.GeocoderMetaData.Address.Components').find(component => component.kind === 'locality');
                return locality ? locality.name : null;
            }

            function checkCity() {
                const cityInput = document.getElementById('city');
                if (!cityInput.value) {
                    alert('Убедитесь, что адрес есть на карте!');
                    return false;
                }
                return true;
            }

            function init() {
                const addressInput = document.getElementById('address');
                const cityInput = document.getElementById('city');
                const cityName = '{{$city_name->name_ru}}'; // Введите название города здесь
                const defaultCoords = [55.753215, 37.622504]; // Москва, Кремль

                ymaps.geocode(cityName, {results: 1}).then(async function (res) {
                    const firstGeoObject = res.geoObjects.get(0);
                    const cityCoords = firstGeoObject.geometry.getCoordinates();

                    const map = new ymaps.Map('map', {
                        center: cityCoords,
                        zoom: 14,
                        controls: ['zoomControl'],
                    });
                    map.options.set('suppressMapOpenBlock', true);


                    let placemarkCoords = cityCoords;
                    if (addressInput.value) {
                        const geocode = await ymaps.geocode(addressInput.value);
                        placemarkCoords = geocode.geoObjects.get(0).geometry.getCoordinates();
                        const city = await getCityFromGeocode(geocode);
                        cityInput.value = city; // установка города в скрытое поле
                        map.setCenter(placemarkCoords);
                    }

                    const placemark = new ymaps.Placemark(placemarkCoords, {}, {
                        draggable: true
                    });

                    map.geoObjects.add(placemark);


                    addressInput.addEventListener('change', async () => {
                        const geocode = await ymaps.geocode(addressInput.value);
                        const coords = geocode.geoObjects.get(0).geometry.getCoordinates();
                        const city = await getCityFromGeocode(geocode);
                        document.getElementById('city').value = city;
                        placemark.geometry.setCoordinates(coords);
                        map.setCenter(coords);
                    });

                    placemark.events.add('dragend', async () => {
                        const coords = placemark.geometry.getCoordinates();
                        const geocode = await ymaps.geocode(coords);
                        const address = geocode.geoObjects.get(0).properties.get('text');
                        const city = await getCityFromGeocode(geocode);
                        document.getElementById('city').value = city;
                        addressInput.value = address;
                        map.setCenter(coords);
                    });

                    map.events.add('click', async (e) => {
                        const coords = e.get('coords');
                        const geocode = await ymaps.geocode(coords);
                        const nearest = geocode.geoObjects.get(0);
                        const nearestCoords = nearest.geometry.getCoordinates();
                        const address = nearest.properties.get('text');
                        const city = await getCityFromGeocode(geocode);
                        document.getElementById('city').value = city; // установка города в скрытое поле
                        placemark.geometry.setCoordinates(nearestCoords);
                        addressInput.value = address;
                        map.setCenter(nearestCoords);
                    });
                });
            }

        </script>
    @endpush

@endsection
