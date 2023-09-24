<?php
function api_like_post($request) {
  
  $user = wp_get_current_user();
  $user_id = $user->ID;
  
  if ($user_id === 0) {
    $response = new WP_Error('error', 'Nao possui permissao', ['status' => 401]);
    return rest_ensure_response($response);
  }

  $post_id = $request['id'];

  // Verifique se o usuário já curtiu esta postagem
  if (has_user_liked($user_id, $post_id)) {
    // Se o usuário já curtiu, remova o "like"
    remove_user_like($user_id, $post_id);
    $response = [
      'message' => 'Like do post foi removido!',
      'user_id' => $user_id,
      'post_id' => $post_id,
    ];
  } else {
    // Se o usuário ainda não curtiu, adicione o "like"
    add_user_like($user_id, $post_id);
    $response = [
      'message' => 'Post foi curtido!',
      'user_id' => $user_id,
      'post_id' => $post_id,
    ];
  }

  return rest_ensure_response($response);
}

function register_api_like_post() {
  register_rest_route('api', '/like/(?P<id>[0-9]+)', ['methods' => WP_REST_Server::CREATABLE, 'callback' => 'api_like_post']);
}

add_action('rest_api_init', 'register_api_like_post');


?>
