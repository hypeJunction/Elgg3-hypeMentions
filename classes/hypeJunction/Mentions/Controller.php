<?php

namespace hypeJunction\Mentions;

use Elgg\EntityNotFoundException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

class Controller {

	/**
	 * Search entities to mention
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 */
	public static function searchEntities(Request $request) {
		$query = $request->getParam('query', '');
		if (!$query) {
			$items = [];
			$user = $request->elgg()->session->getLoggedInUser();
			if ($user) {
				$items = $user->getFriends(['limit' => 20]);
			}
		} else {
			$items = $request->elgg()->hooks->trigger('mentions:search', 'entities', [
				'query' => $query,
			], null);

			if (!isset($items)) {
				$users = elgg_search([
					'query' => $query,
					'type' => 'user',
					'fields' => [
						'metadata' => ['name', 'username', 'title'],
					],
					'limit' => 10,
				]);

				$groups = elgg_search([
					'query' => $query,
					'type' => 'group',
					'fields' => [
						'metadata' => ['name']
					],
					'limit' => 10,
				]);

				$items = array_merge($users, $groups);
			}
		}

		$response = [];
		foreach ($items as $item) {
			$response[] = [
				'name' => $item->getDisplayName(),
				'icon' => $item->getIconURL(['size' => 'tiny']),
				'guid' => $item->guid,
				'url' => elgg_generate_url('mentions:entity', [
					'guid' => $item->guid,
				]),
			];
		}

		elgg_set_http_header('Content-Type: application/json');

		return elgg_ok_response(json_encode($response));
	}

	/**
	 * Search tags to mention
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws \DatabaseException
	 */
	public static function searchTags(Request $request) {
		$query = $request->getParam('query', '');

		$tag_names = elgg_get_registered_tag_metadata_names();

		elgg_get_tags();

		$qb = \Elgg\Database\Select::fromTable('metadata', 'md');
		$qb->select('md.value AS tag')
			->addSelect('COUNT(md.id) AS total')
			->where($qb->compare('md.name', 'IN', $tag_names, ELGG_VALUE_STRING))
			->groupBy('md.value')
			->having($qb->compare('total', '>=', 1, ELGG_VALUE_INTEGER))
			->orderBy('total', 'desc')
			->setMaxResults(20);

		if ($query) {
			$qb->andWhere($qb->compare('md.value', 'like', "%$query%", ELGG_VALUE_STRING));
		}

		$items = $request->elgg()->db->getData($qb) ? : [];

		$response = [];
		foreach ($items as $item) {
			$response[] = [
				'name' => $item->tag,
				'url' => elgg_generate_url('mentions:tag', [
					'tag' => $item->tag,
				]),
			];
		}

		elgg_set_http_header('Content-Type: application/json');

		return elgg_ok_response(json_encode($response));

	}

	/**
	 * Redirect to a tag page
	 *
	 * @param Request $request Request
	 * @return ResponseBuilder
	 */
	public static function showTag(Request $request) {

		$tag = $request->getParam('tag');

		$url = elgg_generate_url('default:search', [
			'q' => $tag,
			'search_type' => 'tags',
		]);

		return elgg_redirect_response($url);
	}

	/**
	 * Redirect to an entity page
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws EntityNotFoundException
	 */
	public static function showEntity(Request $request) {

		$entity = $request->getEntityParam();
		if (!$entity) {
			throw new EntityNotFoundException();
		}

		return elgg_redirect_response($entity->getURL());
	}
}
