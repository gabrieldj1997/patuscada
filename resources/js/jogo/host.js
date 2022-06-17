//Config
require('../bootstrap');

const { default: axios } = require('axios');

//URL's
const startGame = window.location.origin + '/api/jogoApi/start';

//Variaveis
const jogadores_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];

if (jogo.estado_jogo == 0) {
    var button_start = document.querySelector('#button_start');
    button_start.onclick = () => {
        let jogadores = [];
        let confirmMessage = 'Tem certeza que deseja iniciar o jogo? \n';
        for (i = 0; i < jogadores_list.children.length; i++) {
            confirmMessage += `Jogador ${i+1}:  ${jogadores_list.children[i].className.replace('jogador-','')}\n`;
            jogadores.push(parseInt(jogadores_list.children[i].attributes.user_id.value));
        }
        console.log(jogadores)
        let host_confirm = confirm(confirmMessage);
        if(host_confirm){
            const options = {
                method: 'POST',
                url: startGame,
                data: {
                    id: gameId,
                    jogadores: jogadores
                }
            }
        
            axios(options).then(data => {console.log(data)});
        }
    }
}