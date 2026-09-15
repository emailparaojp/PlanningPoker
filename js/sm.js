// Funções específicas do Scrum Master

let currentStoryId = null;
let votesRevealed = false;
let votingModal = null;

function initModal() {
    votingModal = new bootstrap.Modal(document.getElementById('votingModal'));
}

function createStory() {
    const title = document.getElementById('storyTitle').value.trim();
    const description = document.getElementById('storyDescription').value.trim();
    
    if (title.length < 3) {
        alert('O título deve ter pelo menos 3 caracteres');
        return;
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=create_story', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({title, description}),
        signal: controller.signal
    })
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success) {
            document.getElementById('storyTitle').value = '';
            document.getElementById('storyDescription').value = '';
            loadStories();
        } else {
            alert(data.message || 'Erro ao criar história');
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        alert('Erro ao criar história');
    });
}

function loadStories() {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=get_stories', {signal: controller.signal})
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success && data.stories) {
            const list = document.getElementById('storiesList');
            if (data.stories.length === 0) {
                list.innerHTML = '<div class="text-center text-muted"><i class="fas fa-inbox"></i> Nenhuma história criada</div>';
            } else {
                list.innerHTML = data.stories.map(s => `
                    <div class="story-item ${s.status === 'completed' ? 'completed' : ''}">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <h6 class="mb-2">#${s.number} - ${s.title}</h6>
                                ${s.description ? `<p class="text-muted small mb-2">${s.description}</p>` : ''}
                                <span class="badge ${s.status === 'completed' ? 'badge-completed' : 'badge-pending'}">
                                    ${s.status === 'completed' ? `✓ ${s.final_points} pts` : 'Pendente'}
                                </span>
                                <span class="ms-2 small text-muted">${s.vote_count} voto(s)</span>
                            </div>
                            ${s.status !== 'completed' ? `
                                <button class="btn btn-sm btn-primary" onclick="openVotingModal(${s.id})">
                                    <i class="fas fa-eye"></i> Gerenciar
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `).join('');
            }
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        console.error('Erro ao carregar histórias:', err);
    });
}

function openVotingModal(storyId) {
    currentStoryId = storyId;
    votesRevealed = false;
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=get_stories', {signal: controller.signal})
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success && data.stories) {
            const story = data.stories.find(s => s.id === storyId);
            if (story) {
                document.getElementById('modalStoryTitle').textContent = `#${story.number} - ${story.title}`;
                document.getElementById('modalStoryDescription').textContent = story.description || '(sem descrição)';
                document.getElementById('revealBtn').disabled = false;
                loadVotes();
                votingModal.show();
            }
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        alert('Erro ao abrir votação');
    });
}

function closeVotingModal() {
    votingModal.hide();
    currentStoryId = null;
    loadStories();
}

function loadVotes() {
    if (!currentStoryId) return;
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch(`api/api.php?action=get_votes&story_id=${currentStoryId}`, {signal: controller.signal})
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success) {
            const display = document.getElementById('votesDisplay');
            
            if (!data.votes || data.votes.length === 0) {
                display.innerHTML = '<div class="text-center text-muted"><i class="fas fa-hourglass-half"></i> Aguardando votos...</div>';
            } else {
                if (votesRevealed) {
                    const points = data.votes.map(v => v.points);
                    const avg = points.reduce((a, b) => a + b, 0) / points.length;
                    const sorted = [...points].sort((a, b) => a - b);
                    const median = sorted.length % 2 === 0 
                        ? (sorted[sorted.length/2 - 1] + sorted[sorted.length/2]) / 2 
                        : sorted[Math.floor(sorted.length/2)];
                    
                    display.innerHTML = `
                        <div class="row">
                            ${data.votes.map(v => `
                                <div class="col-md-6 mb-2">
                                    <div class="vote-card">
                                        <div class="small text-muted">${v.voter_name}</div>
                                        <div style="font-size: 24px; font-weight: 700;">${v.points}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <div class="results-box mt-3">
                            <div class="stat-item"><span>Votos:</span> <strong>${data.votes.length}</strong></div>
                            <div class="stat-item"><span>Média:</span> <strong>${avg.toFixed(1)}</strong></div>
                            <div class="stat-item"><span>Mediana:</span> <strong>${median}</strong></div>
                        </div>
                    `;
                } else {
                    display.innerHTML = `
                        <div class="row">
                            ${data.votes.map((v, i) => `
                                <div class="col-md-6 mb-2">
                                    <div class="vote-card hidden">
                                        <div class="small text-muted">${v.voter_name}</div>
                                        <div style="font-size: 24px; font-weight: 700;">✓</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }
            }
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        console.error('Erro ao carregar votos:', err);
    });
}

function revealVotes() {
    votesRevealed = true;
    loadVotes();
    document.getElementById('revealBtn').textContent = '✓ Votos Revelados';
    document.getElementById('revealBtn').disabled = true;
}

function resetVoting() {
    if (!currentStoryId) return;
    
    if (!confirm('Tem certeza que deseja reiniciar a votação?')) return;
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    const formData = new FormData();
    formData.append('story_id', currentStoryId);
    
    fetch('api/api.php?action=reset_votes', {
        method: 'POST',
        body: formData,
        signal: controller.signal
    })
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success) {
            votesRevealed = false;
            document.getElementById('revealBtn').textContent = '✓ Revelar Votos';
            document.getElementById('revealBtn').disabled = false;
            loadVotes();
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        alert('Erro ao reiniciar votação');
    });
}

function finalizeStory() {
    if (!currentStoryId) return;
    
    const finalPoints = prompt('Digite a pontuação final da história:');
    if (finalPoints === null) return;
    
    if (isNaN(finalPoints) || finalPoints < 0) {
        alert('Digite um número válido');
        return;
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);
    
    fetch('api/api.php?action=finalize_story', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({story_id: currentStoryId, final_points: parseFloat(finalPoints)}),
        signal: controller.signal
    })
    .then(r => r.json())
    .then(data => {
        clearTimeout(timeoutId);
        if (data.success) {
            alert('História finalizada!');
            closeVotingModal();
        } else {
            alert(data.message || 'Erro ao finalizar');
        }
    })
    .catch(err => {
        clearTimeout(timeoutId);
        alert('Erro ao finalizar história');
    });
}

// Atualizar votos automaticamente
setInterval(() => {
    if (currentStoryId && document.getElementById('votingModal').classList.contains('show')) {
        loadVotes();
    }
}, 5000);

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    initModal();
    loadStories();
});

// Recarregar histórias a cada 5s
setInterval(loadStories, 5000);
