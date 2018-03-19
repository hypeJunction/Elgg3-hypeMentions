<?php

namespace hypeJunction\Mentions;

class ExpandMentions {

	use Linking;

	/**
	 * Expand mentions from @[guid:name] format
	 *
	 * @param \Elgg\Hook $hook
	 *
	 * @return void|array
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$value = $hook->getValue();

		return $this->link($value);
	}
}
