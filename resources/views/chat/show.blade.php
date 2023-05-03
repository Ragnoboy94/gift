@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        Чат
                    </div>
                    <div class="card-body">
                        <div class="chat-messages" id="chat-messages" style="height: 400px; overflow-y: scroll;">
                        </div>
                    </div>
                    <div class="card-footer">
                        <form id="chat-form" autocomplete="off">
                            <div class="input-group">
                                <input type="text" id="chat-input" class="form-control"
                                       placeholder="Введите ваше сообщение..." required>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </div>
                        </form>
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

            getMessages();
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const message = chatInput.value;

                if (message) {
                    sendMessage(message);
                    chatInput.value = '';
                }
            });

            // Запуск цикла проверки каждые 3 секунды
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
                            addMessageToChat({message: content, user_id: {{ auth()->id() }}});
                        }
                    });
            }

            function addMessageToChat(message) {
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
        });
    </script>

@endsection
