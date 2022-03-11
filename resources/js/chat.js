const { default: axios } = require('axios');

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

const messages_el = document.getElementById('messages');
const username_input = document.getElementById('username');
const message_input = document.getElementById('message_input');
const message_form = document.getElementById('message_form');

message_form.addEventListener('submit', (e) => {
    e.preventDefault();

    let has_errors = false;

    if(username_input.value == "") {
        username_input.classList.add('is-invalid');
        has_errors = true;
    }

    if(message_input.value == "") {
        message_input.classList.add('is-invalid');
        has_errors = true;
    }

    if(has_errors){
        return;
    }

    const options = {
        method: 'POST',
        url: window.location.origin+'/send-message',
        data: {
            username: username_input.value,
            message: message_input.value
        }
    }

    axios(options);

});

window.Echo.channel('chat')
    .listen('.message', (e) => {
        messages_el.innerHTML += `<div class="message"><strong>${e.username}</strong>: ${e.message}</div>`;    
    })

    