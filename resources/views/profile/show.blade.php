@extends('layouts.app')

@section('content')
    <div class="container">


        <div class="container">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                {{ __('app.profile') }}
            </h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-profile-info-tab" data-bs-toggle="pill" href="#v-pills-profile-info" role="tab" aria-controls="v-pills-profile-info" aria-selected="true">{{ __('app.profile_information') }}</a>
                        <a class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" href="#v-pills-password" role="tab" aria-controls="v-pills-password" aria-selected="false">{{ __('app.update_password') }}</a>
                        <a class="nav-link" id="v-pills-two-factor-tab" data-bs-toggle="pill" href="#v-pills-two-factor" role="tab" aria-controls="v-pills-two-factor" aria-selected="false">{{ __('app.two_factor_authentication') }}</a>
                        <a class="nav-link" id="v-pills-logout-tab" data-bs-toggle="pill" href="#v-pills-logout" role="tab" aria-controls="v-pills-logout" aria-selected="false">{{ __('app.logout_other_browser_sessions') }}</a>
                        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                            <a class="nav-link" id="v-pills-delete-tab" data-bs-toggle="pill" href="#v-pills-delete" role="tab" aria-controls="v-pills-delete" aria-selected="false">{{ __('app.delete_account') }}</a>
                        @endif
                    </div>
                </div>
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-profile-info" role="tabpanel" aria-labelledby="v-pills-profile-info-tab">
                        @livewire('profile.update-profile-information-form')
                    </div>
                    <div class="tab-pane fade" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab">
                        @livewire('profile.update-password-form')
                    </div>
                    <div class="tab-pane fade" id="v-pills-two-factor" role="tabpanel" aria-labelledby="v-pills-two-factor-tab">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                    <div class="tab-pane fade" id="v-pills-logout" role="tabpanel" aria-labelledby="v-pills-logout-tab">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>
                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <div class="tab-pane fade" id="v-pills-delete" role="tabpanel" aria-labelledby="v-pills-delete-tab">
                            @livewire('profile.delete-user-form')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
