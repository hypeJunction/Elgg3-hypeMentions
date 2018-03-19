<?php

$entity = elgg_extract('entity', $vars);

echo "@[{$entity->guid}:{$entity->getDisplayName()}]";
