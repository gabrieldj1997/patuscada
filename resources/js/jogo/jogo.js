const { default: axios } = require('axios');
const { Channel } = require('pusher-js');

//config
require('../bootstrap');

//URL's
const testeMessageJogo = window.location.origin + '/jogo/teste';

//Variaveis
const users_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];
const buttonTest = document.querySelector('#button_test');


window.Echo.join('App.jogo-' + gameId)
    .joining((user) => {
        console.log('joining: ', user);
        let element = document.getElementsByClassName(`jogador-${user.nickname}`);
        if (element.length == 0) {
            users_list.innerHTML += `<li class="jogador-${user.nickname}" user_id="${user.id}"><strong>${user.nickname}</strong></li>`;
        }
    }).leaving((user) => {
        console.log('leaving: ', user);
        document.querySelector(`.jogador-${user.nickname}`).remove();
    }).listen('UserOnline', (e) => {
        console.log('UserOnline: ', e);
        let element = document.getElementsByClassName(`jogador-${e.user.nickname}`);
        if (element.length == 0) {
            users_list.innerHTML += `<li class="jogador-${e.user.nickname}" user_id="${e.user.id}"><strong>${e.user.nickname}</strong></li>`;
        }

    }).listen('UserOffline', (e) => {
        console.log('UserOffline: ', e);
        if (e.user.nickname != nickname_input.value) {
            try {
                document.querySelector(`.jogador-${e.user.nickname}`).remove();
            } catch (e) {

            }
        }
    }).listen('.pusher:subscription_succeeded', (membros) => {
        console.log('membros: ', membros);

        Object.keys(membros.members).forEach(id => {
            users_list.innerHTML += `<li class="jogador-${membros.members[id].nickname}" user_id="${membros.members[id].id}"><strong>${membros.members[id].nickname}</strong></li>`;
        })
    });

buttonTest.onclick = () => {
    var options = {
        method: 'GET',
        url: testeMessageJogo + '/' + gameId
    }

    axios(options);
}
