// Funções específicas da página de votação

let currentStoryForVoting = null;
let myVote = null;

function loadCurrentStory() {
    fetch('api/api.php?action=get_stories')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Encontrar primeira história pendente
                const pendingStory = data.stories.find(s => s.status === 'pending');
                
                const currentStoryDiv = document.getElementById('currentStory');
                
                if (pendingStory) {
                    currentStoryForVoting = pendingStory.id;
                    currentStoryDiv.innerHTML = `
                        <h3>#${pendingStory.number} - ${pendingStory.title}</h3>
                        ${pendingStory.description ? `<p class="story-description">${pendingStory.description}</p>` : ''}
                        <p class="vote-count-info">${pendingStory.vote_count} voto(s) recebido(s)</p>
                    `;
                    checkMyVote(pendingStory.id);
                } else {
                    currentStoryForVoting = null;
                    currentStoryDiv.innerHTML = '<p class="waiting-message">Aguardando o Scrum Master iniciar uma votação...</p>';
                    clearVoteStatus();
                }
            }
        })
        .catch(error => console.error('Erro ao carregar história atual:', error));
}

function checkMyVote(storyId) {
    fetch(`api/api.php?action=get_votes&story_id=${storyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const myVoteData = data.votes.find(v => v.voter_name === userName);
                if (myVoteData) {
                    myVote = myVoteData.points;
                    updateVoteStatus();
                    highlightSelectedCard();
                } else {
                    myVote = null;
                    clearVoteStatus();
                }
            }
        });
}

function vote(points) {
    if (!currentStoryForVoting) {
        alert('Nenhuma história disponível para votação no momento');
        return;
    }
    
    fetch('api/api.php?action=vote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            story_id: currentStoryForVoting,
            points: points
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            myVote = points;
            updateVoteStatus();
            highlightSelectedCard();
            loadCurrentStory();
        } else {
            alert('Erro ao registrar voto');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao registrar voto');
    });
}

function updateVoteStatus() {
    const status = document.getElementById('voteStatus');
    if (myVote !== null) {
        status.innerHTML = `<span class="vote-confirmed">✓ Seu voto: ${myVote} pontos</span>`;
        status.style.display = 'block';
    }
}

function clearVoteStatus() {
    const status = document.getElementById('voteStatus');
    status.innerHTML = '';
    status.style.display = 'none';
    myVote = null;
    
    // Remover highlight de todos os cards
    document.querySelectorAll('.poker-card').forEach(card => {
        card.classList.remove('selected');
    });
}

function highlightSelectedCard() {
    document.querySelectorAll('.poker-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    if (myVote !== null) {
        const cards = document.querySelectorAll('.poker-card');
        const values = [0.5, 1, 3, 5, 8, 13];
        const index = values.indexOf(myVote);
        if (index >= 0 && cards[index]) {
            cards[index].classList.add('selected');
        }
    }
}

function loadVotingResults() {
    if (!currentStoryForVoting) return;
    
    fetch(`api/api.php?action=get_votes&story_id=${currentStoryForVoting}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.votes.length > 0) {
                const resultsDiv = document.getElementById('votingResults');
                
                // Mostrar apenas contagem de votos, não os valores
                resultsDiv.innerHTML = `
                    <p><strong>Total de votos:</strong> ${data.votes.length}</p>
                    <p class="info-message">Os votos serão revelados pelo Scrum Master</p>
                `;
            }
        });
}

// Atualizar dados automaticamente
setInterval(() => {
    loadCurrentStory();
    loadParticipants();
    loadVotingResults();
}, 3000);

// Carregar dados iniciais
loadCurrentStory();
loadVotingResults();
