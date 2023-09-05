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
                <th>Фото</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($problemData as $data)
                <tr>
                    <th scope="row">{{$data['problem_id']}}</th>
                    <th scope="row"
                        style="background-image: url('/images/{{ pathinfo($data['order']->celebration->image, PATHINFO_FILENAME)}}_small.webp');  background-size: cover; background-position: center; text-shadow: 3px 3px 4px rgba(2, 2, 2, 0.7);"
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
                        @if ($data['images'])
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#imageModal{{$data['problem_id']}}">
                                Посмотреть фото
                            </button>
                            <div class="modal fade" id="imageModal{{$data['problem_id']}}" tabindex="-1"
                                 aria-labelledby="imageModalLabel{{$data['problem_id']}}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel{{$data['problem_id']}}">Фото
                                                проблемы №{{$data['problem_id']}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Закрыть"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                @forelse ($data['images'] as $image)
                                                    <div class="col-2">
                                                        <a href="{{ $image }}" target="_blank">
                                                            <img src="{{ $image }}" alt="Фото" class="img-thumbnail">
                                                        </a>
                                                    </div>
                                                @empty
                                                    <p>Фотографий нет</p>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Закрыть
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if (!$data['resolved'])
                            <form action="{{ route('problem.resolve', $data['problem_id']) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control" required
                                           placeholder="Введите комментарий">
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
