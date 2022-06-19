const { default: axios } = require('axios');
const { Channel } = require('pusher-js');

//config
require('../bootstrap');

//URL's
const chosseCartaPreta = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/cartapreta`;
const chosseCartaBranca = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/cartabranca`;
const chosseVencedor = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/vencedor`;

//Variaveis
const users_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];
const botao_cartas_pretas = document.querySelectorAll('.button_carta_preta');
const botao_cartas_brancas = document.querySelectorAll('.button_carta_branca');
const botao_cartas_brancas_leitor = document.querySelectorAll('.button_carta_branca_leitor');
const box_cartas_brancas_leitor = document.querySelector('#box_cartas_brancas_leitor');
const box_cartas_brancas_jogador = document.querySelector('#box_cartas_brancas');
const box_cartas_pretas_leitor = document.querySelector('#box_cartas_pretas_leitor');
const box_cartas_pretas_jogador = document.querySelector('#box_cartas_pretas');
const cartas_pretas_leitor = document.querySelectorAll('.carta_preta_leitor');



window.Echo.join('App.jogo-' + gameId)
    .joining((user) => {
        console.log('joining: ', user);
        let element = document.getElementsByClassName(`jogador-${user.nickname}`);
        if (element.length == 0 && users_list != null) {
            users_list.innerHTML += `<li class="jogador-${user.nickname}" user_id="${user.id}"><strong>${user.nickname}</strong></li>`;
        }
    }).leaving((user) => {
        console.log('leaving: ', user);
        if (users_list != null) {
            document.querySelector(`.jogador-${user.nickname}`).remove();
        }
    }).listen('UserOnline', (e) => {
        console.log('UserOnline: ', e);
        let element = document.getElementsByClassName(`jogador-${e.user.nickname}`);
        if (element.length == 0 && users_list != null) {
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
            if (users_list != null) {
                users_list.innerHTML += `<li class="jogador-${membros.members[id].nickname}" user_id="${membros.members[id].id}"><strong>${membros.members[id].nickname}</strong></li>`;
            }
        })
    });

if (botao_cartas_pretas.length > 0) {
    botao_cartas_pretas.forEach(carta => {
        carta.addEventListener('click', (event) => {
            console.log(event.path[1].previousElementSibling.attributes.idCartaPreta.value);
            let cartaEscolhida = event.path[1].previousElementSibling;
            let idcarta = cartaEscolhida.attributes.idCartaPreta.value;
            let userConfirm = confirm('Escolher carta ' + idcarta + '?');
            if (userConfirm) {
                options = {
                    method: 'POST',
                    url: chosseCartaPreta,
                    data: {
                        id_carta_preta: idcarta,
                        my_id: myId
                    }
                }

                axios(options);
                cartas_pretas_leitor.forEach(item =>{
                    if(item != cartaEscolhida){
                        item.remove();
                    }
                })
                botao_cartas_pretas.forEach(item => {
                    item.remove();
                })
            }
        })
    })
}
if (botao_cartas_brancas.length > 0) {
    botao_cartas_brancas.forEach(carta => {
        carta.addEventListener('click', (event) => {
            console.log(event.path[1].previousElementSibling.attributes.idCartaBranca.value);
            let idcarta = event.path[1].previousElementSibling.attributes.idCartaBranca.value;
            let userConfirm = confirm('Escolher carta ' + idcarta + '?');
            if (userConfirm) {
                options = {
                    method: 'POST',
                    url: chosseCartaBranca,
                    data: {
                        id_carta_branca: idcarta,
                        my_id: myId
                    }
                }
                axios(options);
                botao_cartas_brancas.forEach(item => {
                    item.remove();
                })
            }
        })
    })
}

if (estadoJogo != 0) {
    if (myId == jogadorLeitor) {
        box_cartas_pretas_leitor.style.display = 'block';
        botao_cartas_brancas.forEach(item => {
            item.remove();
        })
    } else {
        box_cartas_brancas_jogador.style.display = 'block';
    }
}