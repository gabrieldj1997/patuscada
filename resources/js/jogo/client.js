//config
require('../bootstrap');

const { default: axios } = require('axios');

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

//URL's
const getCartaPreta = window.location.origin + '/api/cartaspretas';
const getCartaBranca = window.location.origin + '/api/cartasbrancas';
const chosseCartaPreta = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/cartapreta`;
const chosseCartaBranca = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/cartabranca`;
const chosseVencedor = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/vencedor`;
const changeCartas = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/changeCartas`;
const getNickname = window.location.origin + `/api/user`;

//Variaveis
const gameId = document.location.pathname.split('/')[2];
const box_cartas_brancas_leitor = document.querySelector('#box_cartas_brancas_leitor');
const box_cartas_brancas_jogador = document.querySelector('#box_cartas_brancas');
const box_cartas_pretas_leitor = document.querySelector('#box_cartas_pretas_leitor');
const box_cartas_pretas_jogador = document.querySelector('#box_cartas_pretas');
const botao_cartas_brancas = document.querySelectorAll('.button_carta_branca');
const botao_cartas_pretas = document.querySelectorAll('.button_carta_preta');
const cartas_pretas_leitor = document.querySelectorAll('.carta_preta_leitor');
const button_trocar_cartas = document.querySelector('#button_trocar_cartas');

window.Echo.channel('jogo-message-' + gameId)
    .listen('.message', (data) => {
        MessageTrigger(data)
    });

window.Echo.channel('jogo-jogada-' + gameId)
    .listen('.jogadas', (data) => {
        JogadaTrigger(data)
    })

if (botao_cartas_pretas.length > 0) {
    botao_cartas_pretas.forEach(carta => {
        carta.addEventListener('click', (event) => {
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
                cartas_pretas_leitor.forEach(item => {
                    if (item != cartaEscolhida) {
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
                document.querySelector('#mensagens').innerHTML = `<h1>Aguarde o Leitor escolher uma carta branca</h1>`
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

if (button_trocar_cartas != null) {
    button_trocar_cartas.addEventListener('click', (event) => {
        let userConfirm = confirm('Trocar todas suas cartas brancas?');
        if (userConfirm) {
            options = {
                method: 'POST',
                url: changeCartas,
                data: {
                    my_id: myId
                }
            }
            axios(options);
            button_trocar_cartas.remove();
        }
    })
}
//Function's
function MessageTrigger(message) {
    //primeira classe
    //1 = Partida; 2 = cartas; 3= rodada; 4 = jogador;
    switch (message.data.tp_message[0]) {
        case 1:
            if (message.data.tp_message[1] == 2) {
                window.location.href = window.location.href;
            } else if (message.data.tp_message[1] == 3) {
                document.querySelector('#mensagens').innerHTML = `<h1>Jogo finalizado! ${message.data.message}</h1>`
            }
            break;
        case 2:
            if (message.data.tp_message[1] == 1) {
                //loading com mensagem "Embaralhando e distirbuindo as cartas..."
            } else {
                window.location.href = window.location.href;
            }
            break;
        case 3:
            //implementar
            break;
    }
}

async function JogadaTrigger(message) {
    var tpJogador = TipoJogador();
    if (tpJogador == 1) {
        if (message.tp_jogada == 2) {
            box_cartas_brancas_leitor.style.display = 'block'
            var carta = await GeradorCarta(message.cartas.id, 'branca', message.jogadorId);
            box_cartas_brancas_leitor.innerHTML += carta;
            let botao_cartas_brancas_leitor = document.querySelectorAll('.button_carta_branca_leitor');
            if (botao_cartas_brancas_leitor.length > 0) {
                botao_cartas_brancas_leitor.forEach(carta => {
                    carta.addEventListener('click', (event) => {
                        let idCartaPreta = document.querySelector('.carta_preta_leitor').attributes.idcartapreta.value
                        let idCartaBranca = event.path[1].previousElementSibling.attributes.idCartaBranca.value;
                        let jogadorGanhador = event.path[1].previousElementSibling.attributes.idjogador.value;
                        let userConfirm = confirm('Escolher carta ' + idCartaBranca + '?');
                        if (userConfirm) {
                            options = {
                                method: 'POST',
                                url: chosseVencedor,
                                data: {
                                    id_carta_preta: idCartaPreta,
                                    id_carta_branca: idCartaBranca,
                                    my_id: myId,
                                    id_jogador_ganhador: jogadorGanhador
                                }
                            }
                            axios(options);
                            botao_cartas_brancas_leitor.forEach(item => {
                                item.remove();
                            })
                        }
                    })
                })
            }
        } else if (message.tp_jogada == 1) {
            document.querySelector('#mensagens').innerHTML = `<h1>Aguarde todos escolherem uma carta branca</h1>`
        }
    } else {
        if (message.tp_jogada == 1) {
            box_cartas_pretas_jogador.style.display = 'block'
            var carta = await GeradorCarta(message.cartas.id, 'preta', message.jogadorId);
            box_cartas_pretas_jogador.innerHTML += carta;
            botao_cartas_brancas.forEach(botao => {
                botao.parentElement.style.display = 'flex'
            })
            document.querySelector('#mensagens').innerHTML = `<h1>Escolha uma carta branca</h1>`
        } else if (message.tp_jogada == 3) {
            box_cartas_brancas_leitor.style.display = 'block'
            var carta = await GeradorCarta(message.cartas.id_carta_branca, 'branca', message.jogadorId);
            box_cartas_brancas_leitor.innerHTML += carta;
            document.querySelector('.button_carta_branca_leitor').remove()
        }
    }
    if (message.tp_jogada == 3) {
        user = await ConsultarUsuario(message.cartas.id_jogador)
        document.querySelector('#mensagens').innerHTML = `<h1>Jogador ${user.nickname} venceu a rodada!</h1>`
    }
}

async function GeradorCarta(id, tipo, idUser) {
    var cartaObj;
    var option = {
        method: 'GET',
        url: ((tipo == 'branca') ? getCartaBranca : getCartaPreta) + '/' + id
    }
    cartaObj = await axios(option);
    cartaObj = cartaObj.data
    var carta = `<div style="display: flex;">`
    carta += (tipo == 'branca') ? `<div class="carta_branca card bg-light mb-3" style="max-width: 18rem;"` : `<div class="carta_preta card text-white bg-dark mb-3" style="max-width: 18rem;"`
    carta += ((tipo == 'branca') ? `idCartaBranca="${cartaObj.id}"` : `idCartaPreta="${cartaObj.id}"`) + ` idJogador="${idUser}">`
    carta += `<div class="card-header">Patuscada carta_id = ${cartaObj.id}</div>`
    carta += `<div class="card-body">`
    carta += `<p class="card-text">`
    carta += `${cartaObj.texto}`
    carta += `</p>`
    carta += `</div>`
    carta += `</div>`
    carta += (tipo == 'preta') ? '</div>' : ` <div style="display: flex;align-items: center;"><button type="button" class="btn btn-primary button_carta_branca_leitor"> Selecionar carta ${cartaObj.id} </button></div></div>`
    return carta;
}

async function ConsultarUsuario(id){
    var option = {
        method: 'GET',
        url: getNickname + '/' + id
    }
    var user = await axios(option);
    user = user.data
    return user;
}