<?php
function api_room_message_post($request){
  $user = wp_get_current_user();
  $user_id = $user->ID;
  
  if($user_id === 0){
    $response = new WP_Error('error','Não possui permissão',['status'=> 401]);
    return rest_ensure_response($response);
  }
  
  $msg = sanitize_text_field($request['msg']);
  $room_id = $request['id'];

  // Verifique se a sala de chat (room_id) existe antes de continuar.
  if (!room_exists($room_id)) {
    $response = new WP_Error('error','Sala de chat não encontrada',['status'=> 404]);
    return rest_ensure_response($response);
  }

  if(empty($msg)){
    $response = new WP_Error('error','Ops! Para continuar insira algo. 😺',['status'=> 422]);
    return rest_ensure_response($response);
  }

  // Recupere a postagem da sala existente.
  $room_post = get_post($room_id);

  // Recupere o conteúdo JSON atual da postagem.
  $current_content = $room_post->post_content;

  // Decodifique o conteúdo JSON existente.
  $existing_messages = json_decode($current_content, true);

  if (!$existing_messages) {
    $existing_messages = []; // Inicialize um array vazio se não houver mensagens existentes.
  }

  // Crie um novo objeto de mensagem com o horário atual de Brasília.
  $timestamp = date_i18n('H:i d-m', current_time('timestamp'), true); // Horário no fuso horário de Brasília.
  $new_message = [
    // 'room_id' => $room_id,
    // 'user_id' => $user_id,
    'sender' => $user->display_name,
    'msg' => $msg,
    'timestamp' => $timestamp, // Adicione o horário atual de Brasília ao objeto de mensagem.
  ];

  // Adicione a nova mensagem ao objeto JSON existente.
  $existing_messages[] = $new_message;

  // Codifique o novo objeto JSON.
  $new_content = json_encode($existing_messages, JSON_UNESCAPED_UNICODE);

  // Atualize o conteúdo da postagem da sala com o novo JSON.
  wp_update_post([
    'ID' => $room_id,
    'post_content' => $new_content,
  ]);

  return rest_ensure_response($new_message);
}

function register_api_room_message_post(){
  register_rest_route('api', '/msg_room/(?P<id>[0-9]+)', [ 
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'api_room_message_post',
  ]);
}

add_action('rest_api_init', 'register_api_room_message_post');

?>
