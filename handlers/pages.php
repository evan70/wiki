<?php

$res = Wiki::query ()
	->order ('id asc')
	->order ('link_title asc')
	->fetch_orig ();

if (! $this->internal) {
	$page->id = 'wiki';
	$page->title = __ ('All Pages');
}

echo '<p>';
foreach ($res as $pg) {
	printf (
	'<a href="/wiki/%s">%s</a><br />',
	$pg->id,
	$pg->link_title
	);
}
echo '</p>';

?>
