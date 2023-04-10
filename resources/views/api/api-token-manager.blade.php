<div>
    <!-- Generate API Token -->
    <x-form-section submit="createApiToken">
        <x-slot name="title">
            {{ __('api-tokens.create_api_token') }}
        </x-slot>

        <x-slot name="description">
            {{ __('api-tokens.api_tokens_allow') }}
        </x-slot>

        <x-slot name="form">
            <!-- Имя токена -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('api-tokens.token_name') }}"/>
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="createApiTokenForm.name"
                         autofocus/>
                <x-input-error for="name" class="mt-2"/>
            </div>

            <!-- Разрешения токена -->
            @if (Laravel\Jetstream\Jetstream::hasPermissions())
                <div class="col-span-6">
                    <x-label for="permissions" value="{{ __('api-tokens.permissions') }}"/>

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                            <label class="flex items-center">
                                <x-checkbox wire:model.defer="createApiTokenForm.permissions" :value="$permission"/>
                                <span class="ml-2 text-sm text-gray-600">{{ $permission }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="created">
                {{ __('api-tokens.created') }}
            </x-action-message>

            <x-button>
                {{ __('api-tokens.create') }}
            </x-button>
        </x-slot>
    </x-form-section>
    @if ($this->user->tokens->isNotEmpty())
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <h2>{{ __('api-tokens.manage_api_tokens') }}</h2>
                    <p>{{ __('api-tokens.api_tokens_allow') }}</p>

                    <!-- Список API-токенов -->
                    <div class="mt-3">
                        @foreach ($this->user->tokens->sortBy('name') as $token)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-break">
                                    {{ $token->name }}
                                </div>

                                <div class="d-flex align-items-center">
                                    @if ($token->last_used_at)
                                        <div class="text-muted small">
                                            {{ __('api-tokens.last_used') }} {{ $token->last_used_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <div class="text-muted small">
                                            {{ __('api-tokens.never_used') }}
                                        </div>
                                    @endif

                                    @if (Laravel\Jetstream\Jetstream::hasPermissions())
                                        <button class="btn btn-link text-decoration-underline p-0 ms-3 small"
                                                wire:click="manageApiTokenPermissions({{ $token->id }})">
                                            {{ __('api-tokens.permissions') }}
                                        </button>
                                    @endif
                                    <button class="btn btn-link text-danger p-0 ms-3 small"
                                            wire:click="confirmApiTokenDeletion({{ $token->id }})">
                                        {{ __('api-tokens.delete') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Модальное окно API-токена -->
    <div class="modal{{ $displayingToken ? ' show d-block' : '' }}" tabindex="-1" wire:model="displayingToken">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('api-tokens.api_token') }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('displayingToken', false)"
                            wire:loading.attr="disabled"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('api-tokens.api_text') }}</p>
                    <input x-ref="plaintextToken" type="text" readonly wire:model.defer="plainTextToken"
                           class="mt-4 bg-light px-4 py-2 rounded font-mono text-sm text-muted w-100 text-break"
                           autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                           @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('displayingToken', false)"
                            wire:loading.attr="disabled">{{ __('api-tokens.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно разрешений API-токена -->
    <div class="modal{{ $managingApiTokenPermissions ? ' show d-block' : '' }}" tabindex="-1"
         wire:model="managingApiTokenPermissions">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('api-tokens.api_token_permissions') }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('managingApiTokenPermissions', false)"
                            wire:loading.attr="disabled"></button>
                </div>
                <div class="modal-body">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                            <div class="col">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="{{ $permission }}"
                                           wire:model="updateApiTokenForm.permissions" value="{{ $permission }}">
                                    <label class="form-check-label" for="{{ $permission }}">{{ $permission }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            wire:click="$set('managingApiTokenPermissions', false)"
                            wire:loading.attr="disabled">{{ __('api-tokens.cancel') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="updateApiToken"
                            wire:loading.attr="disabled">{{ __('api-tokens.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления токена -->
    <div class="modal{{ $confirmingApiTokenDeletion ? ' show d-block' : '' }}" tabindex="-1"
         wire:model="confirmingApiTokenDeletion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('api-tokens.delete_api_token') }}</h5>
                    <button type="button" class="btn-close" wire:click="$toggle('confirmingApiTokenDeletion')"
                            wire:loading.attr="disabled"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('api-tokens.confirm_delete_token') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingApiTokenDeletion')"
                            wire:loading.attr="disabled">{{ __('api-tokens.cancel') }}</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteApiToken"
                            wire:loading.attr="disabled">{{ __('api-tokens.delete') }}</button>
                </div>
            </div>
        </div>
    </div>

</div>
