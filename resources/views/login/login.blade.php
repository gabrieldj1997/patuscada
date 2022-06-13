<!DOCTYPE html>
<html lang="en">
<?php
if (Session::has('message')) {
    $message = Session::get('message');
}
if (Session::has('error')) {
    $error = Session::get('error');
}
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Patuscada v.Dj1997</title>
    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>
    <div class="container">
        @if (Auth::check())
            <h2>Ola <strong>{{ Auth::user()->nickname }}</strong> </h2>
            <button type="button" class="btn btn-primary"
                onclick="window.location='{{ route('login.truncate') }}'">Deletar todos logins</button>
            <button type="button" class="btn btn-primary" onclick="window.location='{{ route('chat') }}'">chat</button>
            <button type="button" class="btn btn-primary"
                onclick="window.location='{{ route('login.logout') }}'">logout</button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-delete">Deletar meu
                usuario</button>

            <div id="modal-delete" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deletar usuario</h5>
                            <button type="button" class="btn btn-primary" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_delete" action="{{ route('login.delete', Auth::user()->id) }}"
                                method="POST">
                                @csrf
                                <label for="input-password">Confirme sua senha:</label>
                                <input type="text" id="input-password" name="password">
                                <button type="submit" class="btn btn-danger" form="form_delete">Deletar</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            <button id="button-modal-game" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-game">Criar
                jogo</button>
            <div id="modal-game" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Criar Sala de Jogo</h5>
                            <button type="button" class="btn btn-primary" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_game" action="{{ route('jogo.register') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <label for="input-codigo">Codigo do sala: (max 5 caracter)</label>
                                    <input type="text" id="input-codigo" name="codigo">
                                    <label for="input-name-game">Nome da sala:</label>
                                    <input type="text" id="input-name-game" name="nome_jogo">
                                    <button type="submit" class="btn btn-primary" form="form_game">Criar</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            <button id="button-modal-game" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-game-enter">Entrar no
                jogo</button>
            <div id="modal-game-enter" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Entrar Sala de Jogo</h5>
                            <button type="button" class="btn btn-primary" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <div class="row">
                                    <label for="input-codigo_enter">Codigo do sala:</label>
                                    <input type="text" id="input-codigo_enter" name="codigo_enter">
                                    <a class="btn btn-primary" id="button_game_enter">Entrar</a>
                                </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <h2>Usuario não logado</h2>
            <button type="button" class="btn btn-primary"
                onclick="window.location='{{ route('login.cadaster') }}'">Cadastro</button>
            <form id="form_login" data-grecaptcha-action="login" action="{{ route('login.autenticate') }}"
                method="POST">
                @csrf
                <input type="text" name="nickname" placeholder="Digite seu nickname...">
                <input type="text" name="password" placeholder="Digite sua senha...">
                <button type="submit" class="btn btn-primary" form="form_login">login</button>
            </form>
        @endif
        <button type="button" class="btn btn-primary"
            onclick="window.location='{{ route('index') }}'">voltar</button>
        @if (isset($message))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        @if (isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
@if (Auth::check())
<script>
    document.querySelector('#button-modal-game').onclick = () =>{
    document.querySelector('#input-codigo').value = ''
    let codigo = ''
    for(i = 0; i < 5; i++){
        codigo += String.fromCharCode(Math.floor((Math.random()*26)+65))
    }
    document.querySelector('#input-codigo').value = codigo
    }
    document.querySelector('#button_game_enter').onclick = () =>{
        codigo = document.querySelector('#input-codigo_enter').value
        req = new XMLHttpRequest();
        req.open('GET', document.location.origin+"/api/jogoApi/find/"+codigo);
        req.onload = function(){
            game = JSON.parse(this.response)
            if(game.id === undefined){
                alert('Nenhum jogo encontrado')
            }else{
                if(game.id_estado_jogo != 0){
                    alert('Jogo já iniciado ou encerrado')
                }else{
                    document.location.href = location.origin+"/jogo/partida/"+game.id
                }
            }
            
        }
        req.send();
    }
</script>
@endif
</html>
