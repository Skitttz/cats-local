<?php
function api_room_message_get($request) {
  $room_id = $request['id'];

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

  // Recupere o conteúdo JSON da postagem.
  $message_content = $room_post->post_content;

  // Decodifique o conteúdo JSON em um array de mensagens.
  $messages = json_decode($message_content, true);

  // Verifique se há mensagens.
  if (!$messages) {
    $messages = [];
  }




  return rest_ensure_response($messages);
}

function register_api_room_message_get() {
  register_rest_route('api', '/msg_room/(?P<id>[0-9]+)', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_room_message_get',
  ]);
}

add_action('rest_api_init', 'register_api_room_message_get');

?>
