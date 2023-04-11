@extends('layouts.app')

@section('content')
    <div class="container">

        @section('content')
            <div class="container">
                <div class="row">
                    @if($celebration)
                        <div class="col-md-6">
                            <img
                                src="{{ asset('images/' . $celebration['image']) }}"
                                srcset="{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_small.jpg') }} 320w,
{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_medium.jpg') }} 768w,
{{ asset('images/' . pathinfo($celebration['image'], PATHINFO_FILENAME) . '_large.jpg') }} 1200w"
                                sizes="(max-width: 767px) 320px,
   (max-width: 1199px) 768px,
   1200px"
                                alt="{{ $celebration['name'] }}"
                                class="img-fluid"
                            >
                        </div>
                        <div class="col-md-6">
                            <p class="lead">{{ $celebration['description'] }}</p>
                            <p><b>{{ __('messages.plus') }}:</b></p>
                            <ul>
                                @foreach ($celebration['benefits'] as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                            @if (Auth::check())
                                <form method="POST" action="{{ route('order.create', ['celebration' => $celebration['id']]) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="sum">{{ __('messages.budget') }}</label>
                                        <input type="number" name="sum" id="sum" class="form-control" required>
                                    </div>

                                    @if ($celebration['name'] !== '8 марта')
                                        <div class="form-group">
                                            <label for="gender">{{ __('messages.select_gender') }}</label>
                                            <select name="gender" id="gender" class="form-control" required>
                                                <option value="male">{{ __('messages.male') }}</option>
                                                <option value="female">{{ __('messages.female') }}</option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="hobby">{{ __('messages.hobby') }}</label>
                                        <textarea name="hobby" id="hobby" class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-1">{{ __('messages.order_button') }}</button>
                                </form>

                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.inter_order') }}</a>
                            @endif
                        </div>
                </div>
                @else
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>{{ __('messages.celebration_not_found') }}</h2>
                                <p>{{ __('messages.celebration_not_found_description') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endsection
    </div>
@endsection
