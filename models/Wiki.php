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
		$res = self::query ('id')
			->order ('id asc')
			->fetch_orig ();
		
		$urls = array ();
		foreach ($res as $item) {
			$urls[] = '/wiki/' . $item->id;
		}
		return $urls;
	}
}

?>
