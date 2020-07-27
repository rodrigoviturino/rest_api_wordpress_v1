<?php

$template_diretorio = get_template_directory();

// Custom Post Type
require_once($template_diretorio . "/custom-post-type/produto.php");
require_once($template_diretorio . "/custom-post-type/transacao.php");

// REST API
require_once($template_diretorio . "/endpoints/usuario_post.php");
require_once($template_diretorio . "/endpoints/usuario_get.php");
require_once($template_diretorio . "/endpoints/usuario_put.php");

require_once($template_diretorio . "/endpoints/produto_post.php");
require_once($template_diretorio . "/endpoints/produto_get.php");

function expire_token(){
  return time() + (60 * 60 * 24);
}

function get_product_id_by_slug($slug){
  $query = new WP_Query(array(
    "name" => $slug,
    "post_type" => "produto",
    "numberposts" => 1,
    "fields" => "ids"
  ));
  $posts = $query->get_posts();
  
  return array_shift($posts);
}

add_action('jwt_auth_expire', "expire_token");

?>