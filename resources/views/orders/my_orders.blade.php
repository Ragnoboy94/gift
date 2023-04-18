@extends('layouts.app')

@section('content')
    <div class="container">
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
                        @if ($order->status->name == 'created')
                            <a href="{{ route('order.confirmation', ['orderId' => $order->id]) }}" class="btn btn-primary">Перейти к подтверждению</a>
                            <button type="button" class="btn btn-danger">Отмена</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
