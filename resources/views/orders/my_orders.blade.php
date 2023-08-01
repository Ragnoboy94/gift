@extends('layouts.app')

@section('content')
    <div class="container">
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
        <h1>Мои заказы</h1>
        <!-- Карточки для мобильных устройств -->
        <div class="d-md-none">
            @foreach ($orders as $order)
                <div class="card mb-3 text-white"
                     style="background-image: url('images/{{ pathinfo($order->celebration->image, PATHINFO_FILENAME)}}_small.webp'); background-size: cover; background-position: center; text-shadow: 1px 1px 2px rgb(0, 0, 0,1);">
                    <div class="card-body">
                        <h5 class="card-title">№ заказа: {{ $order->order_number }}</h5>
                        <p class="card-text">
                            <b>Сумма:</b> {{ $order->sum }} {{ $order->sum_rubles }}<br>
                            <b>Подарок:</b> {{ round($order->sum_work) }} {{ $order->sum_work_rubles }}<br>
                            <b>Эльфу:</b> {{ round($order->sum_elf) }} {{ $order->sum_elf_rubles }}<br>
                            <b>Статус:</b> {{ $order->status->display_name }}<br>
                            <b>Дата создания:</b> {{ $order->created_at }}<br>
                            <b>Срок выполнения:</b> {{ $order->deadline }}
                        </p>
                        @if ($order->status->name == 'active' || $order->status->name == 'created' || $order->status->name == 'in_progress' || $order->status->name == 'ready_for_delivery' || $order->status->name == 'cancelled_by_elf')
                            @if ($order->status->name == 'created')
                                <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}"
                                   class="btn btn-primary">Перейти к подтверждению</a>
                            @elseif ($order->status->name == 'cancelled_by_elf')
                                <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}"
                                   class="btn btn-primary">Повторить заказ</a>
                            @elseif ($order->status->name == 'ready_for_delivery')
                                <a href="{{ route('chat.show', ['orderId' => $order->id]) }}"
                                   class="btn btn-primary">Открыть чат</a>
                            @endif
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#cardcancelOrderModal-{{ $order->id }}">
                                Отменить заказ
                            </button>

                        @endif
                    </div>
                </div>
                <div class="modal fade" id="cardcancelOrderModal-{{ $order->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="cardcancelOrderModalLabel-{{ $order->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cardcancelOrderModalLabel-{{ $order->id }}">Отмена
                                    заказа</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body  text-black">
                                @if($order->status->name == 'created' || $order->status->name == 'active')
                                    Внимание: Отмена заказа может привести к снижению вашего рейтинга. Рейтинг
                                    уменьшается при отмене заказов в статусе 'В процессе' и 'Готов к доставке'.
                                    Убедитесь, что вы хотите отменить заказ перед продолжением.
                                @elseif($order->status->name == 'in_progress')
                                    Внимание: Отмена заказа приведет к снижению вашего рейтинга на 0.2, учитывая
                                    количество отмен в этом месяце. Вы уверены, что хотите отменить заказ?
                                @else
                                    Внимание: Отмена заказа приведет к снижению вашего рейтинга на 0.4, учитывая
                                    количество отмен в этом месяце. Вы уверены, что хотите отменить заказ?
                                @endif
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('order.cancel', ['orderId' => $order->id]) }}" class="btn btn-danger">Подтвердить
                                    отмену</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-none d-md-block table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">№ заказа</th>
                    <th scope="col">Сумма</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Срок выполнения</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <th scope="row"
                            style="background-image: url('images/{{ pathinfo($order->celebration->image, PATHINFO_FILENAME)}}_small.webp'); background-size: cover; background-position: center; text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);"
                            class="text-center text-white">{{ $order->order_number }}</th>
                        <td>
                            {{ $order->sum }} {{ $order->sum_rubles }}<br>
                            <span class="d-none d-lg-inline">
        <b>Подарок:</b> {{ round($order->sum_work) }} {{ $order->sum_work_rubles }}<br>
        <b>Эльфу:</b> {{ round($order->sum_elf) }} {{ $order->sum_elf_rubles }}
    </span>
                        </td>

                        <td>{{ $order->status->display_name }}</td>
                        <td><input type="datetime-local" class="form-control" value="{{ $order->created_at }}" readonly>
                        </td>
                        <td><input type="date" class="form-control" value="{{ $order->deadline }}" readonly></td>
                        <td>
                            @if ($order->status->name == 'active' || $order->status->name == 'created' || $order->status->name == 'in_progress' || $order->status->name == 'ready_for_delivery' || $order->status->name == 'cancelled_by_elf')
                                @if ($order->status->name == 'created')
                                    <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}"
                                       class="btn btn-primary">Перейти к подтверждению</a>
                                @elseif ($order->status->name == 'cancelled_by_elf')
                                    <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}"
                                       class="btn btn-primary">Повторить заказ</a>
                                @elseif ($order->status->name == 'ready_for_delivery')
                                    <a href="{{ route('chat.show', ['orderId' => $order->id]) }}"
                                       class="btn btn-primary">Открыть чат</a>
                                @endif
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#cancelOrderModal-{{ $order->id }}">
                                    Отменить заказ
                                </button>

                            @endif
                        </td>
                    </tr>
                    <div class="modal fade" id="cancelOrderModal-{{ $order->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="cancelOrderModalLabel-{{ $order->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelOrderModalLabel-{{ $order->id }}">Отмена
                                        заказа</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-black">
                                    @if($order->status->name == 'created' || $order->status->name == 'active')
                                        Внимание: Отмена заказа может привести к снижению вашего рейтинга. Рейтинг
                                        уменьшается при отмене заказов в статусе 'В процессе' и 'Готов к доставке'.
                                        Убедитесь, что вы хотите отменить заказ перед продолжением.
                                    @elseif($order->status->name == 'cancelled_by_elf')
                                        Жаль, что вы отменяете заказ, но мы обязаны уточнить. Вы уверены, что хотите отменить заказ?
                                    @elseif($order->status->name == 'in_progress')
                                        Внимание: Отмена заказа приведет к снижению вашего рейтинга на 0.2, учитывая
                                        количество отмен в этом месяце. Вы уверены, что хотите отменить заказ?
                                    @else
                                        Внимание: Отмена заказа приведет к снижению вашего рейтинга на 0.4, учитывая
                                        количество отмен в этом месяце. Вы уверены, что хотите отменить заказ?
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('order.cancel', ['orderId' => $order->id]) }}"
                                       class="btn btn-danger">Подтвердить отмену</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
