<?php
function api_get_total_likes($request) {
  $post_id = $request['id'];

  // Verifica se o post existe
  $post = get_post($post_id);
  if (!$post || is_wp_error($post)) {
    $response = new WP_Error('error', 'Post não encontrado', ['status' => 404]);
    return rest_ensure_response($response);
  }

  $total_likes = (int)get_post_meta($post_id, 'total_likes', true);

  $response = [
    'post_id' => $post_id,
    'total_likes' => $total_likes,
  ];

  return rest_ensure_response($response);
}

function register_api_get_total_likes() {
  register_rest_route('api', '/get_total_likes/(?P<id>[0-9]+)', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_get_total_likes',
  ]);
}

add_action('rest_api_init', 'register_api_get_total_likes');


?>
