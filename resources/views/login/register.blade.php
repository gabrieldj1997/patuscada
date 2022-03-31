<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de jogador</title>
   
</head>

<body>
    @if ($errors->any())                     
        @foreach ($errors->all() as $input_error)
        <div class = "alert alert-error">      
          {{ $input_error }}
        </div>
        @endforeach 
    @endif
    
    <a href="{{ route('login.index') }}">voltar</a>
    <form id="cadaster_form" action="{{ route('login.register') }}" method="POST">
        @csrf
        <input type="text" name="name" id="name_input" placeholder="Digite seu nome...">
        <input type="text" name="nickname" id="nickname_input" placeholder="Digite seu login...">
        <input type="text" name="password" id="password_input" placeholder="Digite sua senha...">
        <input type="text" name="email" id="email_input" placeholder="Digite seu email...">
        <button type="submit" id="cadaster">Cadastrar</button>
    </form>
    <script src="{{ url('./js/registerLogin.js') }}"></script>
</body>
</html>
