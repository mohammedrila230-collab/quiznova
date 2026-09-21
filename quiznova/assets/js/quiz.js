(() => {
  const quizCard = document.getElementById('quizCard');
  if (!quizCard) return;

  const params = new URLSearchParams(window.location.search);
  const category = params.get('category') || 'General Knowledge';
  const difficulty = params.get('difficulty') || 'Easy';

  const categoryName = document.getElementById('categoryName');
  const difficultyName = document.getElementById('difficultyName');
  const questionText = document.getElementById('questionText');
  const questionTrack = document.getElementById('questionTrack');
  const questionNumberLabel = document.getElementById('questionNumberLabel');
  const optionsWrap = document.getElementById('quizOptions');
  const counter = document.getElementById('questionCounter');
  const progress = document.getElementById('quizProgress');
  const timerRing = document.getElementById('timerRing');
  const timerText = document.getElementById('timerText');
  const feedback = document.getElementById('quizFeedback');
  const nextBtn = document.getElementById('nextQuestion');
  const scoreText = document.getElementById('liveScore');
  const loading = document.getElementById('quizLoading');
  const resultPanel = document.getElementById('resultPanel');
  const resultPercent = document.getElementById('resultPercent');
  const resultHeading = document.getElementById('resultHeading');
  const resultText = document.getElementById('resultText');
  const saveStatus = document.getElementById('saveStatus');

  categoryName.textContent = category;
  difficultyName.textContent = difficulty;

  let questions = [];
  let index = 0;
  let score = 0;
  let answered = false;
  let remaining = 20;
  let timerId = null;

  fetch(`api/questions.php?category=${encodeURIComponent(category)}&difficulty=${encodeURIComponent(difficulty)}`, { credentials: 'same-origin' })
    .then(async response => {
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'Could not load questions.');
      return data;
    })
    .then(data => {
      questions = data.questions;
      loading.classList.add('d-none');
      quizCard.classList.remove('d-none');
      renderQuestion();
    })
    .catch(error => {
      loading.innerHTML = `<div class="alert alert-danger">${escapeHtml(error.message)}</div><a class="btn btn-surface" href="categories.html">Back to categories</a>`;
    });

  function startTimer() {
    clearInterval(timerId);
    remaining = 20;
    paintTimer();
    timerId = setInterval(() => {
      remaining -= 1;
      paintTimer();
      if (remaining <= 0) {
        clearInterval(timerId);
        revealAnswer(null);
      }
    }, 1000);
  }

  function paintTimer() {
    const pct = Math.max(0, (remaining / 20) * 100);
    timerRing.style.setProperty('--timer', `${pct}%`);
    timerText.textContent = `${remaining}s`;
  }

  function renderQuestion() {
    answered = false;
    feedback.textContent = '';
    nextBtn.disabled = true;
    nextBtn.innerHTML = 'Next question <i class="bi bi-arrow-right"></i>';
    const q = questions[index];
    counter.textContent = `Question ${index + 1} of ${questions.length}`;
    questionTrack.textContent = `${category} / ${difficulty}`;
    questionNumberLabel.textContent = `Question ${String(index + 1).padStart(2, '0')}`;
    questionText.textContent = q.question;
    progress.style.width = `${(index / questions.length) * 100}%`;
    optionsWrap.innerHTML = '';

    ['A', 'B', 'C', 'D'].forEach(letter => {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'quiz-option';
      button.dataset.letter = letter;
      button.innerHTML = `<span class="option-letter">${letter}</span><span>${escapeHtml(q[`option_${letter.toLowerCase()}`])}</span>`;
      button.addEventListener('click', () => revealAnswer(letter));
      optionsWrap.appendChild(button);
    });
    startTimer();
  }

  function revealAnswer(selected) {
    if (answered) return;
    answered = true;
    clearInterval(timerId);
    const q = questions[index];
    const correct = q.correct_answer;
    const correctText = q[`option_${correct.toLowerCase()}`];

    optionsWrap.querySelectorAll('.quiz-option').forEach(btn => {
      btn.disabled = true;
      if (btn.dataset.letter === correct) btn.classList.add('correct');
      if (selected && btn.dataset.letter === selected && selected !== correct) btn.classList.add('wrong');
    });

    if (selected === correct) {
      score += 1;
      feedback.textContent = 'Correct answer. One point has been added to your score.';
    } else if (selected === null) {
      feedback.textContent = `Time expired. The correct answer was ${correct}: ${correctText}.`;
    } else {
      feedback.textContent = `Not quite. The correct answer was ${correct}: ${correctText}.`;
    }

    scoreText.textContent = `${score} pts`;
    nextBtn.disabled = false;
    if (index === questions.length - 1) {
      nextBtn.innerHTML = 'View result <i class="bi bi-arrow-right"></i>';
    }
  }

  nextBtn.addEventListener('click', () => {
    if (!answered) return;
    if (index === questions.length - 1) {
      finishQuiz();
      return;
    }
    index += 1;
    renderQuestion();
  });

  async function finishQuiz() {
    clearInterval(timerId);
    progress.style.width = '100%';
    quizCard.classList.add('d-none');
    resultPanel.classList.remove('d-none');
    const percentage = Math.round((score / questions.length) * 100);
    resultPercent.textContent = `${percentage}%`;
    resultHeading.textContent = resultHeadingFor(percentage);
    resultText.textContent = `You scored ${score} out of ${questions.length} in ${category} / ${difficulty}. ${performanceMessage(percentage)}`;
    resultPanel.querySelector('.result-ring')?.style.setProperty('--result', `${percentage}%`);

    try {
      const response = await fetch('api/save_result.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify({ category, difficulty, score, total: questions.length })
      });
      const data = await response.json();
      saveStatus.textContent = data.message;
      saveStatus.className = data.saved ? 'text-success mt-3' : 'section-subtitle mt-3';
    } catch (error) {
      saveStatus.textContent = 'Score could not be saved. Check that WAMP and MySQL are running.';
      saveStatus.className = 'text-danger mt-3';
    }
  }

  function resultHeadingFor(percent) {
    if (percent === 100) return 'Perfect score.';
    if (percent >= 80) return 'Excellent performance.';
    if (percent >= 60) return 'Strong result.';
    return 'Good attempt. Keep improving.';
  }

  function performanceMessage(percent) {
    if (percent >= 80) return 'You showed strong control of this topic.';
    if (percent >= 60) return 'A little more practice can push this result higher.';
    return 'Review the topic and try the challenge again.';
  }

  function escapeHtml(value) {
    return String(value).replace(/[&<>\'\"]/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[ch]));
  }
})();
