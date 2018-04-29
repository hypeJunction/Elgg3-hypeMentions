<?php

use hypeJunction\Mentions\Controller;

$plugin_root = __DIR__;
$root = dirname(dirname($plugin_root));
$alt_root = dirname(dirname(dirname($root)));

if (file_exists("$plugin_root/vendor/autoload.php")) {
	$views_path = $plugin_root;
} else if (file_exists("$root/vendor/autoload.php")) {
	$views_path = $root;
} else {
	$views_path = $alt_root;
}

return [
	'bootstrap' => \hypeJunction\Mentions\Bootstrap::class,

	'routes' => [
		'mentions:search:entities' => [
			'path' => '/mentions/search/entities',
			'controller' => [Controller::class, 'searchEntities'],
		],
		'mentions:search:tags' => [
			'path' => '/mentions/search/tags',
			'controller' => [Controller::class, 'searchTags'],
		],
		'mentions:tag' => [
			'path' => '/mentions/tag/{tag}',
			'controller' => [Controller::class, 'showTag']
		],
		'mentions:entity' => [
			'path' => '/mentions/entity/{guid}',
			'controller' => [Controller::class, 'showEntity'],
		],
	],

	'views' => [
		'default' => [
			'atwho.js' => $views_path . '/vendor/bower-asset/At.js/dist/js/jquery.atwho.min.js',
			'atwho.css' => $views_path . '/vendor/bower-asset/At.js/dist/css/jquery.atwho.min.css',
			'caret.js' => $views_path . '/vendor/bower-asset/caret.js/dist/jquery.caret.min.js',
		],
	],
];
