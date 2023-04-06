@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('messages.order_confirmation') }}</h1>
        <div class="row">
            <div class="col-md-6">
                <p>{{ __('messages.order_summary') }}:</p>
                <ul>
                    <li>{{ __('messages.budget1') }}: {{ $order->sum }}</li>
                    <li>{{ __('messages.gender1') }}: {{ __('messages.' . $order->gender) }}</li>
                    <li>{{ __('messages.hobby1') }}: {{ $order->hobby }}</li>
                </ul>
                <form method="POST" action="{{ route('order.confirm', ['orderId' => $order->id]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="address">{{ __('messages.address') }}</label>
                        <input type="text" name="address" id="address" class="form-control" required>
                    </div>
                    <div id="map" style="width: 100%; height: 400px;"></div>
                    <button type="submit" class="btn btn-primary mt-1">{{ __('messages.proceed_to_payment') }}</button>
                </form>
            </div>
            <div class="col-md-6">
                <picture>
                    <source media="(min-width: 768px)"
                            srcset="{{ asset('images/' . pathinfo($celebration->image, PATHINFO_FILENAME) . '_medium.jpg') }} 768w,
            {{ asset('images/' . pathinfo($celebration->image, PATHINFO_FILENAME) . '_large.jpg') }} 1200w"
                            sizes="(max-width: 1199px) 768px,
            1200px"
                    >
                    <img
                        src="{{ asset('images/' . $celebration->image) }}"
                        alt="{{ $celebration->name }}"
                        class="img-fluid d-none d-md-block"
                    >
                </picture>
                <p>{{ $celebration->description }}</p>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://api-maps.yandex.ru/2.1/?apikey=470ab6bb-6d83-4388-8f3d-248d94a6a16f&lang=ru_RU" type="text/javascript"></script>
        <script type="text/javascript">
            ymaps.ready(init);

            function init() {
                const addressInput = document.getElementById('address');
                const defaultCoords = [55.753215, 37.622504]; // Москва, Кремль

                const map = new ymaps.Map('map', {
                    center: defaultCoords,
                    zoom: 14,
                    controls: ['zoomControl'],
                });
                map.options.set('suppressMapOpenBlock', true);
                const placemark = new ymaps.Placemark(defaultCoords, {}, {
                    draggable: true
                });
                map.geoObjects.add(placemark);

                addressInput.addEventListener('change', async () => {
                    const geocode = await ymaps.geocode(addressInput.value);
                    const coords = geocode.geoObjects.get(0).geometry.getCoordinates();
                    placemark.geometry.setCoordinates(coords);
                    map.setCenter(coords);
                });

                placemark.events.add('dragend', async () => {
                    const coords = placemark.geometry.getCoordinates();
                    const geocode = await ymaps.geocode(coords);
                    const address = geocode.geoObjects.get(0).properties.get('text');
                    addressInput.value = address;
                    map.setCenter(coords);
                });

                map.events.add('click', async (e) => {
                    const coords = e.get('coords');
                    const geocode = await ymaps.geocode(coords);
                    const nearest = geocode.geoObjects.get(0);
                    const nearestCoords = nearest.geometry.getCoordinates();
                    const address = nearest.properties.get('text');
                    placemark.geometry.setCoordinates(nearestCoords);
                    addressInput.value = address;
                    map.setCenter(nearestCoords);
                });
            }
        </script>
    @endpush

@endsection
