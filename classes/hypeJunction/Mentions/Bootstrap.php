<?php

namespace hypeJunction\Mentions;

use Elgg\Includer;
use Elgg\PluginBootstrap;

class Bootstrap extends PluginBootstrap {


	/**
	 * Get plugin root
	 * @return string
	 */
	protected function getRoot() {
		return $this->plugin->getPath();
	}

	/**
	 * {@inheritdoc}
	 */
	public function load() {
		Includer::requireFileOnce($this->getRoot() . '/autoloader.php');
	}

	/**
	 * {@inheritdoc}
	 */
	public function boot() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		elgg_extend_view('elements/forms.css', 'mentions/mentions.css');

		elgg_extend_view('input/text', 'mentions/mentions');
		elgg_extend_view('input/plaintext', 'mentions/mentions');
		elgg_extend_view('input/longtext', 'mentions/mentions');

		elgg_register_simplecache_view('mentions/emoji.js');

		elgg_register_plugin_hook_handler('prepare', 'html', PrepareHtmlOutput::class);

		elgg_register_plugin_hook_handler('view', 'output/plaintext', ExpandMentions::class, 9999);
		elgg_register_plugin_hook_handler('view', 'search/entity', ExpandMentions::class, 9999);

		elgg_register_event_handler('create', 'object', \hypeJunction\Mentions\SaveMentions::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function ready() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function shutdown() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function activate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function deactivate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function upgrade() {

	}
}