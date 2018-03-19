<?php

$entity = elgg_extract('entity', $vars);

?>
<span rel="icon" style="background-image: url(<?= $entity->getIconURL('tiny') ?>"></span>
<span><?= $entity->getDisplayName() ?></span>
