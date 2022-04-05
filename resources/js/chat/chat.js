require('../bootstrap');

const { default: axios } = require('axios');

const urlMessage = window.location.origin + '/send-message';

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

const nickname_input = document.getElementById('nickname_input');
const messages_el = document.getElementById('messages');
const message_input = document.getElementById('message_input');
const message_form = document.getElementById('message_form');
const users_list = document.getElementById('users-online-list');

message_form.addEventListener('submit', (e) => {
    e.preventDefault();

    let has_errors = false;

    if (message_input.value == "") {
        message_input.classList.add('is-invalid');
        has_errors = true;
    }

    if (has_errors) {
        return;
    }

    const options = {
        method: 'POST',
        url: urlMessage,
        data: {
            nickname: nickname_input.value,
            message: message_input.value
        }
    }

    axios(options);

    message_input.value = "";

});

window.Echo.channel('chat')
    .listen('.message', (e) => {
        if (e.nickname == nickname_input.value) {
            messages_el.innerHTML += `<div class="self-messages messages"><strong>você</strong>: <div class="message-text">${e.message}</div></div>`;
        } else {
            messages_el.innerHTML += `<div class="others-messages messages"><strong>${e.nickname}</strong>: <div class="message-text">${e.message}</div></div>`;
        }
        messages_el.scrollTop = messages_el.scrollHeight;
    });

window.Echo.join('App.Chatroom', (data) => { console.log(data) })
    .joining((user) => {
        console.log("usuario online ==> ",user);
        axios.put('/api/user/' + user.id + '/online?api_token=' + user.api_token, {});
    }).leaving((user) => {
        console.log("usuario offline ==> ",user);
        axios.put('/api/user/' + user.id + '/offline?api_token=' + user.api_token, {});
    }).listen('UserOnline', (e) => {
        console.log("usuario online EVENTO ==> ",e);
        if (e.user.nickname != nickname_input.value) {
            let element = document.getElementsByClassName(`user-${e.user.nickname}`);
            if (element.length == 0) {
                users_list.innerHTML += `<li class="user-${e.user.nickname}"><strong>${e.user.nickname}</strong></li>`;
            }
        }
    }).listen('UserOffline', (e) => {
        console.log("usuario offline EVENTO ==> ",e);
        if (e.user.nickname != nickname_input.value) {
            try{
                document.querySelector(`.user-${e.user.nickname}`).remove();
            }catch(e){

            }
        }
    });

axios.get('/login/users-online').then(data => {
    console.log(data.data)
    data.data.forEach(element => {
        if (element.nickname != nickname_input.value) {
            try{
                document.querySelector(`.user-${element.nickname}`).remove();
            }catch(e){

            }
            users_list.innerHTML += `<li class="user-${element.nickname}"><strong>${element.nickname}</strong></li>`;
        }
    });
})