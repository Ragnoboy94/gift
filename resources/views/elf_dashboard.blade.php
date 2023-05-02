@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Дела для эльфа</h1>
                @if(session()->has('message'))
                    <div class="text-success mt-3">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if (count($orders) > 0)
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1>Активные заказы</h1>
                            <div class="row">
                                @foreach($orders as $order)
                                    <div class="col-md-6 col-lg-4 my-3">
                                        <div class="card"
                                             style="background-image: url('images/{{ pathinfo($order->celebration->image, PATHINFO_FILENAME)}}_small.jpg'); background-size: cover; background-position: center;">
                                            <div class="card-body" style="background-color: rgba(0, 0, 0, 0.5);">
                                                <h5 class="card-title text-white"
                                                    style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);"><b>Заказ
                                                        ID: {{ $order->order_number }}</b></h5>
                                                <div class="card-text text-white lead"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);"><b>Сумма на
                                                        подарок: {{ round($order->sum_work) }} <span class="rublesText"
                                                                                                     data-sum="{{ $order->sum_work }}"></span></b>
                                                </div>
                                                <div class="card-text text-white"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);"><b>За
                                                        работу: {{ round($order->sum_elf) }} <span class="rublesText"
                                                                                                   data-sum="{{ $order->sum_elf }}"></span></b>
                                                </div>
                                                <div class="card-text text-white lead"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);">
                                                    <b>Праздник: {{ $order->celebration->name }}</b></div>
                                                <div class="card-text text-white"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);"><b>Данные:
                                                        Заказ для @if($order->gender == 'male')
                                                            мужчины
                                                        @else
                                                            женщины
                                                        @endif. {{ $order->hobby }}</b></div>
                                                @if($order->status->name == 'cancelled_by_customer')
                                                    <div class="tooltip-container bg-primary text-white">
                                                        Статус заказа: {{ $order->status->display_name }}
                                                        <div class="tooltip-text" data-tooltip>Мы сожалеем, что клиент отменил заказ. Его рейтинг понижен. Свяжитесь с нами и предоставьте фотофиксацию чеков и подарка в разложенном виде. Постараемся компенсировать ваши траты.</div>
                                                    </div>
                                                @elseif($order->status->name == 'finished')
                                                    <div class="tooltip-container bg-primary text-white">
                                                        Статус заказа: {{ $order->status->display_name }}
                                                        <div class="tooltip-text" data-tooltip>Спасибо, что завершили заказ. Ваш рейтинг увеличился!</div>
                                                    </div>
                                                @else
                                                    <a href="{{ route('send-order-ready', ['orderId' => $order->id]) }}"
                                                       class="btn btn-primary update-order mb-2"
                                                       onclick="return confirm('Вы уверены, что заказ собран и готов к отправке? Отлично! Пожалуйста, учтите, что если заказ будет отменен после подтверждения, ваш рейтинг может снизиться на 0.4. Продолжаем радовать людей!')">
                                                        Заказ собран
                                                    </a>
                                                    <br>
                                                    <a href="{{ route('elf.cancel', ['orderId' => $order->id]) }}" class="btn btn-danger"
                                                       onclick="return confirm('Отмена заказа приведет к снижению вашего рейтинга на 0.2 в этом месяце. Помните, что ваша работа эльфа заключается в радости, которую вы приносите людям. Вы уверены, что хотите отменить заказ?')">Отменить заказ</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <h2>Доступные задачи</h2>
                    <div class="col-md-7">
                        <div id="map" style="width: 100%; height: 70vh;"></div>
                    </div>
                    <div class="col-md-5">
                        <div id="orderInfo">
                            <div id="ordersContainer">
                                <!-- Здесь будет отображаться список доступных заказов -->
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

                const rublesTextElements = document.querySelectorAll('.rublesText');

                rublesTextElements.forEach((element) => {
                    const sum = parseInt(element.getAttribute('data-sum'));
                    element.innerText = pluralizeRubles(sum);
                });

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

                                        price.innerHTML = `Сумма заказа: ${Math.round(giftsAmount)} ${pluralizeRubles(Math.round(giftsAmount))}<br><p>За работу: ${Math.round(feeAmount)} ${pluralizeRubles(Math.round(feeAmount))}</p>`;
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
                                        const takeOrderBtn = document.createElement('button');
                                        takeOrderBtn.classList.add('btn', 'btn-primary', 'mt-3');
                                        takeOrderBtn.setAttribute('type', 'button');
                                        takeOrderBtn.setAttribute('data-bs-toggle', 'modal');
                                        takeOrderBtn.setAttribute('data-bs-target', `#order-${order.id}-modal`);
                                        takeOrderBtn.textContent = 'Взять в работу';
                                        colInfo.appendChild(takeOrderBtn);
                                        ordersContainer.appendChild(orderCard);
                                        const orderModal = document.createElement('div');
                                        orderModal.classList.add('modal', 'fade');
                                        orderModal.id = `order-${order.id}-modal`;
                                        orderModal.setAttribute('tabindex', '-1');
                                        orderModal.setAttribute('aria-labelledby', `order-${order.id}-modalLabel`);
                                        orderModal.setAttribute('aria-hidden', 'true');
                                        ordersContainer.appendChild(orderModal);

                                        const modalDialog = document.createElement('div');
                                        modalDialog.classList.add('modal-dialog');
                                        orderModal.appendChild(modalDialog);

                                        const modalContent = document.createElement('div');
                                        modalContent.classList.add('modal-content');
                                        modalDialog.appendChild(modalContent);

                                        const modalHeader = document.createElement('div');
                                        modalHeader.classList.add('modal-header');
                                        modalContent.appendChild(modalHeader);

                                        const modalTitle = document.createElement('h5');
                                        modalTitle.classList.add('modal-title');
                                        modalTitle.id = `order-${order.id}-modalLabel`;
                                        modalTitle.textContent = 'Подтверждение';
                                        modalHeader.appendChild(modalTitle);

                                        const modalCloseBtn = document.createElement('button');
                                        modalCloseBtn.classList.add('btn-close');
                                        modalCloseBtn.setAttribute('type', 'button');
                                        modalCloseBtn.setAttribute('data-bs-dismiss', 'modal');
                                        modalCloseBtn.setAttribute('aria-label', 'Close');
                                        modalHeader.appendChild(modalCloseBtn);

                                        const modalBody = document.createElement('div');
                                        modalBody.classList.add('modal-body');
                                        modalBody.innerHTML = `Взяв заказ, вы подтверждаете, что в случае отказа от него по своим причинам, ваш рейтинг будет снижен. Ожидается выполнение заказа вовремя, и сумма подарка должна быть равна или незначительно меньше объявленной. Также все покупки должны иметь подтверждение в виде чека. Оформление остается на ваше усмотрение и может быть включено в стоимость набора при предъявлении чека. Все, что сделано сверх данного, будет расцениваться как добрая воля, и требование за него плату с заказчика будет отражено на рейтинге.`;
                                        modalContent.appendChild(modalBody);

                                        const modalFooter = document.createElement('div');
                                        modalFooter.classList.add('modal-footer');
                                        modalContent.appendChild(modalFooter);

                                        const confirmBtn = document.createElement('a');
                                        confirmBtn.classList.add('btn', 'btn-primary');
                                        confirmBtn.setAttribute('href', `/elf/take-order/${order.id}`);
                                        confirmBtn.textContent = 'Подтвердить';
                                        modalFooter.appendChild(confirmBtn);

                                        const cancelBtn = document.createElement('button');
                                        cancelBtn.classList.add('btn', 'btn-secondary');
                                        cancelBtn.setAttribute('type', 'button');
                                        cancelBtn.setAttribute('data-bs-dismiss', 'modal');
                                        cancelBtn.textContent = 'Отмена';
                                        modalFooter.appendChild(cancelBtn);
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
