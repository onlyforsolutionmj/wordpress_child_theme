<?php
/**
* Template Name: Delivery Suburbs
*
*/

$args = array(
  'post_type'   => 'delivery-suburb'
);
 
$latest_books = get_posts( $args );

echo "<pre>";
print_r($latest_books);