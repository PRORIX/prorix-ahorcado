<div class="card">
  <p>Bienvenido. Puedes iniciar una partida nueva, continuar la ultima en curso o abrir una partida con su identificador.</p>

  <?php if(!empty($resumeError)): ?>
    <p class="alert error"><?= htmlspecialchars($resumeError) ?></p>
  <?php endif; ?>

  <div class="home-actions">
    <a class="btn" href="?action=new">Nueva partida</a>
    <a class="btn secondary" href="?action=resume">Continuar ultima partida</a>
  </div>

  <form class="resume-form" method="post" action="?action=resume">
    <label for="resume-id">Abrir partida por ID</label>
    <div class="resume-form__controls">
      <input id="resume-id" name="game_id" type="text" placeholder="Ej. a1b2c3d4" required>
      <button class="btn" type="submit">Abrir</button>
    </div>
  </form>

  <?php if(!empty($currentId)): ?>
    <p class="dim">Ultimo ID activo: <code><?= htmlspecialchars($currentId) ?></code></p>
  <?php endif; ?>
</div>
