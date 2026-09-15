// Funções específicas da página de votação

let currentStoryForVoting = null;
let myVote = null;

const VOTING_OPTIONS = [
    {value: 0.5, label: '0.5', desc: 'Apertar parafuso'},
    {value: 1, label: '1', desc: 'Trocar lâmpada'},
    {value: 3, label: '3', desc: 'Trocar pisos'},
    {value: 5, label: '5', desc: 'Construir banheiro'},
    {value: 8, label: '8', desc: 'Construir casa'},
    {value: 13, label: '13+', desc: 'Precisa quebrar'}
];

function initVotingCards() {
    const container = document.getElementById('votingCards');
    container.innerHTML = VOTING_OPTIONS.map(opt => `
        <div class="poker-card" onclick="vote(${opt.value})">
            <div class="card-value">${opt.label}</div>
            <div class="card-description">${opt.desc}</div>
        </div>
    `).join('');
}

function loadCurrentStory() {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=get_stories', {signal: controller.signal})
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success && data.stories) {
            const pending = data.stories.find(s => s.status === 'pending');
            const div = document.getElementById('currentStory');
            
            if (pending) {
                currentStoryForVoting = pending.id;
                div.innerHTML = `
                    <h5 class="mb-2" style="color: var(--primary); font-weight: 600;">#${pending.number} - ${pending.title}</h5>
                    ${pending.description ? `<p class="text-muted small mb-2">${pending.description}</p>` : ''}
                    <small class="text-muted"><i class="fas fa-users"></i> ${pending.vote_count} voto(s) recebido(s)</small>
                `;
                checkMyVote(pending.id);
            } else {
                currentStoryForVoting = null;
                div.innerHTML = `<div class="text-center text-muted"><i class="fas fa-hourglass-half fa-2x mb-2"></i><p>Aguardando o Scrum Master apresentar uma história...</p></div>`;
                clearVoteStatus();
            }
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        console.error('Erro ao carregar história:', err);
    });
}

function checkMyVote(storyId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch(`api/api.php?action=get_votes&story_id=${storyId}`, {signal: controller.signal})
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success && data.votes) {
            const found = data.votes.find(v => v.voter_name === userName);
            if (found) {
                myVote = found.points;
                updateVoteStatus();
                highlightSelectedCard();
            } else {
                myVote = null;
                clearVoteStatus();
            }
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
    });
}

function vote(points) {
    if (!currentStoryForVoting) {
        alert('Nenhuma história disponível para votação');
        return;
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=vote', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({story_id: currentStoryForVoting, points}),
        signal: controller.signal
    })
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success) {
            myVote = points;
            updateVoteStatus();
            highlightSelectedCard();
            loadCurrentStory();
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        alert('Erro ao registrar voto');
    });
}

function updateVoteStatus() {
    const status = document.getElementById('voteStatus');
    if (myVote !== null) {
        status.innerHTML = `<div class="vote-status"><i class="fas fa-check-circle"></i> Seu voto: <strong>${myVote} pontos</strong></div>`;
    } else {
        status.innerHTML = '';
    }
}

function clearVoteStatus() {
    document.getElementById('voteStatus').innerHTML = '';
    myVote = null;
    document.querySelectorAll('.poker-card').forEach(c => c.classList.remove('selected'));
}

function highlightSelectedCard() {
    document.querySelectorAll('.poker-card').forEach(c => c.classList.remove('selected'));
    if (myVote !== null) {
        const options = [0.5, 1, 3, 5, 8, 13];
        const idx = options.indexOf(myVote);
        if (idx >= 0) {
            const cards = document.querySelectorAll('.poker-card');
            if (cards[idx]) cards[idx].classList.add('selected');
        }
    }
}

function loadVotingResults() {
    if (!currentStoryForVoting) return;
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch(`api/api.php?action=get_votes&story_id=${currentStoryForVoting}`, {signal: controller.signal})
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success && data.votes && data.votes.length > 0) {
            document.getElementById('votingResults').innerHTML = `
                <div class="results-box">
                    <div class="stat-item">
                        <span><i class="fas fa-vote-yea"></i> Total de votos:</span>
                        <strong>${data.votes.length}</strong>
                    </div>
                    <p class="small text-muted mt-2"><i class="fas fa-lock"></i> Os votos serão revelados pelo Scrum Master</p>
                </div>
            `;
        } else {
            document.getElementById('votingResults').innerHTML = `<p class="results-hidden"><i class="fas fa-hourglass-half"></i> Nenhum voto registrado</p>`;
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initVotingCards();
    loadCurrentStory();
    loadVotingResults();
    loadParticipants();
});

// Atualizar a cada 5s
setInterval(() => {
    loadCurrentStory();
    loadVotingResults();
    loadParticipants();
}, 5000);
