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
                            <div id="ordersContainer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Подключаем API карт, например, Яндекс.Карты -->
        <script src="https://api-maps.yandex.ru/2.1/?apikey=470ab6bb-6d83-4388-8f3d-248d94a6a16f&lang=ru_RU"
                type="text/javascript"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function pluralizeRubles(number) {
                    const remainder100 = number % 100;
                    const remainder10 = number % 10;

                    if (remainder100 >= 11 && remainder100 <= 19) {
                        return 'рублей';
                    } else if (remainder10 === 1) {
                        return 'рубль';
                    } else if (remainder10 >= 2 && remainder10 <= 4) {
                        return 'рубля';
                    } else {
                        return 'рублей';
                    }
                }
                let map;

                ymaps.ready(init);

                function init() {
                    // Создаем карту
                    ymaps.geocode('{{$city_name->name_ru}}')
                        .then(function (res) {
                            var firstGeoObject = res.geoObjects.get(0);
                            map = new ymaps.Map("map", {
                                center: firstGeoObject.geometry.getCoordinates(),
                                zoom: 10,
                                controls: ['zoomControl'],
                            });
                            map.options.set('suppressMapOpenBlock', true);

                            showOrdersForCity(map.getCenter());

                            map.events.add('actionend', debounce(function () {
                                showOrdersForCity(map.getCenter());
                            }, 300));
                            map.events.add('click', function (e) {
                                // Если клик был на свободной области карты (не на метке), показываем список заказов
                                if (e.get('target') === map) {
                                    showOrdersForCity(map.getCenter());
                                }
                            });
                        });

                }

                function showOrdersForCity(centerCoords) {
                    // Очистите список заказов и метки на карте
                    const ordersContainer = document.getElementById('ordersContainer');
                    ordersContainer.innerHTML = '';
                    map.geoObjects.removeAll();

                    // Ищем город, основываясь на координатах центра карты
                    ymaps.geocode(centerCoords, {kind: 'locality'}).then(function (res) {
                        const city = res.geoObjects.get(0);

                        if (city) {
                            const cityName = city.properties.get('name');

                            // Получаем заказы для найденного города
                            fetch(`/get-orders-by-city/${cityName}`)
                                .then(response => response.json())
                                .then(orders => {
                                    orders.forEach(function (order) {
                                        // Добавляем метки заказов на карту
                                        addOrderPlacemark(order.address, order.id, map);
                                        // Добавляем карточку заказа
                                        const ordersContainer = document.getElementById('ordersContainer');
                                        const orderCard = document.createElement('div');
                                        orderCard.id = `order-${order.id}`;
                                        orderCard.classList.add('card', 'mb-3');

                                        const row = document.createElement('div');
                                        row.classList.add('row', 'g-0');
                                        orderCard.appendChild(row);

                                        const colImage = document.createElement('div');
                                        colImage.classList.add('col-md-4');
                                        row.appendChild(colImage);

                                        const image = document.createElement('img');
                                        image.src = 'images/' + order.celebration.image.replace(/\.[^/.]+$/, "") + '_small.jpg';
                                        image.alt = 'Order Image';
                                        image.classList.add('img-fluid', 'd-none', 'd-md-block');
                                        colImage.appendChild(image);

                                        const price = document.createElement('p');
                                        const feeAmount = 200 + ((`${order.sum}` - 625) / 100 * 15);
                                        const giftsAmount = `${order.sum}` - feeAmount;

                                        price.innerHTML = `Сумма заказа: ${Math.round(giftsAmount)} ${pluralizeRubles(Math.round(giftsAmount))}`;
                                        colImage.appendChild(price);

                                        const colInfo = document.createElement('div');
                                        colInfo.classList.add('col-md-8');
                                        row.appendChild(colInfo);

                                        const listGroup = document.createElement('ul');
                                        listGroup.classList.add('list-group', 'lead', 'ms-3');
                                        colInfo.appendChild(listGroup);

                                        const listItemGender = document.createElement('li');
                                        listItemGender.classList.add('list-group-item');
                                        listItemGender.textContent = `Для кого: ${order.gender}`;
                                        listGroup.appendChild(listItemGender);

                                        const listItemHobby = document.createElement('li');
                                        listItemHobby.classList.add('list-group-item');
                                        listItemHobby.textContent = `Его интересы: ${order.hobby}`;
                                        listGroup.appendChild(listItemHobby);

                                        const cardAddress = document.createElement('p');
                                        cardAddress.classList.add('card-text');
                                        colInfo.appendChild(cardAddress);

                                        const smallText = document.createElement('small');
                                        smallText.classList.add('text-muted');
                                        smallText.textContent = order.address;
                                        cardAddress.appendChild(smallText);

                                        ordersContainer.appendChild(orderCard);

                                        // Назначаем обработчик клика для каждого элемента списка
                                        orderCard.addEventListener('click', () => {
                                            showOrderInfo(order.id);
                                        });
                                    });
                                })
                                .catch(function (error) {
                                    console.error(error);
                                });
                        }
                    });
                }

                async function addOrderPlacemark(address, orderId, map) {
                    const geocode = await ymaps.geocode(address);
                    const coords = geocode.geoObjects.get(0).geometry.getCoordinates();
                    const placemark = new ymaps.Placemark(coords);
                    map.geoObjects.add(placemark);
                    placemark.events.add('click', function () {
                        showOrderInfo(orderId);
                    });
                }

                let activeOrderId = null;

                function showOrderInfo(orderId) {
                    if (activeOrderId) {
                        const prevOrderElement = document.getElementById(`order-${activeOrderId}`);
                        if (prevOrderElement) {
                            prevOrderElement.classList.remove('active');
                        }
                    }

                    const currentOrderElement = document.getElementById(`order-${orderId}`);
                    if (currentOrderElement) {
                        currentOrderElement.classList.add('active');
                        activeOrderId = orderId;

                        // Запрос информации о заказе и отображение справа от карты
                        // Вместо этого вы можете запросить информацию с сервера, если вам нужны дополнительные данные
                        const orderInfoContainer = document.getElementById('ordersContainer');
                        orderInfoContainer.innerHTML = "";
                        for (let i = 0; i < currentOrderElement.children.length; i++) {
                            const child = currentOrderElement.children[i];
                            const newChild = child.cloneNode(true);
                            orderInfoContainer.appendChild(newChild);
                        }
                    }
                }

                function debounce(func, wait, immediate) {
                    let timeout;
                    return function () {
                        const context = this,
                            args = arguments;
                        const later = function () {
                            timeout = null;
                            if (!immediate) func.apply(context, args);
                        };
                        const callNow = immediate && !timeout;
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                        if (callNow) func.apply(context, args);
                    };
                }
            });
        </script>

    @endpush
@endsection
