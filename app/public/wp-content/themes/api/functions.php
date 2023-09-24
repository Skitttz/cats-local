<?php
// Remove as rotas definidas pelo WordPress
// remove_action('rest_api_init', 'create_initial_rest_routes', 99);


// Permite solicitações CORS da origem http://localhost:5173.
add_action('init', 'allow_cors');


function allow_cors() {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}

add_filter('rest_endpoints', function($endpoints){
  unset($endpoints['/wp/v2/users']);
  unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
  return $endpoints;

});

$dirbase = get_template_directory();

require_once $dirbase . '/endpoints/user_post.php';
require_once $dirbase . '/endpoints/user_get.php';

require_once $dirbase . '/endpoints/photo_post.php';
require_once $dirbase . '/endpoints/photo_delete.php';
require_once $dirbase . '/endpoints/photo_get.php';
require_once $dirbase . '/endpoints/photo_like_get.php';  

require_once $dirbase . '/endpoints/comment_post.php';
require_once $dirbase . '/endpoints/comment_get.php';

require_once $dirbase . '/endpoints/like_post.php';
require_once $dirbase . '/endpoints/get_liked_post.php';


require_once $dirbase . '/endpoints/stats_get.php';

require_once $dirbase . '/endpoints/password.php';

require_once $dirbase . '/endpoints/room_create.php';
require_once $dirbase . '/endpoints/room_message_get.php';
require_once $dirbase . '/endpoints/room_message_post.php';
require_once $dirbase . '/endpoints/room_message_clear.php';



// like_post

function has_user_liked($user_id, $post_id) {
  $liked_users = get_post_meta($post_id, 'liked_users', true);

  if ($liked_users && in_array($user_id, $liked_users)) {
      return true; // O usuário curtiu a postagem
  }

  return false; // O usuário não curtiu a postagem
}

function add_user_like($user_id, $post_id) {
  $liked_users = get_post_meta($post_id, 'liked_users', true);
  $total_likes = (int) get_post_meta($post_id, 'total_likes', true);

  if (!$liked_users) {
    $liked_users = array(); // Inicializa a lista de usuários que curtiram a postagem
  }

  // Adiciona o ID do usuário à lista de "likes"
  if (!in_array($user_id, $liked_users)) {
    $liked_users[] = $user_id;
    update_post_meta($post_id, 'liked_users', $liked_users); // Atualiza a lista de "likes"
    update_post_meta($post_id, 'total_likes', $total_likes + 1); // Incrementa o total de likes
  }

  return true; // "Like" adicionado com sucesso
}

// Função para remover o "like" de um usuário de uma postagem
function remove_user_like($user_id, $post_id) {
  $liked_users = get_post_meta($post_id, 'liked_users', true);
  $total_likes = (int) get_post_meta($post_id, 'total_likes', true);

  if ($liked_users && in_array($user_id, $liked_users)) {
    // Remove o ID do usuário da lista de "likes"
    $updated_liked_users = array_diff($liked_users, array($user_id));
    update_post_meta($post_id, 'liked_users', $updated_liked_users); // Atualiza a lista de "likes"
    update_post_meta($post_id, 'total_likes', $total_likes - 1); // Decrementa o total de likes

    return true; // "Like" removido com sucesso
  }

  return false; // O usuário não havia curtido a postagem anteriormente
}


// photo_get 

function get_total_likes($post_id) {
  // Obtém o número total de likes da postagem com base no campo personalizado 'total_likes'
  $total_likes = (int) get_post_meta($post_id, 'total_likes', true);
  return $total_likes;
}

// MSG CHAT


function registrar_tipo_post_personalizado() {
  $labels = array(
      'name'               => 'Salas de Chat',
      'singular_name'      => 'Sala de Chat',
      'menu_name'          => 'Salas de Chat',
      'add_new'            => 'Adicionar Nova Sala',
      'add_new_item'       => 'Adicionar Nova Sala de Chat',
      'edit_item'          => 'Editar Sala de Chat',
      'new_item'           => 'Nova Sala de Chat',
      'view_item'          => 'Ver Sala de Chat',
      'search_items'       => 'Procurar Salas de Chat',
      'not_found'          => 'Nenhuma Sala de Chat encontrada',
      'not_found_in_trash' => 'Nenhuma Sala de Chat encontrada na lixeira',
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'salas-de-chat' ), // Slug para URLs
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ), // Campos suportados
  );

  register_post_type( 'sala-de-chat', $args );
}

add_action( 'init', 'registrar_tipo_post_personalizado' );

function room_exists($room_id) {
  // Use uma consulta personalizada para verificar se uma sala com o ID existe.
  $args = array(
    'post_type' => 'sala-de-chat', // Substitua pelo seu tipo de post personalizado.
    'post_status' => 'any',
    'posts_per_page' => 1,
    'fields' => 'ids', // Apenas IDs, não precisa dos detalhes completos.
    'p' => $room_id, // ID da sala a ser verificada.
  );

  $room_query = new WP_Query($args);

  // Verifique se pelo menos uma sala foi encontrada.
  if ($room_query->have_posts()) {
    return true; // A sala existe.
  } else {
    return false; // A sala não existe.
  }
}

function get_room_messages($room_id, $per_page, $page, $order) {
  $args = [
    'post_type' => 'sala-de-chat',
    'posts_per_page' => $per_page,
    'paged' => $page,
    'meta_key' => 'room_id',
    'meta_value' => $room_id,
    'order' => $order,
  ];

  $messages = get_posts($args);

  $formatted_messages = [];

  foreach ($messages as $message) {
    $message_data = json_decode($message->post_content, true);
    if ($message_data) {
      $formatted_messages[] = $message_data;
    }
  }

  return $formatted_messages;
}


update_option('large_size_w',1000);
update_option('large_size_h',1000);
update_option('large_crop',1);

function change_api($slug) {
  return 'json';
}
add_filter('rest_url_prefix', 'change_api');

function expire_token(){
  return time() + (60 * 60 *24);
}
add_action('jwt_auth_expire','expire_token');
?>
