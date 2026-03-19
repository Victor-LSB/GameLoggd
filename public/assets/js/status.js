// Status do Jogo
const formStatus = document.querySelectorAll('.formStatus');
// Avaliação do Jogo
const ratingForm = document.querySelectorAll('.ratingForm');
// Formulário de Pesquisa
const searchInput = document.getElementById('searchInput');
const gameList = document.querySelectorAll('.gameItem');
// Filtro de Status
const filterStatus = document.querySelector('.filterStatus');
// Mensagem de mudança de status
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end', // Canto superior direito
  showConfirmButton: false, // Sem botão de "OK"
  timer: 3000 // Desaparece em 3 segundos
});

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
            Toast.fire({
                icon: 'success',
                title: 'Status atualizado com sucesso!'
            });
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
            Toast.fire({
                icon: 'success',
                title: 'Avaliação atualizada com sucesso!'
            });
            const cardGame = document.getElementById(`game-${gameId}`);
            const pRating = cardGame.querySelector('.pRating');
            pRating.textContent = 'Avaliação: ' + (newRating ? newRating : 'Não avaliado');
        })
    })
})

// Filtro de Status
filterStatus.addEventListener('change', function(event) {
    event.preventDefault();
    const selectedStatus = filterStatus.value;

    gameList.forEach(function(game) {
        const status = game.querySelector('.gameStatus').textContent.replace('Status: ', '');
        if (selectedStatus === '' || status === selectedStatus) {
            game.style.display = 'block';
        } else {
            game.style.display = 'none';
        }
    })
})

// Pesquisa de Jogos
searchInput.addEventListener('input', function() {
    const termoPesquisa = searchInput.value.toLowerCase();

    gameList.forEach(function(game) {
        const titulo = game.querySelector('h3').textContent.toLowerCase();
        if (titulo.includes(termoPesquisa)) {
            game.style.display = 'block';
        } else {
            game.style.display = 'none';
        }
    })
})

