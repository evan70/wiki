<?php
namespace wiki;
/**
 * Fields:
 *
 * id
 * body
 */
class Wiki extends \Model {
	public $table = '#prefix#wiki';
	public $key = 'id';
	/**
	 * Generate a list of pages for the sitemaps app.
	 */
	public static function sitemap () {
		$res = self::query ()
			->order ('id asc')
			->order ('link_title asc')
			->fetch_orig ();
		
		$urls = array ();
		foreach ($res as $item) {
			$urls[] = '/wiki/' . $item->id;
		}
		return $urls;
	}
	
	/**
	 * Generate a list of pages for the search app,
	 * and add them directly via `Search::add()`.
	 */
	public static function search () {
		require_once ('apps/wiki/lib/markdown.php');
		require_once ('apps/wiki/lib/Functions.php');
		$pages = self::query ()
			->fetch_orig ();
		
		foreach ($pages as $i => $page) {
			$url = 'wiki/' . $page->id;
			if (! Search::add (
				'wiki/' . $page->id,
				array (
					'title' => str_replace ('-', ' ', $page->id),
					'text' => wiki_parse_body ($page->body),
					'url' => '/wiki/' . $page->id
				)
			)) {
				return array (false, $i);
			}
		}
		return array (true, count ($pages));
	}
	
}

?>
