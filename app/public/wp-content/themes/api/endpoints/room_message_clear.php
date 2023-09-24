<?php
function api_room_message_clear($request) {
  // Verifique se o usuário atual tem permissão de administrador.
  if (!current_user_can('administrator')) {
    $response = new WP_Error('error', 'Você não tem permissão para realizar esta ação.', ['status' => 403]);
    return rest_ensure_response($response);
  }

  $room_id = $request['id']; // ID da sala de chat.

  // Verifique se a sala de chat (room_id) existe antes de continuar.
  if (!room_exists($room_id)) {
    $response = new WP_Error('error', 'Sala de chat não encontrada', ['status' => 404]);
    return rest_ensure_response($response);
  }

  // Recupere a postagem da sala existente.
  $room_post = get_post($room_id);

  // Verifique se a postagem da sala existe.
  if (!$room_post || $room_post->post_type !== 'sala-de-chat') {
    $response = new WP_Error('error', 'Postagem da sala não encontrada', ['status' => 404]);
    return rest_ensure_response($response);
  }

  // Limpe o conteúdo da postagem da sala (mensagens).
  $updated_room_post = [
    'ID'           => $room_id,
    'post_content' => json_encode([]), // Define o conteúdo da sala como um array vazio.
  ];

  wp_update_post($updated_room_post); // Atualize o conteúdo da sala (mensagens).

  $response_data = [
    'message' => 'O conteúdo da sala foi limpo com sucesso.',
    'room_id' => $room_id,
  ];

  return rest_ensure_response($response_data);
}

function register_api_room_message_clear() {
  register_rest_route('api', '/room_message_clear/(?P<id>[0-9]+)', [
    'methods' => WP_REST_Server::DELETABLE, // Use DELETABLE para solicitações DELETE.
    'callback' => 'api_room_message_clear',
    'permission_callback' => function ($request) {
      // Verifique se o usuário atual tem permissão de administrador.
      return current_user_can('administrator');
    },
  ]);
}

add_action('rest_api_init', 'register_api_room_message_clear');
?>