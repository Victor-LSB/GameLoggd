// Status do Jogo
const formStatus = document.querySelectorAll('.formStatus');
// Avaliação do Jogo
const ratingForm = document.querySelectorAll('.ratingForm');

// Status do Jogo
formStatus.forEach(function(form) {
    form.addEventListener('submit', function(event){
        event.preventDefault();

        const dados = new FormData(form);

        const gameId = dados.get('game_id');
        const newStatus = dados.get('status');

        fetch('index.php?action=change_status', {
            method: 'POST',
            body: dados
            })
        .then(function(resposta) {
            alert('Status atualizado com sucesso!');
            const cardGame = document.getElementById(`game-${gameId}`);
            const pStatus = cardGame.querySelector('.gameStatus');
            pStatus.textContent = 'Status: ' + newStatus;
        })
            
    })
})

// Avaliação do Jogo
ratingForm.forEach(function(form) {
    form.addEventListener('change', function(event){
        event.preventDefault();

        const dados = new FormData(form);

        const gameId = dados.get("game_id");
        const newRating = dados.get("rating");

        fetch('index.php?action=change_rating', {
            method: 'POST',
            body: dados
            })
        .then(function(resposta) {
            alert('Avaliação atualizada com sucesso!');
            const cardGame = document.getElementById(`game-${gameId}`);
            const pRating = cardGame.querySelector('.pRating');
            pRating.textContent = 'Avaliação: ' + (newRating ? newRating : 'Não avaliado');
        })
    })
})