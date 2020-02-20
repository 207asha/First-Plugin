<?php
/**
 * Hello World Plugin is the simplest WordPress plugin for beginner.
 * Take this as a base plugin and modify as per your need.
 *
 * @package Ashas First Plugin
 * @author Asha Joshi
 * @license #
 * @link #
 * @copyright @2020. All rights reserved.
 *
 *            @wordpress-plugin
 *            Plugin Name: Ashas First Plugin
 *            Plugin URI: #
 *            Description: This is a simplest wordpress plugin for beginner . Create a custom post type (Register custom post type)
 and create a form in frontend and submit that from through ajax and store in DB and creating shortcode.
 *            Version: 1.0
 *            Author: Asha Joshi
 *            Author URI: #
 *            Text Domain: #
 *            Contributors: #
 *            License: #
 *            License URI: #
 */
/**
 *
 * @since 1.0
 */
/*
 * Creating a function to create our CPT (Custom Post Type)
 * you can take help from this link  - https://www.wpbeginner.com/wp-tutorials/how-to-create-custom-post-types-in-wordpress/
*/

function custom_post_type() {
    // Set UI labels for Custom Post Type
    $labels = array('name' => _x('Articles', 'Post Type General Name', ''), 'singular_name' => _x('Article', 'Post Type Singular Name', ''), 'menu_name' => __('Articles', ''), 'parent_item_colon' => __('Parent Article', ''), 'all_items' => __('All Articles', ''), 'view_item' => __('View Article', ''), 'add_new_item' => __('Add New Article', ''), 'add_new' => __('Add New', ''), 'edit_item' => __('Edit Article', ''), 'update_item' => __('Update Article', ''), 'search_items' => __('Search Article', ''), 'not_found' => __('Not Found', ''), 'not_found_in_trash' => __('Not found in Trash', ''),);
    // Set other options for Custom Post Type
    $args = array('label' => __('Articles', ''), 'description' => __('Article', ''), 'labels' => $labels,
    // Features this CPT supports in Post Editor
    'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
    // You can associate this CPT with a taxonomy or custom taxonomy.
    //'taxonomies'          => array( 'genres' ),
    //'taxonomies'  => array( 'category' ),
    /* A hierarchical CPT is like Pages and can have
     * Parent and child items. A non-hierarchical CPT
     * is like Posts.
    */
    'hierarchical' => false, 'public' => true, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => true, 'show_in_admin_bar' => true, 'menu_position' => 5, 'can_export' => true, 'has_archive' => true, 'exclude_from_search' => false, 'publicly_queryable' => true, 'capability_type' => 'page',);
    // Registering your Custom Post Type
    register_post_type('Articles', $args);
}
/* Hook into the 'init' action so that the function
 * Containing our post type registration is not
 * unnecessarily executed.
*/
add_action('init', 'custom_post_type', 0);

function article_taxonomy() {
    register_taxonomy(
        'articles_category',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'articles',             // post type name
        array(
            'hierarchical' => true,
            'label' => 'Article Category', // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'article_category',    // This controls the base slug that will display before each term
                'with_front' => true  // Don't display the category base before
            )
        )
    );
}
add_action( 'init', 'article_taxonomy');

/* 
    Registering css and js  
*/

function wpb_adding_scripts() {
    wp_register_script('my_amazing_scripts', plugins_url('jquery.js', __FILE__), array('jquery'), '3.4.1', true);
    wp_enqueue_script('my_amazing_scripts');
}

add_action('wp_enqueue_scripts', 'wpb_adding_scripts');
function wpb_adding_jsscripts() {
    wp_register_script('my_amazing_script', plugins_url('pulgin.js', __FILE__));
    wp_enqueue_script('my_amazing_script');
}

add_action('wp_enqueue_scripts', 'wpb_adding_jsscripts');
function wpb_adding_styles() {
    wp_register_style('my_stylesheet', plugins_url('pulgin.css', __FILE__));
    wp_enqueue_style('my_stylesheet');
}
add_action('wp_enqueue_scripts', 'wpb_adding_styles');

/*
 *Creating a front end form
*/
// function that runs when shortcode is called
function article_frontform() {
    // Things that you want to do.
    /*
      ob_start() is a part of PHP’s Output Buffering.
      We use this to turn the output buffering ON, What does it do you ask? Well, let’s say you want to manipulate the values of local variables across your code into different values, ob_start() allows us to do just that.
      When we turn on the PHP’s output buffering we are saying: “take whatever I will write here and store it as value for me to use/change/manipulate“.
      In short, it’s a kind of holder for values(strings) needed to be outputted to the web browser and eventually the user’s screen. So basically it takes everything needed to be outputted to the screen and stores it as STRINGS  and says hold on a minute.
    */
    ob_start();
?>
<div class="articles_form">
   <h4> Article Form  </h4>
   <form id="article_form" name="article_form" class="wordpress-ajax-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data" >
      <input type="text" name="name" placeholder="Post title">
       <br>
        <?php 
        $args = array('hide_empty' => false);
        $terms = get_terms( 'articles_category', $args );
         //  print_r($terms); exit;
        //$terms = get_terms( 'articles_category' );
        if($terms){ ?>
        <select id="artticle_category" class="form-control" name="artticle_category[]" style="width:100%;" multiple="">
        <option value=""> Select Category </option>

            <?php
         foreach ($terms as $term) {
             echo'<option value="'. $term->term_id . '">' . $term->name .  '</option>';
             # code...
         }
       ?>
        </select> <br><br>
    <?php  } ?>
      <textarea name="description" placeholder="Post Description" style="height: 200px !important;"></textarea> 
      <br>
      <input type="file" name="uploadedfiles" id="uploadedfiles"  accept="image/*"  >
      <br>
      <input type="hidden" name="action" value="custom_action">
      <br>
      <button>Send</button>
   </form>
</div>


<div class="articles_catform">
   <h4> Article Category Form  </h4>
   <form id="articles_catform" name="articles_catform" class="wordpress-ajax-form2" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data" >
      <input type="text" name="name" placeholder="Category Title">
      <br>
       <textarea name="description" placeholder="Category Description" style="height: 200px !important;"></textarea> 
      <br>
      <input type="hidden" name="action" value="custom_cataction">
      <br>
      <button>Send</button>
   </form>
</div>

<?php
    return ob_get_clean();
    /*
    statement eventually one of the cleaning functions will come into play in order to close the current buffering and clean after us, allowing us to use buffering again across our code in a different section.
    */
}
// for creating shortcode you an take help from this link -  https://www.wpbeginner.com/wp-tutorials/how-to-add-a-shortcode-in-wordpress/
// register shortcode
add_shortcode('articleform', 'article_frontform');


