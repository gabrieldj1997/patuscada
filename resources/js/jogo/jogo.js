//config
require('../bootstrap');

//Variaveis
const users_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];



window.Echo.join('App.jogo-' + gameId)
    .joining((user) => {
        let element = document.getElementsByClassName(`jogador-${user.nickname}`);
        if (element.length == 0 && users_list != null) {
            users_list.innerHTML += `<li class="jogador-${user.nickname}" user_id="${user.id}"><strong>${user.nickname}</strong></li>`;
        }
    }).leaving((user) => {
        if (users_list != null) {
            document.querySelector(`.jogador-${user.nickname}`).remove();
        }
    }).listen('UserOnline', (e) => {
        let element = document.getElementsByClassName(`jogador-${e.user.nickname}`);
        if (element.length == 0 && users_list != null) {
            users_list.innerHTML += `<li class="jogador-${e.user.nickname}" user_id="${e.user.id}"><strong>${e.user.nickname}</strong></li>`;
        }

    }).listen('UserOffline', (e) => {
        if (e.user.nickname != nickname_input.value) {
            try {
                document.querySelector(`.jogador-${e.user.nickname}`).remove();
            } catch (e) {

            }
        }
    }).listen('.pusher:subscription_succeeded', (membros) => {

        Object.keys(membros.members).forEach(id => {
            if (users_list != null) {
                users_list.innerHTML += `<li class="jogador-${membros.members[id].nickname}" user_id="${membros.members[id].id}"><strong>${membros.members[id].nickname}</strong></li>`;
            }
        })
    });

