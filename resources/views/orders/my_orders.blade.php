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
        <h1>{{ __('app.my_orders') }}</h1>

        <div class="d-md-none">
            @foreach ($orders as $order)
                <div class="card mb-3 text-white"
                     style="background-image: url('images/{{ pathinfo($order->celebration->image, PATHINFO_FILENAME)}}_small.webp'); background-size: cover; background-position: center; text-shadow: 1px 1px 2px rgb(0, 0, 0,1);">
                    <div class="card-body">
                        <h5 class="card-title">№ {{__('order.orders')}}: {{ $order->order_number }}</h5>
                        <p class="card-text">
                            <b>{{__('order.summa')}}:</b> {{ $order->sum }} {{ $order->sum_rubles }}<br>
                            <b>{{__('order.gift')}}:</b> {{ round($order->sum_work) }} {{ $order->sum_work_rubles }}<br>
                            <b>{{__('order.elf')}}:</b> {{ round($order->sum_elf) }} {{ $order->sum_elf_rubles }}<br>
                            <b>{{__('order.status')}}:</b> {{ $order->status->display_name }}<br>
                            <b>{{__('order.date_create')}}:</b> {{ $order->created_at }}<br>
                            <b>{{__('order.time_done')}}:</b> {{ $order->deadline }}
                        </p>
                        @if ($order->status->name == 'active' || $order->status->name == 'created' || $order->status->name == 'in_progress' || $order->status->name == 'ready_for_delivery' || $order->status->name == 'cancelled_by_elf')
                            @if ($order->status->name == 'created')
                                <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}"
                                   class="btn btn-primary">{{__('order.go_confirm')}}</a>
                            @elseif ($order->status->name == 'cancelled_by_elf')
                                <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}"
                                   class="btn btn-primary">{{__('order.repeat_order')}}</a>
                            @elseif ($order->status->name == 'ready_for_delivery')
                                <a href="{{ route('chat.show', ['orderId' => $order->id]) }}"
                                   class="btn btn-primary">{{__('order.open_chat')}}</a>
                            @endif
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#cardcancelOrderModal-{{ $order->id }}">
                                {{__('trans.cancel_order')}}
                            </button>

                        @endif
                    </div>
                </div>
                <div class="modal fade" id="cardcancelOrderModal-{{ $order->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="cardcancelOrderModalLabel-{{ $order->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cardcancelOrderModalLabel-{{ $order->id }}">{{__('order.cancel_order')}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body  text-black">
                                @if($order->status->name == 'created' || $order->status->name == 'active')
                                    {{__('order.status_text1')}}
                                @elseif($order->status->name == 'cancelled_by_elf')
                                    {{__('order.status_text4')}}
                                @elseif($order->status->name == 'in_progress')
                                    {{__('order.status_text2')}}
                                @else
                                    {{__('order.status_text3')}}
                                @endif
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('order.cancel', ['orderId' => $order->id]) }}" class="btn btn-danger">{{__('order.confirm_cancel')}}</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('api-tokens.close')}}</button>
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
                    <th scope="col">№ {{__('order.orders')}}</th>
                    <th scope="col">{{__('order.summa')}}</th>
                    <th scope="col">{{__('order.status')}}</th>
                    <th scope="col">{{__('order.date_create')}}</th>
                    <th scope="col">{{__('order.time_done')}}</th>
                    <th scope="col">{{__('order.action')}}</th>
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
        <b>{{__('order.gift')}}:</b> {{ round($order->sum_work) }} {{ $order->sum_work_rubles }}<br>
        <b>{{__('order.elf')}}:</b> {{ round($order->sum_elf) }} {{ $order->sum_elf_rubles }}
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
                                       class="btn btn-primary">{{__('order.go_confirm')}}</a>
                                @elseif ($order->status->name == 'cancelled_by_elf')
                                    <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}"
                                       class="btn btn-primary">{{__('order.repeat_order')}}</a>
                                @elseif ($order->status->name == 'ready_for_delivery')
                                    <a href="{{ route('chat.show', ['orderId' => $order->id]) }}"
                                       class="btn btn-primary">{{__('order.open_chat')}}</a>
                                @endif
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#cancelOrderModal-{{ $order->id }}">
                                    {{__('trans.cancel_order')}}
                                </button>

                            @endif
                        </td>
                    </tr>
                    <div class="modal fade" id="cancelOrderModal-{{ $order->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="cancelOrderModalLabel-{{ $order->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelOrderModalLabel-{{ $order->id }}">{{__('order.cancel_order')}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-black">
                                    @if($order->status->name == 'created' || $order->status->name == 'active')
                                        {{__('order.status_text1')}}
                                    @elseif($order->status->name == 'cancelled_by_elf')
                                        {{__('order.status_text4')}}
                                    @elseif($order->status->name == 'in_progress')
                                        {{__('order.status_text2')}}
                                    @else
                                        {{__('order.status_text3')}}
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('order.cancel', ['orderId' => $order->id]) }}"
                                       class="btn btn-danger">{{__('order.confirm_cancel')}}</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('api-tokens.close')}}
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
