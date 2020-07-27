<?php 

function produto_scheme($slug){
  $post_id = get_product_id_by_slug($slug);

  if($post_id){

    $post_meta = get_post_meta($post_id);

    // Pegando imagem via MEDIA com ID do POST
    $images = get_attached_media("image", $post_id);
    $images_array = null;

    if(images) {
      $images_array = array();

        foreach ($images as $key => $value) {
          $images_array[] = array(
            "titulo" => $value->post_name,
            "src" => $value->guid
          );
        }
    }

    $response = array(
      "id" => $slug,
      "fotos" => $images_array,
      "nome" => $post_meta["nome"][0],
      "preco" => $post_meta["preco"][0],
      "descricao" => $post_meta["descricao"][0],
      "vendido" => $post_meta["vendido"][0],
      "usuario_id" => $post_meta["usuario_id"][0]
    );

  } else {
    $response = new WP_Error("naoexiste", "Produto não encontrado", array("status" => 404));
  }

  return $response;
}

// Callback
function api_produto_get($request) {

  $response = produto_scheme($request["slug"]);

  return rest_ensure_response($response);
}

function registrar_api_produto_get(){
  register_rest_route('api', '/produto/(?P<slug>[-\w]+)', array(
    array(
      // "methods" => WP_REST_Service::CREATABLE,
      "methods" => "GET", // POST, PUT, DELETE
      "callback" => "api_produto_get",
    ),
  ));
}

add_action("rest_api_init", "registrar_api_produto_get");

/******************* PRODUTOS ************************/


// Callback
function api_produtos_get($request) {

  // Relembrando que REQUEST é o GET
  $q = sanitize_text_field($request["q"]) ?: "";
  $_page = sanitize_text_field($request["_page"]) ?: 0;
  $_limit = sanitize_text_field($request["_limit"]) ?: 9;
  $usuario_id = sanitize_text_field($request["usuario_id"]);


  $usuario_id_query = null;

  if($usuario_id){
    $usuario_id_query = array(
      "key" => "vendido",
      "value" => "false",
      "compare" => "="
    );
  }

  // criando essa ARRAY para pegarmos os produtos NÃO VENDIDO
  $vendido = array(
    "key" => "usuario_id",
    "value" => $usuario_id,
    "compare" => "="
  );

  $query = array(
    "post_type" => "produto",
    "posts_per_page" => $_limit,
    "paged" => $_page,
    "s" => $q, // Não podemos passar usuario_id aqui porque ele é um Custom Field e temos que passar como META
    "meta_query" => array(
      $usuario_id_query,
      $vendido
    )
  );

  $loop = new WP_Query($query);
  $posts = $loop->posts;
  $total = $loop->found_posts;

  $produtos = array();
  foreach ($posts as $key => $value){
    $produtos[] = produto_scheme($value->post_name);
  }

  // Retorna o Total
  $response = rest_ensure_response($produtos);
  $response->header("X-Total-Count", $total);

  return $response;
}


function registrar_api_produtos_get(){
  register_rest_route('api', '/produto', array(
    array(
      // "methods" => WP_REST_Service::CREATABLE,
      "methods" => "GET", // POST, PUT, DELETE
      "callback" => "api_produtos_get",
    ),
  ));
}

add_action("rest_api_init", "registrar_api_produtos_get");



?>