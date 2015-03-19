<?php

$page->layout = $appconf['Wiki']['layout'];

if (isset ($this->params[0])) {
	$id = $this->params[0];
} else {
	$this->redirect ('/wiki/Home');
}

require_once ('apps/wiki/lib/markdown.php');
require_once ('apps/wiki/lib/Functions.php');

$page->id = 'wiki';

$title = $link_title;

$editable = User::require_acl ('wiki/edit');

$wiki = new Wiki ($id);
if ($wiki->error || (isset ($this->params[1]) && $this->params[1] == 'edit')) {
	if (! $editable) {
		$this->redirect ('/wiki/Home');
	}
	$f = new Form ('post', 'wiki/edit');
	if ($f->submit ()) {
		if ($wiki->error) {
			$wiki = new Wiki (array (
				'id' => $_POST['id'],
				'link_title' => $_POST['link_title']
				'body' => $_POST['body']
			));
		} else {
			$wiki->id = $_POST['id'];
			$wiki->link_title => $_POST['link_title'];
			$wiki->body = $_POST['body'];
		}
		$wiki->put ();

		Versions::add ($wiki);

		$hook = $wiki->orig ();
		$hook->page = 'wiki/' . $wiki->id;
		$hook->title = trim ($_POST['id']);
		$hook->link_title = $wiki->link_title;
		$hook->body = $wiki->body;
		$this->hook ('wiki/edit', $hook);

		$this->redirect ('/wiki/' . $wiki->id);
	} else {
		$o = new StdClass;
		$o->dashed = $id;
		$o->id = $title;
		$o->link_title = $wiki->link_title;
		$o->body = $wiki->body;
		$o->failed = $f->failed;
		$o = $f->merge_values ($o);

		$page->title = __ ('Edit Page');
		echo $tpl->render ('wiki/edit', $o);
		return;
	}
}

$page->title = $link_title;

echo $tpl->render ('wiki/index', array (
	'id' => $id,
	'title' => $link_title,
	'body' => wiki_parse_body ($wiki->body),
	'editable' => $editable
));

?>
