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
                        <form id="chat-form">
                            <div class="input-group">
                                <input type="text" id="chat-input" class="form-control" placeholder="Введите ваше сообщение...">
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const socket = io('{{ config("app.socket_server_address") }}:3000');


        // Уведомления о новых сообщениях
        socket.on('chat message', (msg) => {
            // Добавьте код для добавления нового сообщения на страницу чата
        });

        // Индикаторы "печатающего" пользователя
        const typingIndicator = document.getElementById('typing-indicator');

        socket.on('typing', (msg) => {
            typingIndicator.innerHTML = msg;
        });

        // Отправка сообщений
        const sendMessageButton = document.getElementById('send-message-button');
        const messageInput = document.getElementById('message-input');

        sendMessageButton.addEventListener('click', () => {
            const message = messageInput.value;

            if (message) {
                socket.emit('chat message', message);
                messageInput.value = '';
            }
        });

        // Обработка события "печатающий"
        messageInput.addEventListener('input', () => {
            socket.emit('typing', 'Пользователь печатает...');
        });
    </script>
@endsection

