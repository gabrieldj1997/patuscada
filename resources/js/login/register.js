const { default: axios } = require('axios');

const username = document.getElementById('username_input');
const passsword = document.getElementById('password_input');
const email = document.getElementById('email_input');
const cadaster_form = document.getElementById('cadaster_form');

cadaster_form.addEventListener('submit', (e) => {
    e.preventDefault();

    let has_errors = false;

    if(username.value == "") {
        username.classList.add('is-invalid');
        has_errors = true;
    }

    if(passsword.value == "") {
        passsword.classList.add('is-invalid');
        has_errors = true;
    }

    if(email.value == "") {
        email.classList.add('is-invalid');
        has_errors = true;
    }

    if(has_errors){
        return;
    }

    const options = {
        method: 'POST',
        url: window.location.origin+'/login',
        data: {
            username: username.value,
            password: passsword.value,
            email: email.value
        }
    }
    console.log(options);
    axios(options).then(resp => {
        if(resp.status == 200) {
        alert(resp.data.status);
        window.location.href = window.location.origin+'/login';
        return
        }
        alert('Jogador não cadastrado, nickname já existe');
    }).catch(err => {
        alert('Jogador não cadastrado' + err)
    });

});
