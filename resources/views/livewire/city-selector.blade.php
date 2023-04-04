<div>
    <select class="form-select" wire:model="city" wire:change="setCity($event.target.value)">
        <option selected disabled>{{ __('messages.choose_city') }}</option>
        <option value="moscow">{{ __('messages.moscow') }}</option>
        <option value="saint_petersburg">{{ __('messages.saint_petersburg') }}</option>
        <option value="irkutsk">{{ __('messages.irkutsk') }}</option>
    </select>
</div>
