<?php

if ($SERVER_REQUEST["METHOD"]==="POST") {

}


 ?>

 $args = array(
  'numberposts' => 10,
  'post_type'   => 'book'
);

$latest_books = get_posts( $args );
