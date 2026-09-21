(() => {
  const body = document.getElementById('leaderboardBody');
  const podium = document.getElementById('podiumList');
  if (!body || !podium) return;

  fetch('api/leaderboard.php', { credentials: 'same-origin' })
    .then(async response => {
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'Could not load leaderboard.');
      return data;
    })
    .then(data => {
      if (!data.players.length) {
        body.innerHTML = '<tr><td colspan="5" class="text-center py-5 muted-text">No scores yet. Sign in and complete a quiz to create the first ranking.</td></tr>';
        podium.innerHTML = '<div class="small-muted">No ranked players yet.</div>';
        return;
      }

      podium.innerHTML = data.players.slice(0, 3).map((player, index) => `
        <div class="podium-item">
          <span class="podium-rank">${String(index + 1).padStart(2, '0')}</span>
          <div><span class="podium-name">${escapeHtml(player.username)}</span><span class="podium-meta">${Number(player.quizzes)} quizzes · best ${Number(player.best_percent)}%</span></div>
          <span class="podium-score">${Number(player.total_score)} pts</span>
        </div>`).join('');

      body.innerHTML = data.players.map((player, index) => `
        <tr>
          <td><span class="rank-badge">${String(index + 1).padStart(2, '0')}</span></td>
          <td><strong>${escapeHtml(player.username)}</strong></td>
          <td>${Number(player.quizzes)}</td>
          <td><span class="result-tag">${Number(player.best_percent)}%</span></td>
          <td class="text-end"><strong>${Number(player.total_score)} pts</strong></td>
        </tr>`).join('');
    })
    .catch(error => {
      body.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-danger">${escapeHtml(error.message)}</td></tr>`;
      podium.innerHTML = '<div class="text-danger small">Check the database connection.</div>';
    });

  function escapeHtml(value) {
    return String(value).replace(/[&<>\'\"]/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[ch]));
  }
})();
