<?php 

// Callback
function api_usuario_post($request) {

  $email = sanitize_email( $request["email"] );
  $senha = $request["senha"];
  $nome = sanitize_text_field( $request["nome"] );
  $rua = sanitize_text_field( $request["rua"] );
  $cep = sanitize_text_field( $request["cep"] );
  $numero = sanitize_text_field( $request["numero"] );
  $bairro = sanitize_text_field( $request["bairro"] );
  $cidade = sanitize_text_field( $request["cidade"] );
  $estado = sanitize_text_field( $request["estado"] );

  $user_exists = username_exists($email);
  $email_exists = email_exists($email);

  if(!$user_exists && !$email_exists && $email && $senha){
    $user_id = wp_create_user( $email, $senha, $email );

    $rule_user = array( 
      "ID" => $user_id,
      "display_name" => $nome,
      "fisrt_name" => $nome,
      "role" => "subscriber"
    ); 

    wp_update_user($rule_user);

    update_user_meta( $user_id, "rua", $rua,);
    update_user_meta( $user_id, "cep", $cep,);
    update_user_meta( $user_id, "numero", $numero,);
    update_user_meta( $user_id, "bairro", $bairro,);
    update_user_meta( $user_id, "cidade", $cidade,);
    update_user_meta( $user_id, "estado", $estado,);
  } else {
    $rule_user = new WP_Error("email", "Email já cadastrado.", array("status" => 403));
  }


  return rest_ensure_response($rule_user);
}

function registrar_api_usuario_post(){
  register_rest_route('api', '/usuario', array(
    array(
      // "methods" => WP_REST_Service::CREATABLE,
      "methods" => "POST", // POST, PUT, DELETE
      "callback" => "api_usuario_post",
    ),
  ));
}

add_action("rest_api_init", "registrar_api_usuario_post");
?>