add_action('wp_ajax_custom_action', 'custom_action');
add_action('wp_ajax_nopriv_custom_action', 'custom_action');
function custom_action() {
   // print_r($_POST); exit;
    global $wpdb;
    //$response = array();
    // Example for creating an response with error information, to know in our js file
    // about the error and behave accordingly, like adding error message to the form with JS
    $post_name = (trim($_POST['name']));
    $post_description = (trim($_POST['description']));
  
    $catids = array();
    foreach ($_POST['artticle_category'] as $key => $value) {
       $catids[] =$value;
    }

    /*
     * If this was coming from the database or another source, we would need to make sure
     * these were integers or convert them to interger using intval:

     */
    $catids = array_map( 'intval', $catids );
    $catids = array_unique( $catids );
    //print_r($_POST['artticle_category']); 
    $taxonomy = 'articles_category';
    $post_id = wp_insert_post( array('post_type' => 'articles', 'post_title' => $post_name, 'post_content' => $post_description, 'post_status' => 'publish', 'comment_status' => 'closed', // if you prefer
    'ping_status' => 'closed', // if you prefer
    ));

    
    $term_taxonomy_ids = wp_set_object_terms( $post_id, $catids , $taxonomy );

    //print_r($term_taxonomy_ids); exit;

    if( $_FILES['uploadedfiles']) {

    $file = $_FILES['uploadedfiles'];
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
    $file_return = wp_handle_upload( $file, array('test_form' => false ) );
    if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        return false;
    } else {
        $filename = $file_return['file'];
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );
        $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );

        update_post_meta($post_id, '_thumbnail_id', $attachment_id);
        if( 0 < intval( $attachment_id ) ) {
          return $attachment_id;
        }
    }
    return false;
   
    }

    //$response['message'] = 'Article created Successfully!!!';
    //echo json_encode($response);
    wp_die();
    //die();
    // ... Do some code here, like storing inputs to the database, but don't forget to properly sanitize input data!
    // Don't forget to exit at the end of processing
    //exit(json_encode($response));
    
}


add_action('wp_ajax_custom_cataction', 'custom_cataction');
add_action('wp_ajax_nopriv_custom_cataction', 'custom_cataction');
function custom_cataction() {
    $post_name = (trim($_POST['name']));
    $post_description = (trim($_POST['description']));

    $cid = wp_insert_term($post_name, 'articles_category', array(
    'description' => $post_description,
    ));
   // print_r($cid);
    if ( is_wp_error($cid) ) {

     //echo $cid->get_error_message();
      echo'';

    }
    else  {
       echo'<option value="'.$cid['term_taxonomy_id'].'">'.$post_name.'</option>'; 
    }

    wp_die();
}


function get_custom_post_type_template($archive_template) {
    global $post;
    if (is_post_type_archive('articles')) {
        $archive_template = plugins_url('archive-articles.php', __FILE__);
        //echo $archive_template;  exit;
        // plugins_url('pulgin.js', __FILE__)
        
    }
    return $archive_template;
}
//add_filter( 'archive_template', 'get_custom_post_type_template' ) ;
add_filter('archive_template', 'yourplugin_get_custom_archive_template');
function yourplugin_get_custom_archive_template($template) {
    global $wp_query;
    if (is_post_type_archive('articles')) {
        $templates[] = 'archive-articles.php';
        $template = yourplugin_locate_plugin_template($templates);
    }
    return $template;
}
function yourplugin_locate_plugin_template($template_names, $load = false, $require_once = true) {
    if (!is_array($template_names)) {
        return '';
    }
    $located = '';
    $this_plugin_dir = WP_PLUGIN_DIR . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
    foreach ($template_names as $template_name) {
        if (!$template_name) continue;
        if (file_exists(STYLESHEETPATH . '/' . $template_name)) {
            $located = STYLESHEETPATH . '/' . $template_name;
            break;
        } elseif (file_exists(TEMPLATEPATH . '/' . $template_name)) {
            $located = TEMPLATEPATH . '/' . $template_name;
            break;
        } elseif (file_exists($this_plugin_dir . '/templates/' . $template_name)) {
            $located = $this_plugin_dir . '/templates/' . $template_name;
            break;
        }
    }
    if ($load && $located != '') {
        load_template($located, $require_once);
    }
    return $located;
}

/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template');
function my_custom_template() {
    global $post;
    $this_plugin_dir = WP_PLUGIN_DIR . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
    //echo $post->post_type; exit;
    /* Checks for single template by post type */
    if ($post->post_type == 'articles') {
        if (file_exists($this_plugin_dir . '/templates/single-articles.php')) {
            return $this_plugin_dir . '/templates/single-articles.php';
        }
    }
    return $single;
}
