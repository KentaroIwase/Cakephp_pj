<?php foreach ($places as $place) : ?>
	<div><?= h($place->id) ?></div>
	<div><?= h($place->name) ?></div>
<?php endforeach; ?>