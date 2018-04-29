<?php

namespace hypeJunction\Mentions;

use Elgg\Hook;

class PrepareHtmlOutput {

	/**
	 * Prepare HTML output
	 *
	 * @param Hook $hook Hook
	 *
	 * @return array
	 */
	public function __invoke(Hook $hook) {

		$value = $hook->getValue();

		$html = elgg_extract('html', $value);
		$options = elgg_extract('options', $value);

		if (!isset($options['parse_hashtags'])) {
			$options['parse_hashtags'] = true;
		}

		if (!isset($options['parse_mentions'])) {
			$options['parse_mentions'] = true;
		}

		$html = MentionsService::instance()->link($html, $options);

		$options['parse_hashtags'] = false;
		$options['parse_mentions'] = false;

		return [
			'html' => $html,
			'options' => $options,
		];
	}
}