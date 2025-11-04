function alterarStatus(id, novoStatus) {
    fetch('atualizar_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&ativo=${novoStatus}`
    })
    .then(res => res.text())
    .then(data => {
        if (data.trim() === 'ok') {
            location.reload();
        } else {
            alert('Erro ao atualizar status!');
        }
    });
}
