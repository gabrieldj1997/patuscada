require('../bootstrap');

const { default: axios } = require('axios');

const urlMessage = window.location.origin + '/send-message';

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

const messages_el = document.getElementById('messages');
const nickname_input = document.getElementById('nickname_input');
const message_input = document.getElementById('message_input');
const message_form = document.getElementById('message_form');

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

