const { default: axios } = require('axios');

const username = document.getElementById('username_input');
const passsword = document.getElementById('password_input');

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
                    .then(login);
            });
        }
    });
});

let login = (token) => {
    const options = {
        method: 'POST',
        url: window.location.origin + `/login/${username.value}`,
        data: {
            username: username.value,
            password: passsword.value,
            grecaptcha: token
        }
    }

    axios(options).then(resp => {
        if (resp.status == 200) {
            alert(`Jogador: ${resp.data.data.username} logado com sucesso`);
            window.location.href = window.location.origin + '/login';
            return
        }
        alert('Jogador não cadastrado');
        console.log(resp.data);
    }).catch(err => {
        alert('Jogador não cadastrado' + err)
    });
}