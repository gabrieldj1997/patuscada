require('../bootstrap');
const { default: axios } = require('axios');

//Urls
const urlLogin = window.location.origin + `/login/entrar`;
const urlRegisterLogin = window.location.origin + `/login/cadastro`;

const name = document.getElementById('name_input');
const nickname = document.getElementById('nickname_input');
const passsword = document.getElementById('password_input');
const email = document.getElementById('email_input');

let grecaptchaKeyMeta = document.querySelector("meta[name='grecaptcha-key']");
let grecaptchaKey = grecaptchaKeyMeta.getAttribute("content");


grecaptcha.ready(function () {
    let forms = document.querySelectorAll('form[data-grecaptcha-action]');

    Array.from(forms).forEach(function (form) {
        form.onsubmit = (e) => {

            e.preventDefault();

            let grecaptchaAction = form.getAttribute('data-grecaptcha-action');

            grecaptcha.ready(function () {

                grecaptcha.execute(grecaptchaKey, { action: grecaptchaAction })
                    .then(registerLogin);
            });
        }

    });
});

let registerLogin = (token) => {

    const options = {
        method: 'POST',
        url: urlRegisterLogin,
        data: {
            name: name.value,
            nickname: nickname.value,
            password: passsword.value,
            email: email.value,
            grecaptcha: token
        }
    }

    axios(options).then(resp => {
        if (resp.status == 200) {
            alert(resp.data.status);
            window.location.href = urlLogin;
            return
        }
        alert('Jogador não cadastrado, nickname já existe');
        console.log(resp.dada);
    }).catch(err => {
        alert('Jogador não cadastrado error => ' + err)
    });
}