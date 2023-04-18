@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="text-success mt-3">
                {{ session()->get('message') }}
            </div>
        @endif
        <h1>Мои заказы</h1>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Сумма</th>
                <th scope="col">Статус</th>
                <th scope="col">Дата создания</th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orders as $order)
                <tr>
                    <th scope="row">{{ $order->id }}</th>
                    <td>{{ $order->sum }}</td>
                    <td>{{ $order->status->name }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        @if ($order->status->name == 'active' || $order->status->name == 'created' || $order->status->name == 'in_progress' || $order->status->name == 'ready_for_delivery')
                            @if ($order->status->name == 'created')
                                <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}" class="btn btn-primary">Перейти к подтверждению</a>
                            @endif
                            <a href="{{ route('order.cancel', ['orderId' => $order->id]) }}" class="btn btn-danger" onclick="return confirm('{{ ($order->status->name == 'created' || $order->status->name == 'active') ? 'Внимание: Отмена заказа может привести к снижению вашего рейтинга. Рейтинг уменьшается при отмене заказов в статусе \'in_progress\' и \'ready_for_delivery\'. Убедитесь, что вы хотите отменить заказ перед продолжением.' : ($order->status->name == 'in_progress' ? 'Внимание: Отмена заказа приведет к снижению вашего рейтинга на 0.2, учитывая количество отмен в этом месяце. Вы уверены, что хотите отменить заказ?' : 'Внимание: Отмена заказа приведет к снижению вашего рейтинга на 0.4, учитывая количество отмен в этом месяце. Вы уверены, что хотите отменить заказ?') }}')">Отменить заказ</a>
                        @endif

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
