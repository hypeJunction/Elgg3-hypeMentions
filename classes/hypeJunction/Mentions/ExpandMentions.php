<?php

namespace hypeJunction\Mentions;

class ExpandMentions {

	/**
	 * Expand mentions from @[guid:name] format
	 *
	 * @param \Elgg\Hook $hook
	 *
	 * @return string
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$value = $hook->getValue();

		$options = [
			'sanitize' => false,
			'autop' => false,
			'parse_urls' => true,
			'parse_emails' => true,
			'parse_usernames' => true,
			'parse_hashtags' => true,
			'parse_mentions' => true,
		];

		return elgg_format_html($value, $options);
	}
}
