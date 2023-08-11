@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Проблемы с заказами</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Заказ</th>
                <th>Описание</th>
                <th>Стоимость</th>
                <th>Дата создания заказа и выявления проблемы</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($problemData as $data)
                <tr>
                    <th scope="row" style="background-image: url('/images/{{ pathinfo($data['order']->celebration->image, PATHINFO_FILENAME)}}_small.webp');  background-size: cover; background-position: center; text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);"
                        class="text-center text-white">
                        {{ $data['order']->id }}</br>{{ $data['order']->user->name }}
                    </th>
                    <td>{{ $data['description'] }}</td>
                    <td>{{ $data['sum'] }}</td>
                    <td>
                        {{ $data['orderCreatedAt'] }}<br>
                        {{ $data['problemCreatedAt'] }}<br>
                        {{ $data['time'] }} {{ $data['variation'] }}
                    </td>
                    <td>
                        @if (!$data['order']->resolved)
                            <form action="{{ route('problem.resolve', $data['order']->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Пометить как решенную</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
