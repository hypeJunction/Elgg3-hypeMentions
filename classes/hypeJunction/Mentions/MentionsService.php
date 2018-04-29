<?php

namespace hypeJunction\Mentions;

use Elgg\Di\ServiceFacade;

class MentionsService {

	use ServiceFacade;

	/**
	 * {@inheritdoc}
	 */
	public function name() {
		return 'posts.mentions';
	}

	/**
	 * Links mentions and hashtags
	 *
	 * @param string $value  text to process
	 * @param array  $option Linking options
	 *
	 * @return string
	 */
	public function link($value, array $options = []) {

		//$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

		if (elgg_extract('parse_mentions', $options, true)) {
			// Link mentions @[guid:name]
			$value = preg_replace_callback('/@\[(\d+):(.*?)\]/i', [$this, 'linkMentions'], $value);
		}

		if (elgg_extract('parse_hashtags', $options, true)) {
			// Link hashtags
			$regex = '/<a[^>]*?>.*?<\/a>|<.*?>|(^|\s|\!|\.|\?|>|\G)+(#\w+)/i';
			$value = preg_replace_callback($regex, [$this, 'linkHashtags'], $value);
		}

		return $value;
	}

	/**
	 * Replace callback
	 *
	 * @param array $matches Matches
	 *
	 * @return string
	 */
	public function linkMentions($matches) {
		$entity = get_entity($matches[1]);
		if (!$entity) {
			return elgg_format_element('span', [
				'rel' => 'mention',
				'data-guid' => $matches[1],
			], $matches[2]);
		}

		return elgg_view('output/url', [
			'text' => $matches[2],
			'href' => elgg_generate_url('mentions:entity', [
				'guid' => $entity->guid,
			]),
			'rel' => 'mention',
			'data-guid' => $matches[1],
		]);
	}

	/**
	 * Callback function for hashtag preg_replace_callback
	 *
	 * @param array $matches An array of matches
	 *
	 * @return string
	 */

	public static function linkHashtags($matches) {

		if (empty($matches[2])) {
			return $matches[0];
		}

		$tag = str_replace('#', '', $matches[2]);
		$href = elgg_generate_url('mentions:tag', [
			'tag' => $tag,
		]);

		return $matches[1] . elgg_view('output/url', [
				'rel' => 'hashtag',
				'href' => $href,
				'text' => $matches[2],
			]);
	}
}
