@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @if(session()->has('message'))
                <div class="text-success text-center mt-3">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if(session()->has('error_message'))
                <div class="text-danger text-center mt-3">
                    {{ session()->get('error_message') }}
                </div>
            @endif
            @if ($order->status_id != 3)
                <h2 class="text-center"> {{__('trans.order')}} {{$order->status->display_name}}</h2>
                <div class="card col-6 mx-auto mb-3 text-white"
                     style="background-image: url('../images/{{ pathinfo($order->celebration->image, PATHINFO_FILENAME)}}_small.webp'); background-size: cover; background-position: center; text-shadow: 1px 1px 2px rgb(0, 0, 0,1);">
                    <div class="card-body">
                        <h5 class="card-title">№ {{__('order.orders')}}: {{ $order->order_number }}</h5>
                        <p class="card-text">
                            <b>{{__('order.summa')}}:</b> {{ $order->sum }} {{ $order->sum_rubles }}<br>
                            <b>{{__('order.status')}}:</b> {{ $order->status->display_name }}<br>
                            <b>{{__('order.date_create')}}:</b> {{ $order->created_at }}<br>
                        </p>
                    </div>
                </div>

            @else
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{__('order.chat')}}
                        </div>
                        <div class="card-body">
                            <div class="chat-messages" id="chat-messages" style="height: 400px; overflow-y: scroll;">
                            </div>
                        </div>
                        <div class="card-footer">
                            <form id="chat-form" autocomplete="off">
                                <div class="input-group">
                                    <input type="text" id="chat-input" class="form-control"
                                           placeholder="{{__('order.inter_mass')}}..." required>
                                    <button type="submit" class="btn btn-primary">{{__('order.send')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{__('order.order_info')}}:
                        </div>
                        <div class="card-body">
                            <div id="message" class="alert" role="alert" style="display: none;"></div>
                            @if (Auth::user()->id == $order->user_id)
                                <p><img width="48" class="h-8 w-8 rounded-full object-cover"
                                        src="{{ $elf->profile_photo_url }}"
                                        alt="{{ $elf->name }}"/><strong>{{__('order.elf1')}}:</strong> {{ $elf->name }}</p>
                                @if(!$order->phone_visible)
                                    <form method="POST"
                                          action="{{ route('orders.update_phone_visibility', $order->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="alert alert-warning" role="alert">
                                            {{__('order.phone_info')}}
                                        </div>
                                        <button type="submit" class="btn btn-success form-control my-2">{{__('order.show_phone')}}
                                        </button>
                                    </form>
                                @else

                                    <div class="alert alert-info" role="alert">
                                        {{__('order.phone_info2')}}.
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="button" class="btn btn-primary form-control mb-1" data-bs-toggle="modal"
                                                data-bs-target="#orderConfirmationModal">
                                            {{__('order.order_taken')}}
                                        </button>
                                    </div>
                                    <div class="col-lg-6">
                                        <button type="button" class="btn btn-danger form-control mb-1" data-bs-toggle="modal"
                                                data-bs-target="#orderProblemModal">
                                            {{__('order.problem')}}
                                        </button>
                                    </div>
                                </div>
                                <div class="modal fade" id="orderProblemModal" tabindex="-1"
                                     aria-labelledby="orderProblemModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="orderProblemModalLabel">{{__('order.send_problem')}}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger" role="alert">
                                                    {{__('order.order_block')}}.
                                                </div>
                                                {{__('new.problem_text1')}}
                                                <form method="POST" action="{{ route('order-problem.store', $order->id) }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="problemDescription">{{__('order.problem_desc')}}</label>
                                                        <textarea class="form-control" id="problemDescription" required name="description" placeholder="{{__('order.problem')}}" rows="3"></textarea>
                                                    </div>
                                                    <input type="hidden" name="order_id" id="orderId" value="">
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">{{__('order.send')}}</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            {{__('api-tokens.close')}}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="orderConfirmationModal" tabindex="-1"
                                     aria-labelledby="orderConfirmationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="orderConfirmationModalLabel">{{__('order.confirm_taken')}}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{__('order.order_info2')}}
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST" action="{{ route('orders.finish', $order->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">{{__('app.confirm')}}</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    {{__('api-tokens.close')}}
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @else
                                <p><strong>{{__('messages.address')}}:</strong> {{ $order->address }}
                                    @if ($order->apartment)
                                        , {{__('messages.apartment')}}: {{$order->apartment}}
                                    @endif
                                    @if ($order->floor)
                                        , {{__('messages.floor')}}: {{$order->floor}}
                                    @endif
                                    @if ($order->intercom)
                                        , {{__('order.inter_work')}}
                                    @endif</p>
                                @if($order->phone_visible)
                                    <p><strong>{{__('order.phone')}}:</strong> {{ $user->phone }}</p>
                                @endif
                                <form id="photo-upload-form" enctype="multipart/form-data">
                                    <div class="upload-container" id="upload-container">
                                        <div class="upload-box" id="upload-box">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"
                                                 fill="currentColor"
                                                 class="bi bi-plus" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                            </svg>
                                            <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">{{__('order.description')}}:</label>
                                        <textarea placeholder="{{__('order.idea')}}..."
                                                  class="form-control" id="description" name="description"
                                                  rows="3">{{$order->description}}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{__('order.send')}}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatMessages = document.getElementById('chat-messages');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            let lastMessageId = 0;
            @if (Auth::user()->id != $order->user_id)
            displaySavedImages();
            @endif
            getMessages();
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const message = chatInput.value;

                if (message) {
                    sendMessage(message);
                    chatInput.value = '';
                }
            });

            setInterval(checkForNewMessages, 3000);

            function getMessages() {
                fetch(`{{ url('/chat/' . $order->id . '/messages') }}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(message => {
                            addMessageToChat(message);
                            lastMessageId = message.id;
                        });
                    });
            }

            function sendMessage(content) {
                fetch(`{{ url('/chat/' . $order->id . '/send') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({content}),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            addMessageToChat({message: content, user_id: {{ auth()->id() }}, sent_by_me: true});
                        }
                    });
            }

            function addMessageToChat(message) {
                if (message.sent_by_me && message.user_id === {{ auth()->id() }}) {
                    return;
                }

                const messageElement = document.createElement('div');
                messageElement.classList.add('chat-message');

                if (message.user_id === {{ auth()->id() }}) {
                    messageElement.classList.add('sender');
                } else {
                    messageElement.classList.add('receiver');
                }

                messageElement.textContent = message.message;
                chatMessages.appendChild(messageElement);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function checkForNewMessages() {
                fetch(`{{ url('/chat/' . $order->id . '/messages?lastMessageId=' . urlencode('')) }}${lastMessageId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(message => {
                                addMessageToChat(message);
                                lastMessageId = message.id;
                            });
                        }
                    });
            }
            @if (Auth::user()->id != $order->user_id)
            const uploadContainer = document.getElementById('upload-container');
            const uploadBox = document.getElementById('upload-box');
            const photosInput = document.getElementById('photos');

            uploadBox.addEventListener('click', (e) => {
                if (e.target === uploadBox || e.target.tagName === 'svg' || e.target.tagName === 'path') {
                    photosInput.click();
                }
            });
            let selectedFiles = [];
            photosInput.addEventListener('change', (e) => {
                selectedFiles = [...e.target.files];
                handleFiles(selectedFiles);
                photosInput.value = '';
            });


            function handleFiles(files) {
                for (const file of files) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.onload = () => URL.revokeObjectURL(img.src);

                    const newUploadBox = uploadBox.cloneNode(true);
                    newUploadBox.appendChild(img);

                    const removeIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    removeIcon.setAttribute('width', '26');
                    removeIcon.setAttribute('height', '26');
                    removeIcon.setAttribute('fill', 'currentColor');
                    removeIcon.classList.add('bi', 'bi-x-lg', 'remove-icon');

                    const path1 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path1.setAttribute('fill-rule', 'evenodd');
                    path1.setAttribute('d', 'M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z');

                    const path2 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path2.setAttribute('fill-rule', 'evenodd');
                    path2.setAttribute('d', 'M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z');

                    removeIcon.appendChild(path1);
                    removeIcon.appendChild(path2);

                    removeIcon.addEventListener('click', () => {
                        newUploadBox.remove();
                    });

                    newUploadBox.appendChild(removeIcon);
                    uploadContainer.insertBefore(newUploadBox, uploadBox);
                }
            }

            function displaySavedImages() {
                fetch('{{ route('get_saved_images', $order->id) }}')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(image => {
                            const img = document.createElement('img');
                            img.src = image.file_name;
                            img.classList.add('saved-image');

                            const newUploadBox = uploadBox.cloneNode(true);
                            newUploadBox.appendChild(img);

                            const removeIcon = newUploadBox.querySelector('.remove-icon');
                            if (removeIcon) {
                                removeIcon.remove();
                            }

                            uploadContainer.insertBefore(newUploadBox, uploadBox);
                        });
                    });
            }


            uploadBox.addEventListener('dragover', (e) => {
                e.preventDefault();
            });

            uploadBox.addEventListener('drop', (e) => {
                e.preventDefault();
                const files = e.dataTransfer.files;
                handleFiles(files);
            });
            const photoUploadForm = document.getElementById('photo-upload-form');

            photoUploadForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const formData = new FormData();

                for (let i = 0; i < selectedFiles.length; i++) {
                    formData.append('photos[]', selectedFiles[i]);
                }
                fetch('{{ route('upload_files', $order->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const messageElement = document.getElementById('message');
                            messageElement.style.display = 'block';
                            messageElement.classList.add('alert-success');
                            messageElement.textContent = data.message;

                            document.querySelectorAll('.remove-icon').forEach(removeIcon => {
                                removeIcon.remove();
                            });
                        }
                    });
            });
            @endif
        });
    </script>
    @endif
@endsection
