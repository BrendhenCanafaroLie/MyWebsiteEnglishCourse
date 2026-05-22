<?php if (!empty($flash)): ?>
<div class="flash flash-<?= e($flash['type']) ?>">
  <span class="flash-icon"><?= $flash['type'] === 'success' ? '✅' : '❌' ?></span>
  <span><?= $flash['message'] ?></span>
  <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
</div>
<?php endif; ?>
