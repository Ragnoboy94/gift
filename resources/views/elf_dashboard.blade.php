@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>{{__('app.work_elf')}}</h1>
                @if(session()->has('message'))
                    <div class="text-success mt-3">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert text-danger text-center">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (count($orders) > 0)
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1>{{__('trans.active_order')}}</h1>
                            <div class="row">
                                @foreach($orders as $order)
                                    <div class="col-md-6 col-lg-4 my-3">
                                        <div class="card"
                                             style="background-image: url('images/{{ pathinfo($order->celebration->image, PATHINFO_FILENAME)}}_small.webp'); background-size: cover; background-position: center;">
                                            <div class="card-body" style="background-color: rgba(0, 0, 0, 0.5);">
                                                <h5 class="card-title text-white"
                                                    style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);">
                                                    <b>{{__('trans.order')}}
                                                        ID: {{ $order->order_number }}</b></h5>
                                                <div class="card-text text-white lead"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);">
                                                    <b>{{__('trans.summa_gift1')}}: {{ round($order->sum_work) }} <span
                                                            class="rublesText"
                                                            data-sum="{{ $order->sum_work }}"></span></b>
                                                </div>
                                                <div class="card-text text-white"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);">
                                                    <b>{{__('trans.for_work')}}: {{ round($order->sum_elf) }} <span
                                                            class="rublesText"
                                                            data-sum="{{ $order->sum_elf }}"></span></b>
                                                </div>
                                                <div class="card-text text-white lead"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);">
                                                    <b>{{__('trans.celebr')}}: {{ $order->celebration->name }}</b></div>
                                                <div class="card-text text-white"
                                                     style="text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);">
                                                    <b>{{__('trans.data')}}:
                                                        {{__('trans.order_for')}} @if($order->gender == 'male')
                                                            {{__('trans.man')}}
                                                        @else
                                                            {{__('trans.woman')}}
                                                        @endif. {{ $order->hobby }}</b></div>
                                                @if($order->status->name == 'cancelled_by_customer')
                                                    <div class="tooltip-container bg-primary text-white">
                                                        {{__('trans.status_order')}}: {{ $order->status->display_name }}
                                                        <div class="tooltip-text"
                                                             data-tooltip>{{__('trans.status_text1')}}
                                                        </div>
                                                    </div>
                                                @elseif($order->status->name == 'finished')
                                                    <div class="tooltip-container bg-primary text-white">
                                                        {{__('trans.status_order')}}: {{ $order->status->display_name }}
                                                        <div class="tooltip-text"
                                                             data-tooltip>{{__('trans.status_text2')}}
                                                        </div>
                                                    </div>
                                                    @if($order->status->name == 'finished' && !$order->paid &&
                                                    Auth::user()->id == $order->elf_id)
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <button type="button"
                                                                        class="btn btn-success my-2  form-control"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#orderConfirmationModal">{{__('trans.money_here')}}</button>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <button type="button"
                                                                        class="btn btn-warning my-2 form-control"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#orderproblemModal">{{__('new.problem')}}</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="modal fade" id="orderConfirmationModal"
                                                         tabindex="-1"
                                                         aria-labelledby="orderConfirmationModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="orderConfirmationModalLabel">
                                                                        {{__('trans.yes_money')}}</h5>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                <form method="POST"
                                                                      action="{{ route('orders.mark_as_paid', $order->id) }}">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        {{__('trans.status_text3')}}
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-primary">
                                                                            {{__('trans.yes_order')}}
                                                                        </button>
                                                                        <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">{{__('api-tokens.close')}}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="orderproblemModal"
                                                         tabindex="-1"
                                                         aria-labelledby="orderproblemModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="orderproblemModalLabel">
                                                                        {{__('new.problem')}}</h5>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                <form method="POST" action="{{ route('order-problem.store', $order->id) }}">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        {{__('new.problem_text')}}
                                                                        <div class="form-group">
                                                                            <label for="problemDescription"><b>{{__('order.problem_desc')}}</b></label>
                                                                            <textarea class="form-control" id="problemDescription" required name="description" placeholder="{{__('new.problem')}}" rows="3"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-primary">
                                                                            {{__('order.send_problem')}}
                                                                        </button>
                                                                        <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">{{__('api-tokens.close')}}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($order->status->name == 'ready_for_delivery')
                                                    <a href="{{ route('chat.show', ['orderId' => $order->id]) }}"
                                                       class="btn btn-primary mb-2">
                                                        {{__('trans.connect')}}
                                                    </a>
                                                @else
                                                    <a href="{{ route('send-order-ready', ['orderId' => $order->id]) }}"
                                                       class="btn btn-primary update-order mb-2"
                                                       onclick="return confirm('{{__('trans.order_done_text1')}}')">
                                                        {{__('trans.order_done')}}
                                                    </a>
                                                    <br>
                                                    <a href="{{ route('elf.cancel', ['orderId' => $order->id]) }}"
                                                       class="btn btn-danger"
                                                       onclick="return confirm('{{__('trans.order_done_text2')}}')">{{__('trans.cancel_order')}}</a>
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
                    <h2>{{__('trans.available')}}</h2>
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
        <script src="https://api-maps.yandex.ru/2.1/?apikey=470ab6bb-6d83-4388-8f3d-248d94a6a16f&lang=ru_RU"
                type="text/javascript"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function pluralizeRubles(number) {
                    const remainder100 = number % 100;
                    const remainder10 = number % 10;

                    if (remainder100 >= 11 && remainder100 <= 19) {
                        return '{{__('trans.rubles')}}';
                    } else if (remainder10 === 1) {
                        return '{{__('trans.ruble')}}';
                    } else if (remainder10 >= 2 && remainder10 <= 4) {
                        return '{{__('trans.rublya')}}';
                    } else {
                        return '{{__('trans.rubles')}}';
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
                                if (e.get('target') === map) {
                                    showOrdersForCity(map.getCenter());
                                }
                            });
                        });

                }

                function showOrdersForCity(centerCoords) {
                    const ordersContainer = document.getElementById('ordersContainer');
                    ordersContainer.innerHTML = '';
                    map.geoObjects.removeAll();

                    ymaps.geocode(centerCoords, {kind: 'locality'}).then(function (res) {
                        const city = res.geoObjects.get(0);

                        if (city) {
                            const cityName = city.properties.get('name');

                            fetch(`/get-orders-by-city/${cityName}`)
                                .then(response => response.json())
                                .then(orders => {
                                    orders.forEach(function (order) {
                                        addOrderPlacemark(order.address, order.id, map);
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
                                        image.src = 'images/' + order.celebration.image.replace(/\.[^/.]+$/, "") + '_small.webp';
                                        image.alt = 'Order Image';
                                        image.classList.add('img-fluid', 'd-none', 'd-md-block');
                                        colImage.appendChild(image);

                                        const price = document.createElement('p');
                                        const feeAmount = 200 + ((`${order.sum}` - 625) / 100 * 15);
                                        const giftsAmount = `${order.sum}` - feeAmount;

                                        price.innerHTML = `{{__('trans.summa_order')}}: ${Math.round(giftsAmount)} ${pluralizeRubles(Math.round(giftsAmount))}<br><p>{{__('trans.for_work')}}: ${Math.round(feeAmount)} ${pluralizeRubles(Math.round(feeAmount))}</p>`;
                                        colImage.appendChild(price);

                                        const colInfo = document.createElement('div');
                                        colInfo.classList.add('col-md-8');
                                        row.appendChild(colInfo);

                                        const listGroup = document.createElement('ul');
                                        listGroup.classList.add('list-group', 'lead', 'ms-3');
                                        colInfo.appendChild(listGroup);

                                        const listItemGender = document.createElement('li');
                                        listItemGender.classList.add('list-group-item');
                                        listItemGender.textContent = `{{__('trans.for_why')}}: ${order.gender}`;
                                        listGroup.appendChild(listItemGender);

                                        const listItemHobby = document.createElement('li');
                                        listItemHobby.classList.add('list-group-item');
                                        listItemHobby.textContent = `{{__('trans.his_hobby')}}: ${order.hobby}`.replace('&#039;', "'");
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
                                        takeOrderBtn.textContent = '{{__('trans.take_work')}}';
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
                                        modalTitle.textContent = '{{__('trans.confirm')}}';
                                        modalHeader.appendChild(modalTitle);

                                        const modalCloseBtn = document.createElement('button');
                                        modalCloseBtn.classList.add('btn-close');
                                        modalCloseBtn.setAttribute('type', 'button');
                                        modalCloseBtn.setAttribute('data-bs-dismiss', 'modal');
                                        modalCloseBtn.setAttribute('aria-label', 'Close');
                                        modalHeader.appendChild(modalCloseBtn);

                                        const modalBody = document.createElement('div');
                                        modalBody.classList.add('modal-body');
                                        modalBody.innerHTML = `{{__('trans.confirm_text')}}`;
                                        modalContent.appendChild(modalBody);

                                        const modalFooter = document.createElement('div');
                                        modalFooter.classList.add('modal-footer');
                                        modalContent.appendChild(modalFooter);

                                        const confirmBtn = document.createElement('a');
                                        confirmBtn.classList.add('btn', 'btn-primary');
                                        confirmBtn.setAttribute('href', `/elf/take-order/${order.id}`);
                                        confirmBtn.textContent = '{{__('app.confirm')}}';
                                        modalFooter.appendChild(confirmBtn);

                                        const cancelBtn = document.createElement('button');
                                        cancelBtn.classList.add('btn', 'btn-secondary');
                                        cancelBtn.setAttribute('type', 'button');
                                        cancelBtn.setAttribute('data-bs-dismiss', 'modal');
                                        cancelBtn.textContent = '{{__('session.cancel')}}';
                                        modalFooter.appendChild(cancelBtn);
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
