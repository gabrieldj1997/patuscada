require('../bootstrap');

const { default: axios } = require('axios');
const users_list = document.querySelector('#jogadores');
const gameId = document.location.pathname.split('/')[3];
try {
    axios.get(`/api/jogoApi/${gameId}`).then(res => {

    });   
} catch (e) {
    alert('erro de conexão com o servidor')
}


// window.Echo.channel('chat')
//     .listen('.message', (e) => {
//         if (e.nickname == nickname_input.value) {
//             messages_el.innerHTML += `<div class="self-messages messages"><strong>você</strong>: <div class="message-text">${e.message}</div></div>`;
//         } else {
//             messages_el.innerHTML += `<div class="other-messages messages"><strong>${e.nickname}</strong>: <div class="message-text">${e.message}</div></div>`;
//         }
//         messages_el.scrollTop = messages_el.scrollHeight;
//     });

// window.Echo.channel('chat')
//     .listen('.message', (e) => {
//         if (e.nickname == nickname_input.value) {
//             messages_el.innerHTML += `<div class="self-messages messages"><strong>você</strong>: <div class="message-text">${e.message}</div></div>`;
//         } else {
//             messages_el.innerHTML += `<div class="other-messages messages"><strong>${e.nickname}</strong>: <div class="message-text">${e.message}</div></div>`;
//         }
//         messages_el.scrollTop = messages_el.scrollHeight;
//     });
window.Echo.join('App.game-' + gameId)
    .joining((user) => {
        console.log('joining: ', user);
        axios.put('/api/user/' + user.id + '/online?api_token=' + user.api_token, {});
        let element = document.getElementsByClassName(`user-${user.nickname}`);
        if (element.length == 0) {
            users_list.innerHTML += `<li class="user-${user.nickname}"><strong>${user.nickname}</strong></li>`;
        }
    }).leaving((user) => {
        console.log('leaving: ', user);
        axios.put('/api/user/' + user.id + '/offline?api_token=' + user.api_token, {});
        document.querySelector(`.user-${user.nickname}`).remove();
    }).listen('UserOnline', (e) => {
        console.log('UserOnline: ', e);
        let element = document.getElementsByClassName(`user-${e.user.nickname}`);
        if (element.length == 0) {
            users_list.innerHTML += `<li class="user-${e.user.nickname}"><strong>${e.user.nickname}</strong></li>`;
        }

    }).listen('UserOffline', (e) => {
        console.log('UserOffline: ', e);
        if (e.user.nickname != nickname_input.value) {
            try {
                document.querySelector(`.user-${e.user.nickname}`).remove();
            } catch (e) {

            }
        }
    }).listen('.pusher:subscription_succeeded', (membros) => {
        console.log('membros: ', membros);
        axios.put('/api/user/' + membros.me.id + '/online?api_token=' + membros.me.api_token, {});
        axios.get('/login/users-online').then(resp => {
            resp.data.forEach(user => {
                usuario = membros.members[user.id];
                if (usuario == null) {
                    console.log('mudando status', user.nickname, 'para offline')
                    axios.put('/api/user/' + user.id + '/offline?api_token=' + user.api_token, {})
                }
            });
        })

        Object.keys(membros.members).forEach(id => {
            users_list.innerHTML += `<li class="user-${membros.members[id].nickname}"><strong>${membros.members[id].nickname}</strong></li>`;
        })
    });