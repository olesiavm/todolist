<div class="btn-group">
    <a class="btn rounded theme-button" href="/show-tasks">Список задач</a>
	<?php if (!isset($_SESSION['auth'])): ?>
		<a class="btn rounded theme-button login" href="/authentication">Войти</a>
	<?php else: ?>
		<a class="btn rounded theme-button" href="/logout">Выход</a>
	<?php endif; ?>
</div>
	