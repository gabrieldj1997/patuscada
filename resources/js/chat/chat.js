require('../bootstrap');

const { default: axios } = require('axios');

const urlMessage = window.location.origin + '/send-message';

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

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
            messages_el.innerHTML += `<div class="other-messages messages"><strong>${e.nickname}</strong>: <div class="message-text">${e.message}</div></div>`;
        }
        messages_el.scrollTop = messages_el.scrollHeight;
    });

window.Echo.join('App.Chatroom')
    .joining((user) => {
        axios.put('/api/user/' + user.id + '/online?api_token=' + user.api_token, {});
    }).leaving((user) => {
        axios.put('/api/user/' + user.id + '/offline?api_token=' + user.api_token, {});
    }).listen('UserOnline', (e) => {
        if (e.user.nickname != nickname_input.value) {
            let element = document.getElementsByClassName(`user-${e.user.nickname}`);
            if (element.length == 0) {
                users_list.innerHTML += `<li class="user-${e.user.nickname}"><strong>${e.user.nickname}</strong></li>`;
            }
        }
    }).listen('UserOffline', (e) => {
        if (e.user.nickname != nickname_input.value) {
            try {
                document.querySelector(`.user-${e.user.nickname}`).remove();
            } catch (e) {

            }
        }
    }).listen('.pusher:subscription_succeeded', (membros) => {
        axios.put('/api/user/' + membros.me.id + '/online?api_token=' + membros.me.api_token, {});
        axios.get('/login/users-online').then(resp => {
            resp.data.forEach(user => {
                usuario = membros.members[user.id];
                if(usuario == null){
                    console.log('mudando status', user.nickname, 'para offline')
                    axios.put('/api/user/' + user.id + '/offline?api_token=' + user.api_token, {})
                }
            });
        })
        
        Object.keys(membros.members).forEach(id => {
            if (membros.members[id].nickname != nickname_input.value) {
                users_list.innerHTML += `<li class="user-${membros.members[id].nickname}"><strong>${membros.members[id].nickname}</strong></li>`;
            }
        })
    });
