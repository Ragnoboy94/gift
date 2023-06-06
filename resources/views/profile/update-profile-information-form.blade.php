<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('app.profile_information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('app.update_your_account') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="form-control"
                       wire:model="photo"
                       x-ref="photo"
                       x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('app.photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('app.select_new_photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('app.remove_photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('app.name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('app.email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" disabled wire:model.defer="state.email" autocomplete="username" />
            <x-input-error for="email" class="mt-2" />
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('app.email_unverified') }}

                    <button type="button" class="btn btn-sm btn-outline-primary rounded-md" wire:click.prevent="sendEmailVerification">
                        {{ __('app.resend_verification_email') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        {{ __('app.new_verification_link_sent') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('app.saved') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('app.save') }}
        </x-button>
    </x-slot>
</x-form-section>
<x-action-section>
    <x-slot name="title">
    </x-slot>

    <x-slot name="description">
    </x-slot>

    <x-slot name="content">


        <div class="mt-5">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                {{ __('session.delete_account_button') }}
            </button>
        </div>
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('session.account_deletion_warning') }}
        </div>
        <!-- Confirm Deletion Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">{{ __('session.confirm_deletion_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('session.confirm_deletion_body') }}
                    </div>
                    <div class="modal-footer">
                        <div class="lead text-center text-black" id="del-text"></div>
                        <button type="button" class="btn btn-secondary" id="close-button" data-bs-dismiss="modal">{{ __('session.cancel') }}</button>
                        <button type="button" id="confirm-delete-button" class="btn btn-danger">{{ __('session.confirm_deletion_button') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-action-section>
<script>
    let deleteButton = document.getElementById('confirm-delete-button');
    deleteButton.addEventListener('click', function() {
        deleteButton.remove();
        document.getElementById('close-button').remove();
        document.getElementById('del-text').innerText = "На вашу почту отправляется письмо...";
        fetch('/account/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {


                document.getElementById('del-text').innerText = data.message;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
</script>


