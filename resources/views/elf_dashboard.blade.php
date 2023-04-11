@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Дела для эльфа</h1>
                <div class="row">
                    <div class="col-md-7">
                        <div id="map" style="width: 100%; height: 70vh;"></div>
                    </div>
                    <div class="col-md-5">
                        <div id="orderInfo">
                            <!-- Здесь будет информация о заказах -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Подключаем API карт, например, Яндекс.Карты -->
    <script src="https://api-maps.yandex.ru/2.1/?apikey=470ab6bb-6d83-4388-8f3d-248d94a6a16f&lang=ru_RU" type="text/javascript"></script>
    <script>
        ymaps.ready(init);

        function init() {
            // Создаем карту
            ymaps.geocode('{{$city_name->name_ru}}')
                .then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);
                    var map = new ymaps.Map("map", {
                        center: firstGeoObject.geometry.getCoordinates(),
                        zoom: 10,
                        controls: ['zoomControl'],
                    });
                    map.options.set('suppressMapOpenBlock', true);
                });

            // Добавляем метки заказов на карту
            @foreach ($orders as $order)
            var placemark = new ymaps.Placemark([{{ $order->latitude }}, {{ $order->longitude }}]);
            map.geoObjects.add(placemark);
            placemark.events.add('click', function() {
                showOrderInfo({{ $order->id }});
            });
            @endforeach
        }

        function showOrderInfo(orderId) {
            // Запрос информации о заказе и отображение справа от карты
        }
    </script>
    @endpush
@endsection
