require('../bootstrap');
const { default: axios } = require('axios');

const codGame = document.getElementById('cod_game_input');
const nameGame = document.getElementById('name_game_input');
const formRegisterGame = document.getElementById('form_register_game');

formRegisterGame.addEventListener('submit', (e) => {

    e.preventDefault();

    if (codGameValue === '' || nameGameValue === '') {
        alert('Preencha todos os campos');
        return;
    }

    const data = {
        codGane: codGameValue,
        nameGame: nameGameValue
    };

    axios.post('/games', data)
        .then(response => {
            if (response.data.success) {
                alert('Jogo cadastrado com sucesso');
                window.location.href = '/games';
            } else {
                alert('Jogo já cadastrado');
            }
        })
        .catch(error => {
            alert('Erro ao cadastrar jogo');
        });
});