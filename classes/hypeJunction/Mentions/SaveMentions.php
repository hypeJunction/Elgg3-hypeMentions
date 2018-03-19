<?php

namespace hypeJunction\Mentions;

use Elgg\Event;
use NotificationException;

class SaveMentions {

	/**
	 * Save mention
	 *
	 * @param Event $event
	 *
	 * @throws NotificationException
	 */
	public function __invoke(Event $event) {

		$entity = $event->getObject();
		if (!$entity instanceof \ElggObject) {
			return;
		}

		$description = $entity->description;

		if (!$description) {
			return;
		}

		$poster = $entity->getOwnerEntity();

		preg_match_all('/@\[(\d+):(.*?)\]/i', $description, $matches);

		foreach ($matches as $match) {
			$guid = elgg_extract(1, $match);
			$mention = get_entity($guid);

			if (!$mention) {
				continue;
			}

			add_entity_relationship($entity->guid, 'mentions', $mention->guid);

			if ($mention instanceof \ElggUser && $poster && has_access_to_entity($entity, $mention)) {
				$title = elgg_echo('notify:mention:subject', [$poster->getDisplayName()]);
				$description = elgg_echo('notify:mention:body', [
					$poster->getDisplayName(),
					$entity->getDisplayName(),
					$entity->getURL(),
				]);

				notify_user($mention->guid, $entity->owner_guid, $title, $description, [
					'action' => 'mention',
					'object' => $entity,
					'subject' => $poster,
					'url' => $entity->getURL(),
				]);
			}
		}
	}
}
