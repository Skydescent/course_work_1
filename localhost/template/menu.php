<?php foreach ($menuPoints as $point) : ?>
	<?php if (isset($point['title'])) : ?>
	    <?php if (!isset($point['rights']) || array_search($userGroup, $point['rights']) !== false) : ?>
	        <li>
	            <a class="main-menu__item" href="<?= $point['path']; ?>"><?= $point['title']; ?></a>
	        </li>
	    <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>
