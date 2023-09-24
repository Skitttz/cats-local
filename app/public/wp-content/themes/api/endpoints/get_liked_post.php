<?php
function api_check_like_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if ($user_id === 0) {
      $response = new WP_Error('error', 'Não possui permissão', ['status' => 401]);
      return rest_ensure_response($response);
  }

  $post_id = $request['id'];

  // Verifique se o usuário já curtiu esta postagem
  $isLiked = has_user_liked($user_id, $post_id);

  $response = [
      'liked' => $isLiked,
  ];

  return rest_ensure_response($response);
}

function register_api_check_like_post() {
  register_rest_route('api', '/check-like/(?P<id>[0-9]+)', ['methods' => WP_REST_Server::READABLE, 'callback' => 'api_check_like_post']);
}

add_action('rest_api_init', 'register_api_check_like_post');


?>
