<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<feed xmlns="http://www.w3.org/2005/Atom">

 <title>Fil d'exemple</title>
 <subtitle>Un titre secondaire.</subtitle>
 <link href="http://example.org/"/>
 <updated>2003-12-13T18:30:02Z</updated>
 <author>
   <name>Paul Martin</name>
   <email>paulmartin@example.com</email>
 </author>
 <id>urn:uuid:60a76c80-d399-11d9-b91C-0003939e0af6</id>

<?php foreach( $values['pages'] as $page ): ?>
 <entry>
   <title><?php echo $page ?></title>
   <link href="http://example.org/2003/12/13/atom03"/>
   <id>urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a</id>
   <updated>2003-12-13T18:30:02Z</updated>
   <summary>Quelque texte.</summary>
 </entry>

<?php endforeach ?>

</feed>
