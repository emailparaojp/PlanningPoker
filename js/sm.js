// Funções específicas do Scrum Master

let currentStoryId = null;
let votesRevealed = false;

function createStory() {
    const title = document.getElementById('storyTitle').value.trim();
    const description = document.getElementById('storyDescription').value.trim();
    
    if (!title) {
        alert('Por favor, digite um título para a história');
        return;
    }
    
    fetch('api/api.php?action=create_story', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            title: title,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('storyTitle').value = '';
            document.getElementById('storyDescription').value = '';
            loadStories();
            alert('História criada com sucesso!');
        } else {
            alert(data.message || 'Erro ao criar história');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao criar história');
    });
}

function loadStories() {
    fetch('api/api.php?action=get_stories')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const list = document.getElementById('storiesList');
                if (data.stories.length === 0) {
                    list.innerHTML = '<p class="empty-message">Nenhuma história criada ainda</p>';
                } else {
                    list.innerHTML = data.stories.map(story => {
                        const statusBadge = story.status === 'completed' 
                            ? `<span class="badge badge-success">Finalizada - ${story.final_points} pts</span>` 
                            : `<span class="badge badge-pending">Pendente</span>`;
                        
                        return `
                            <div class="story-item ${story.status}">
                                <div class="story-header">
                                    <h3>#${story.number} - ${story.title}</h3>
                                    ${statusBadge}
                                </div>
                                ${story.description ? `<p class="story-description">${story.description}</p>` : ''}
                                <div class="story-actions">
                                    <span class="vote-count">${story.vote_count} voto(s)</span>
                                    ${story.status !== 'completed' ? 
                                        `<button onclick="openVotingModal(${story.id})" class="btn btn-small">Gerenciar Votação</button>` 
                                        : ''}
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            }
        })
        .catch(error => console.error('Erro ao carregar histórias:', error));
}

function openVotingModal(storyId) {
    currentStoryId = storyId;
    votesRevealed = false;
    
    fetch('api/api.php?action=get_stories')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const story = data.stories.find(s => s.id === storyId);
                if (story) {
                    document.getElementById('modalStoryTitle').textContent = `#${story.number} - ${story.title}`;
                    document.getElementById('modalStoryDescription').textContent = story.description || '';
                    document.getElementById('votingModal').style.display = 'block';
                    loadVotes();
                }
            }
        });
}

function closeVotingModal() {
    document.getElementById('votingModal').style.display = 'none';
    currentStoryId = null;
    loadStories();
}

function loadVotes() {
    if (!currentStoryId) return;
    
    fetch(`api/api.php?action=get_votes&story_id=${currentStoryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const display = document.getElementById('votesDisplay');
                
                if (data.votes.length === 0) {
                    display.innerHTML = '<p class="empty-message">Aguardando votos...</p>';
                } else {
                    if (votesRevealed) {
                        // Calcular estatísticas
                        const points = data.votes.map(v => v.points);
                        const avg = points.reduce((a, b) => a + b, 0) / points.length;
                        const sorted = [...points].sort((a, b) => a - b);
                        const median = sorted.length % 2 === 0 
                            ? (sorted[sorted.length/2 - 1] + sorted[sorted.length/2]) / 2 
                            : sorted[Math.floor(sorted.length/2)];
                        
                        display.innerHTML = `
                            <div class="votes-revealed">
                                ${data.votes.map(vote => `
                                    <div class="vote-card">
                                        <div class="voter-name">${vote.voter_name}</div>
                                        <div class="vote-points">${vote.points}</div>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="vote-stats">
                                <p><strong>Média:</strong> ${avg.toFixed(1)} pontos</p>
                                <p><strong>Mediana:</strong> ${median} pontos</p>
                            </div>
                        `;
                    } else {
                        display.innerHTML = `
                            <div class="votes-hidden">
                                ${data.votes.map(vote => `
                                    <div class="vote-card hidden">
                                        <div class="voter-name">${vote.voter_name}</div>
                                        <div class="vote-hidden">✓</div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }
                }
            }
        })
        .catch(error => console.error('Erro ao carregar votos:', error));
}

function revealVotes() {
    votesRevealed = true;
    loadVotes();
    document.getElementById('revealBtn').disabled = true;
}

function resetVoting() {
    if (!currentStoryId) return;
    
    if (!confirm('Tem certeza que deseja reiniciar a votação? Todos os votos serão perdidos.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('story_id', currentStoryId);
    
    fetch('api/api.php?action=reset_votes', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            votesRevealed = false;
            document.getElementById('revealBtn').disabled = false;
            loadVotes();
            alert('Votação reiniciada!');
        }
    })
    .catch(error => console.error('Erro ao reiniciar votação:', error));
}

function finalizeStory() {
    if (!currentStoryId) return;
    
    const finalPoints = prompt('Digite a pontuação final da história:');
    
    if (finalPoints === null) return;
    
    if (isNaN(finalPoints) || finalPoints < 0) {
        alert('Por favor, digite um número válido');
        return;
    }
    
    fetch('api/api.php?action=finalize_story', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            story_id: currentStoryId,
            final_points: parseFloat(finalPoints)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('História finalizada com sucesso!');
            closeVotingModal();
        }
    })
    .catch(error => console.error('Erro ao finalizar história:', error));
}

// Atualizar votos automaticamente se modal estiver aberto
setInterval(() => {
    if (currentStoryId && document.getElementById('votingModal').style.display === 'block') {
        loadVotes();
    }
}, 3000);

// Carregar histórias ao iniciar
loadStories();
