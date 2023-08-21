@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Проблемы с заказами</h2>
        <table class="table">
            <thead>
            <tr>
                <th>№ проблемы</th>
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
                    <th scope="row">{{$data['problem_id']}}</th>
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
                        @if (!$data['resolved'])
                            <form action="{{ route('problem.resolve', $data['problem_id']) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control" required placeholder="Введите комментарий">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success">Пометить как решенную</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div>
                                <p>Комментарий: {{ $data['comments'] }}</p>
                                <p>Решено пользователем: {{ $data['resolvedBy'] }}</p>
                                <p>Дата решения: {{ $data['resolvedAt'] }}</p>
                            </div>
                        @endif

                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
