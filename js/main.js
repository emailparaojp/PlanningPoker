// Funções principais compartilhadas

function createSession() {
    const sessionName = document.getElementById('sessionName').value.trim();
    const smName = document.getElementById('smName').value.trim();
    
    if (!sessionName || !smName) {
        alert('Por favor, preencha todos os campos');
        return;
    }
    
    fetch('api/api.php?action=create_session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: sessionName,
            sm_name: smName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'sm.php';
        } else {
            alert(data.message || 'Erro ao criar sessão');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao criar sessão');
    });
}

function joinSession() {
    const sessionCode = document.getElementById('sessionCode').value.trim();
    const teamMemberName = document.getElementById('teamMemberName').value.trim();
    
    if (!sessionCode || !teamMemberName) {
        alert('Por favor, preencha todos os campos');
        return;
    }
    
    fetch('api/api.php?action=join_session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            code: sessionCode,
            name: teamMemberName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'vote.php';
        } else {
            alert(data.message || 'Erro ao entrar na sessão');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao entrar na sessão');
    });
}

function loadParticipants() {
    fetch('api/api.php?action=get_participants')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const list = document.getElementById('participantsList');
                if (list) {
                    if (data.participants.length === 0) {
                        list.innerHTML = '<p class="empty-message">Nenhum participante online</p>';
                    } else {
                        list.innerHTML = data.participants.map(name => 
                            `<div class="participant-item"><span class="status-indicator"></span>${name}</div>`
                        ).join('');
                    }
                }
            }
        })
        .catch(error => console.error('Erro ao carregar participantes:', error));
}

function updateActivity() {
    fetch('api/api.php?action=update_activity', { method: 'POST' });
}

// Atualizar atividade a cada 10 segundos
if (typeof sessionId !== 'undefined') {
    setInterval(updateActivity, 10000);
    setInterval(loadParticipants, 5000);
    loadParticipants();
}
