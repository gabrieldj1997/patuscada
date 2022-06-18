//config
require('../bootstrap');

const { default: axios } = require('axios');

//URL's
const startGame = window.location.origin + '/jogo/start';

//Variaveis
const users_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];
const buttonTest = document.querySelector('#button_test');

window.Echo.channel('jogo-message-' + gameId)
    .listen('.message', (data) => {
        console.log(data)
        MessageTrigger(data)
    });

window.Echo.channel('jogo-cartas-' + gameId)
    .listen('.cartas-' + myId, (data) => {
        console.log(data)
    })

//Function's
function MessageTrigger(message) {
    //primeira classe
    //1 = Partida; 2 = cartas; 3= rodada; 4 = jogador;
    switch (message.data.tp_message[0]) {
        case 1:
            if(message.data.tp_message[1] == 2){
                window.location.href = window.location.href;
            }
            break;
        case 2:
            if(message.data.tp_message[1] == 1){
                //loading com mensagem "Embaralhando e distirbuindo as cartas..."
            }else{
                //excluindo loading 
            }
            break;
        case 3:
            //implementar
            break;
    }
}

