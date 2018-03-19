<?php

/**
 * Mentions
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 */

use hypeJunction\Mentions\ExpandMentions;

require_once __DIR__ . '/autoloader.php';

return function () {
	elgg_register_event_handler('init', 'system', function () {
		elgg_extend_view('elements/forms.css', 'mentions/mentions.css');

		elgg_extend_view('input/text', 'mentions/mentions');
		elgg_extend_view('input/plaintext', 'mentions/mentions');
		elgg_extend_view('input/longtext', 'mentions/mentions');

		elgg_register_simplecache_view('mentions/emoji.js');

		elgg_register_plugin_hook_handler('view', 'output/plaintext', ExpandMentions::class, 9999);
		elgg_register_plugin_hook_handler('view', 'output/longtext', ExpandMentions::class, 9999);
		elgg_register_plugin_hook_handler('view', 'search/entity', ExpandMentions::class, 9999);

		elgg_register_event_handler('create', 'object', \hypeJunction\Mentions\SaveMentions::class);
	});
};
