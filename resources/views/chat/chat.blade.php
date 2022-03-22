<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Patuscada</title>

    <link rel="stylesheet" href="./css/app.css">
</head>
<body>
    <a href="{{ url('/login') }}">voltar</a>
    <div class="app">
        <header>
            <h1>Let's play</h1>
            <input type="text" name="username" id="username" value="{{ $nickname }}" disabled>
        </header>
        <div id="messages"></div>

        <form id="message_form">
            <input type="text" name="message" id="message_input" placeholder="Your message">
            <button type="submit" id="message_send">Send message</button>
        </form>
    </div>

    <script src="./js/app.js"></script>
    <script src="./js/chat.js"></script>
</body>
</html>