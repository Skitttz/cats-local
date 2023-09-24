<?php
function create_chat_room($request) {
  // Verifique se o usuário está autenticado e tem permissão para criar salas.
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if ($user_id === 0) {
    $response = new WP_Error('error', 'Não possui permissão', ['status' => 401]);
    return rest_ensure_response($response);
  }

  // Obtenha os dados da solicitação.
  $data = $request->get_json_params();

  // Certifique-se de que os dados necessários tenham sido fornecidos.
  if (empty($data['room_name'])) {
    $response = new WP_Error('error', 'Nome da sala é obrigatório', ['status' => 422]);
    return rest_ensure_response($response);
  }

  // Crie a sala de chat no banco de dados.
  $room_name = sanitize_text_field($data['room_name']);

  // Crie o post usando o tipo de post personalizado 'sala-de-chat'.
  $room_id = wp_insert_post([
    'post_title' => $room_name,
    'post_status' => 'publish',
    'post_type' => 'sala-de-chat', // Use o tipo de post personalizado 'sala-de-chat'.
  ]);

  // Retorne a ID da sala recém-criada.
  return rest_ensure_response(['room_id' => $room_id]);
}

function register_create_chat_room_endpoint() {
  register_rest_route('api', '/create_chat_room', [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'create_chat_room',
  ]);
}

add_action('rest_api_init', 'register_create_chat_room_endpoint');


?>
