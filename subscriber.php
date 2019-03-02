<?php
$USER= 'kenzkenz_miya';
$PW= 'miyazaki';
$dnsinfo= "mysql:dbname=kenzkenz_kisyou;host=mysql1203b.xserver.jp;charset=utf8";
$pdo = new PDO($dnsinfo,$USER,$PW);

if( isset( $_GET[ 'hub_challenge' ] )  )  {
    header( 'HTTP/1.1 200' );
    header( 'Content-Type: text/plain' );
    echo $_GET[ 'hub_challenge' ];
    file_put_contents( 'challenge.txt', print_r( $_GET, TRUE ) );
} else  {
    $string = file_get_contents( 'php://input' );
    file_put_contents( 'new-feed.txt', $string );
    $feed = simplexml_load_string($string);

    $stmt = $pdo -> prepare("INSERT INTO tblfeed (id, title, updated, author, link, xml) VALUES (:id, :title, :updated, :author, :link, :xml)");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':updated', $updated, PDO::PARAM_STR);
    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
    $stmt->bindParam(':link', $link, PDO::PARAM_STR);
    $stmt->bindParam(':xml', $xml, PDO::PARAM_STR);

    foreach ($feed->entry as $entry) {
        $id = $entry->id;
        $title= $entry->title;
        $updated= $entry->updated;
        $author= $entry->author->name;
        $link = $entry->link['href'];
    }
    $xml = $string;
    $stmt->execute();

}

