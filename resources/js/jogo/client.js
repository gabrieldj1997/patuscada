//config
require('../bootstrap');

const { default: axios } = require('axios');

//URL's
const startGame = window.location.origin + '/jogo/teste';

//Variaveis
const users_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];
const buttonTest = document.querySelector('#button_test');


buttonTest.onclick = () => {
    const options = {
        method: 'GET',
        url: startGame+'/'+gameId
    }

    axios(options)
}

window.Echo.channel('jogo-message-' + gameId)
    .listen('.message', (data) => {
        
    });

