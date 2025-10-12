<?php
/** @var \App\Domain\Entity\Game $game */
$masked = $game->maskedWord();
$wrong = $game->wrongGuesses();
$wrongCount = $game->wrongGuessesCount();
$status = $game->getStatus();
$imageIndex = $status === \App\Domain\Entity\Game::STATUS_LOST ? 6 : min($wrongCount, 6);
$imagePath = "/images/ahorcado/images/imagenEstado{$imageIndex}.png";
?>
<div class="card">
  <div class="hangman-state">
    <img src="<?= htmlspecialchars($imagePath) ?>" alt="Estado del ahorcado" class="hangman-img">
  </div>
  <p>Partida: <code><?= htmlspecialchars($game->getId()) ?></code></p>
  <p>Palabra: <strong style="letter-spacing:4px;font-size:22px"><?= htmlspecialchars($masked) ?></strong></p>
  <p>Fallos:
    <?php foreach ($wrong as $w): ?>
      <span class="pill"><?= htmlspecialchars($w) ?></span>
    <?php endforeach; ?>
    <span class="dim"> (Restan <?= $game->remainingAttempts() ?> intentos de <?= $game->getMaxAttempts() ?>)</span>
  </p>

  <?php if ($status === \App\Domain\Entity\Game::STATUS_IN_PROGRESS): ?>
    <form class="inline" method="post" action="?action=guess">
      <input type="hidden" name="id" value="<?= htmlspecialchars($game->getId()) ?>">
      <label for="letter">Letra:</label>
      <input id="letter" name="letter" maxlength="1" required>
      <button class="btn" type="submit">Probar</button>
      <a class="btn secondary" href="?action=new">Reiniciar</a>
    </form>
  <?php elseif ($status === \App\Domain\Entity\Game::STATUS_WON): ?>
    <p class="won">Has ganado. La palabra era <strong><?= htmlspecialchars($game->getWord()) ?></strong>.</p>
    <div class="action-row">
      <a class="btn" href="?action=new">Jugar de nuevo</a>
      <a class="btn secondary" href="?action=home">Volver al menu</a>
    </div>
  <?php else: ?>
    <p class="lost">Has perdido. La palabra era <strong><?= htmlspecialchars($game->getWord()) ?></strong>.</p>
    <div class="action-row">
      <a class="btn" href="?action=new">Intentarlo otra vez</a>
      <a class="btn secondary" href="?action=home">Volver al menu</a>
    </div>
  <?php endif; ?>
</div>
