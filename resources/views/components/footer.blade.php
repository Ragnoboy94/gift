<footer class="text-center py-1 bg-light fixed-bottom">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col-auto d-none d-md-block text-start">
                <a target="_blank" href="{{route('terms1.show')}}"
                   class="text-decoration-none text-sm text-muted">{{__('messages.terms_of_service')}}</a>
                <br>
                <a target="_blank" href="{{route('policy.show')}}"
                   class="text-decoration-none text-sm text-muted">{{__('messages.privacy_policy')}}</a>

            </div>
            <div class="col-auto text-center text-muted">
                <p class="mb-0"><a class="text-muted h5" href="{{ route('home') }}">{{__('app.gift_secrets')}}</a><span
                        class="d-none d-sm-block">{{__('app.right_my')}} {{ date('Y') }}г.</span></p>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary btn-sm my-2" data-bs-toggle="modal"
                        data-bs-target="#contactModal">{{__("cont.contact")}}
                </button>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">{{__("cont.contact")}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>

            </div>
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="modal-body">


                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" required="" placeholder="Email" name="email">
                        <label for="email">{{__('cont.email')}}</label>
                    </div>
                    <div class="mb-3">
                        <label for="Description" class="form-label">{{__('cont.input_message')}}</label>
                        <textarea class="form-control" name="message" id="Description" rows="4" required></textarea>
                    </div>

                </div>
                <div class="modal-header">
                    <div class="text-muted text-sm">
                        {{__('cont.help')}}
                    </div>
                    <div class="mt-4" id="iframeContainer">

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('order.send')}}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('api-tokens.close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var iframeLoaded = false;


    document.getElementById('contactModal').addEventListener('show.bs.modal', function () {
        if (!iframeLoaded) {

            var iframe = document.createElement('iframe');
            iframe.src = 'https://yoomoney.ru/quickpay/fundraise/button?billNumber=fXL67ACxoEU.230905&';
            iframe.width = '330';
            iframe.height = '50';
            iframe.frameborder = '0';
            iframe.allowtransparency = 'true';
            iframe.scrolling = 'no';


            document.getElementById('iframeContainer').appendChild(iframe);

            iframeLoaded = true;
        }
    });
</script>
