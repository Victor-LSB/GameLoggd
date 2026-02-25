const formStatus = document.querySelectorAll('.formStatus');

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
                window.location.reload();
        })
    })
})