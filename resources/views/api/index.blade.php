<x-guest-layout>
    <x-navbar></x-navbar>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-body">
                        @livewire('api.api-token-manager')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer></x-footer>
</x-guest-layout>
