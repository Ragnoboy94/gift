@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('messages.privacy_policy') }}</div>
                    <div class="card-body">
                        <h3>{{ __('privacy_policy.intro') }}</h3>
                        <p>{{ __('privacy_policy.introduction') }}</p>
                        <h3>{{ __('privacy_policy.data_collection') }}</h3>
                        <p>{{ __('privacy_policy.collect_info_desc') }}</p>

                        <h3>{{ __('privacy_policy.data_usage') }}</h3>
                        <p>{{ __('privacy_policy.use_info_desc') }}</p>

                        <h3>{{ __('privacy_policy.data_disclosure') }}</h3>
                        <p>{{ __('privacy_policy.disclose_info_desc') }}</p>

                        <h3>{{ __('privacy_policy.data_security') }}</h3>
                        <p>{{ __('privacy_policy.data_security_desc') }}</p>

                        <h3>{{ __('privacy_policy.data_transfers') }}</h3>
                        <p>{{ __('privacy_policy.data_transfers_desc') }}</p>

                        <h3>{{ __('privacy_policy.user_rights') }}</h3>
                        <p>{{ __('privacy_policy.access_rights') }}</p>

                        <h3>{{ __('privacy_policy.policy_changes') }}</h3>
                        <p>{{ __('privacy_policy.changes') }}</p>

                        <h3>{{ __('privacy_policy.contact_info') }}</h3>
                        <p>{{ __('privacy_policy.contact') }}</p>

                        <p>{{ __('privacy_policy.company_name') }}<br>
                            {{ __('privacy_policy.address') }}<br>
                            {{ __('privacy_policy.city_zip') }}<br>
                            {{ __('privacy_policy.country') }}<br>
                            {{ __('privacy_policy.phone') }}<br>
                            {{ __('privacy_policy.email') }}</p>

                        <p>{{ __('privacy_policy.response') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
