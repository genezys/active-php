<h1>All pages</h1>

<ul>
<?php foreach( $values['pages'] as $page ): ?>
	<li><a href="<?php echo ActiveRequest::relativeUri('wiki/pages/'.$page) ?>"><?php echo $page ?></a></li>
<?php endforeach ?>
</ul>
