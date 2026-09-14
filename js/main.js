// Funções principais compartilhadas

function createSession() {
    const sessionName = document.getElementById('sessionName')?.value.trim();
    const smName = document.getElementById('smName')?.value.trim();
    
    if (!sessionName || !smName) {
        alert('Por favor, preencha todos os campos');
        return;
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=create_session', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({name: sessionName, sm_name: smName}),
        signal: controller.signal
    })
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success) {
            window.location.href = 'sm.php';
        } else {
            alert(data.message || 'Erro ao criar sessão');
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        alert('Erro ao criar sessão');
    });
}

function joinSession() {
    const code = document.getElementById('sessionCode')?.value.trim().toUpperCase();
    const name = document.getElementById('teamMemberName')?.value.trim();
    
    if (!code || !name) {
        alert('Por favor, preencha todos os campos');
        return;
    }
    
    if (code.length !== 6) {
        alert('O código deve ter 6 caracteres');
        return;
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=join_session', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({code, name}),
        signal: controller.signal
    })
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success) {
            window.location.href = 'vote.php';
        } else {
            alert(data.message || 'Erro ao entrar na sessão');
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        alert('Erro ao entrar na sessão');
    });
}

function loadParticipants() {
    const list = document.getElementById('participantsList');
    if (!list) return;
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=get_participants', {signal: controller.signal})
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success && data.participants) {
            if (data.participants.length === 0) {
                list.innerHTML = '<div class="text-center text-muted small">Nenhum participante</div>';
            } else {
                list.innerHTML = data.participants.map(name => 
                    `<div class="participant-item"><i class="fas fa-circle"></i> ${name}</div>`
                ).join('');
            }
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        console.error('Erro ao carregar participantes:', err);
    });
}

function updateActivity() {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=update_activity', {method: 'POST', signal: controller.signal})
    .finally(() => clearTimeout(timeoutId));
}

// Inicializar se em sessão
if (typeof sessionId !== 'undefined') {
    setInterval(updateActivity, 10000);
    setInterval(loadParticipants, 5000);
    updateActivity();
    loadParticipants();
}
