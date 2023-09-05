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
                        data-bs-target="#contactModal">Связаться с разработчиками
                </button>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Связаться с разработчиками</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>

            </div>
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="modal-body">


                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" required="" placeholder="Email" name="email">
                        <label for="email">Email адрес</label>
                    </div>
                    <div class="mb-3">
                        <label for="Description" class="form-label">Введите сообщение</label>
                        <textarea class="form-control" name="message" id="Description" rows="4" required></textarea>
                    </div>

                </div>
                <div class="modal-header">
                    <div class="text-muted text-sm">
                        Поддержи проект: хостинг, обновление функционала.
                    </div>
                    <div class="mt-4">
                        <iframe src="https://yoomoney.ru/quickpay/fundraise/button?billNumber=fXL67ACxoEU.230905&"
                                width="330" height="50" frameborder="0" allowtransparency="true"
                                scrolling="no"></iframe>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </form>
        </div>
    </div>
</div>
