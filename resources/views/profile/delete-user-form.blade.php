<x-action-section>
    <x-slot name="title">
        {{ __('session.delete_account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('session.permanently_delete_account') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('session.account_deletion_warning') }}
        </div>

        <div class="mt-5">
            <button class="btn btn-danger" wire:click="$toggle('confirmingUserDeletion')">
                {{ __('session.delete_account_button') }}
            </button>
        </div>

        @if($confirmingUserDeletion)
            <!-- Delete User Confirmation Modal -->
            <div class="modal d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('session.delete_account') }}</h5>
                            <button type="button" class="btn-close" wire:click="$toggle('confirmingUserDeletion')"></button>
                        </div>
                        <div class="modal-body">
                            {{ __('session.delete_account_confirmation') }}

                            <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                                <input type="password" class="form-control mt-1 w-75"
                                       autocomplete="current-password"
                                       placeholder="{{ __('session.password') }}"
                                       x-ref="password"
                                       wire:model.defer="password"
                                       wire:keydown.enter="deleteUser" />

                                <x-input-error for="password" class="mt-2" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingUserDeletion')">{{ __('session.cancel') }}</button>
                            <button type="button" class="btn btn-danger ml-3" wire:click="deleteUser" wire:loading.attr="disabled">
                                {{ __('session.delete_account_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </x-slot>
</x-action-section>
