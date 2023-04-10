<div>
    <select class="form-select" wire:model="city_id" wire:change="setCity($event.target.value)">
        <option selected disabled>{{ __('messages.choose_city') }}</option>
        @foreach(\App\Models\City::all() as $city)
            @if (app()->getLocale() == "ru")
                <option value="{{ $city->id }}">{{ $city->name_ru}}</option>
            @else
                <option value="{{ $city->id }}">{{ $city->name_en}}</option>
            @endif
        @endforeach
    </select>
</div>

