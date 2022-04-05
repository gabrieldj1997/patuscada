<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="APP_KEY" content="{{ config('broadcasting.connections.pusher.key') }}" />

    <title>Patuscada</title>

    <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('css/style.css')) }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>

<body>
    <div class="container chat-container">
        <div class="chat-header col-12">
            <div class="row">
                <div class="title-logo col-md-3">
                    <h1>Chat</h1>
                </div>
                <div class="header-section-bar d-flex justify-content-end col-md-3">
                    <button type="button" class="btn btn-primary"
                        onclick="window.location='{{ route('login.index') }}'">Voltar</button>
                </div>
            </div>
        </div>
        <div class="chat-section col-12">
            <div id="users-online" class="col-2">
                <h4>Usuarios online</h4>
                <ul id="users-online-list"></ul>
            </div>
            <div id="messages" class="col-10"></div>
        </div>
        <div class="chat-footer col-12">
            <div class="row chat-form">
                <div class="d-flex justify-content-center">
                    <form id="message_form">
                        <div class="d-flex justify-content-between">
                            <input type="text" name="message" id="message_input" placeholder="Your message">
                            <input type="text" name="nickname" id="nickname_input" value="{{ Auth::user()->nickname }}" hidden>
                            <button type="submit" class="btn btn-primary" id="message_send">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="{{ url(mix('js/app.js')) }}"></script>
<script src="{{ url(mix('js/chat.js')) }}"></script>


</html>
