@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-profile-info" role="tabpanel"
                             aria-labelledby="v-pills-profile-info-tab">
                            @livewire('profile.update-profile-information-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
