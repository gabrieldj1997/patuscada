<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Patuscada v.Dj1997</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>
    <div class="container">
        @if (Auth::check())
            <p>Ola {{ Auth::user()->nickname }}</p>
            <button type="button" class="btn btn-primary" onclick="window.location='{{ route('login.truncate') }}'">Deletar todos logins</button>
            <button type="button" class="btn btn-primary" onclick="window.location='{{ route('chat') }}'">chat</button>
            <button type="button" class="btn btn-primary" onclick="window.location='{{ route('login.logout') }}'">logout</button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-sm">Deletar meu usuario</button>

            <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <form action="{{ route('login.delete', Auth::user()->id) }}" method="delete">
                            @csrf
                            <label for="input-password">Confirme sua senha:</label>
                            <input type="text" id="input-password">
                            <button type="submit">Deletar</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <p>Usuario não logado</p>
            <button type="button" class="btn btn-primary" onclick="window.location='{{ route('login.cadaster') }}'">Cadastro</button>
            <form id="login_form" data-grecaptcha-action="login" action="{{ route('login.autenticate') }}"
                method="POST">
                @csrf
                <input type="text" name="nickname" id="nickname_input" placeholder="Digite seu nickname...">
                <input type="text" name="password" id="password_input" placeholder="Digite sua senha...">
                <button type="submit" class="btn btn-primary" id="login_submit">login</button>
            </form>
        @endif
        @if (isset($message))
            <br>
            <span id="messages">{{ $message }}</span>
        @endif
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>
