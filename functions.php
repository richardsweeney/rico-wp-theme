<?php

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_image_size('post-excerpt', 300, 300, TRUE);
add_image_size('portfolio-image', 450, 225, TRUE);

function lh_set_constants() {
	define('ROOT', get_bloginfo('template_directory'));
	define('IMG', get_bloginfo('template_directory') . '/img');
	define('JS', get_bloginfo('template_directory') . '/js');
	define('CSS', get_bloginfo('template_directory') . '/css');
	define('URL', get_bloginfo('url'));
	define('AKISMET_KEY', '9fd1d87831df');
}
add_action('init', 'lh_set_constants');

function rps_enqueue_stuff() {
	wp_register_style('main-css', CSS . '/main.css');
  wp_enqueue_style('main-css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('modernizr', JS . '/modernizr.js');
  if (is_page('contact')) {
		wp_enqueue_script('contact-form-ajax', JS . '/contact-ajax.js', array('jquery'));
	} elseif (is_page(array('home', 'portfolio'))) {
		wp_enqueue_script('portfolio', JS . '/portfolio.js', array('jquery'));
	}
	if(!is_page()) {
		wp_enqueue_script('history', JS . '/jquery.history.js', array('jquery'));
		wp_enqueue_script('comments-form-ajax', JS . '/comments.js', array('jquery'));
		wp_enqueue_script('ajax-pagination', JS . '/pagination.js', array('jquery'));
	}
	global $posts_per_page, $cat;
	$object = array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'js' => JS,
		'time' => array(
			'now' => time(),
			'oneHourFromNow' => strtotime('+1 hour'),
			'quaterHourFromNow' => strtotime('+15 minutes')
		),
		'posts_per_page' => $posts_per_page,
		'cat' => $cat
	);
	wp_localize_script('modernizr', 'ajaxGlobals', $object);
}
add_action('wp_enqueue_scripts', 'rps_enqueue_stuff');

if (function_exists('register_sidebar')) {
  register_sidebar(array(
		'name' => __('Sidebar'),
		'id' => 'rps-sidebar-widget',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<div class="title">',
    'after_title' => '</div>',
	));
}

/** Remove WP version from header */
 remove_action('wp_head', 'wp_generator');
 function blank_version() {
    return '';
 }
 add_filter('the_generator','blank_version');

if (!class_exists('RPS_CreateCustomPostType')) {
 /**
	* Class to create new custom post types
	*/
	class RPS_CreateCustomPostType {

		public
			$postOrPage = 'post',
			$supports = array( 'title', 'editor', 'custom-fields' ),
			$hierarchicalPost = false,
			$menuPos = 5;

		public function __construct( $nameArray ) {
			$this->cptName = $nameArray['cptName'];
			$this->singularName = $nameArray['singularName'];
			$this->pluralName = ( $nameArray['pluralName'] == null ) ? $nameArray['singularName'] : $nameArray['pluralName'];
			$this->slug = ( $nameArray['slug'] == null ) ? $nameArray['cptName'] : $nameArray['slug'];
			$this->supports = ( $nameArray['supports'] != null ) ? $nameArray['supports'] : $this->supports;
		}

		public function createPostType(){
			$cptArgs = array(
				'labels' => array(
					'name' => __( ucfirst( $this->pluralName ), 'post type general name' ),
		      'singular_name' => __( $this->singularName, 'post type singular name' ),
		      'add_new' => __( 'Add ', $this->singularName ),
		      'add_new_item' => __( 'Add new ' . ucfirst( $this->singularName ) ),
		      'edit_item' => __( 'Edit ' . $this->singularName ),
		      'new_item' => __( 'New ' . $this->singularName ),
		      'view_item' => __( 'View ' . $this->singularName ),
		      'search_items' => __( 'Seach ' . ucfirst( $this->pluralName ) ),
		      'not_found' =>  __( 'No ' . $this->pluralName . ' found' ),
		      'not_found_in_trash' => __( 'No ' . $this->pluralName . ' found in the trash' ),
				),
				'hierarchical' => $this->hierarchicalPost,
		    'public' => true,
				'rewrite' => array( 'slug' => $this->slug ),
			  'query_var' => true,
				'supports' => $this->supports,
				'menu_position' => $this->menuPos
			);
		  register_post_type( $this->cptName, $cptArgs );
		}
	}
}

if(!class_exists ('RPS_CreateCustomPostTypeWithTaxonomy')) {
 /**
	* Create a custom post type with a custom taxonomy
	*/
	class RPS_CreateCustomPostTypeWithTaxonomy extends RPS_CreateCustomPostType {
		public $customTax = array(
			'heirarchical' => true, // like categories
			'name' => '',
			'nameSingular' => '',
			'namePlural' => '',
			'taxSlug' => ''
		);
		public function createTaxonomy(){

			$name = $this->customTax['name'];
			$nameSingular = ( !empty( $this->customTax['nameSingular'] ) ) ? $this->customTax['nameSingular'] : $this->customTax['name'];
			$namePlural = ( !empty( $this->customTax['namePlural'] ) ) ? $this->customTax['namePlural'] : $this->customTax['name'];
			$taxSlug = ( !empty( $this->customTax['taxSlug'] ) ) ? $this->customTax['taxSlug'] : $this->customTax['name'];
			$labels = array(
				'name' => _x( ucfirst( $name ), 'taxonomy general name' ),
			  'singular_name' => _x( $nameSingular, 'taxonomy singular name' ),
			  'search_items' =>  __( 'Sök ' . $namePlural ),
			  'popular_items' => __( 'Populära ' . $namePlural ),
			  'all_items' => __( 'Alla ' . $namePlural ),
			  'parent_item' => __( 'Parent ' . $namePlural ),
			  'parent_item_colon' => __( 'Parent ' . $namePlural ),
			 	'edit_item' => __( 'Redigera ' . $nameSingular ),
			  'update_item' => __( 'Uppdatera ' . $nameSingular ),
			  'add_new_item' => __( 'Lägg till ny ' . $nameSingular )
			);
			register_taxonomy(
				$name,
				array( $this->cptName ),
				array(
				  'hierarchical' => $this->customTax['heirarchical'],
				  'labels' => $labels,
				  'show_ui' => true,
				  'query_var' => true,
				  'rewrite' => array( 'slug' => $taxSlug )
				)
			);
		}
	}
}

/*
	Examples:

*** Create a custom post type ***
$contactArray = array(
	'cptName' => 'contact-info',
	'singularName' => 'kontakt info',
	'pluralName' => 'kontakt info',
	'slug' => 'kontakt',
);
$contact = new RPS_CreateCustomPostType($contactArray);
$contact->supports = array('title', 'thumbnail');
add_action('init', array(&$contact, 'createPostType'));

*** Create a custom post type with taxonomy ***
$personArray = array(
	'cptName' => 'key-person',
	'singularName' => 'nyckelperson',
	'pluralName' => 'nyckelpersoner',
	'slug' => 'person'
);
$person = new RPS_CreateCustomPostTypeWithTaxonomy($personArray);
$person->supports = array('title', 'editor', 'thumbnail');
$person->customTax = array(
	'heirarchical' => TRUE,
	'name' => 'roll',
	'nameSingular' => 'roll',
  'namePlural' => 'roller',
	'taxSlug' => 'roll'
);
add_action('init', array(&$person, 'createTaxonomy'),0);
add_action('init', array(&$person, 'createPostType'));
*/

$portfolioArray = array(
	'cptName' => 'portfolio_item',
	'singularName' => 'portfolio item',
	'pluralName' => 'portfolio items',
	'slug' => 'portfolio-item'
);
$portfolio = new RPS_CreateCustomPostTypeWithTaxonomy($portfolioArray);
$portfolio->supports = array('title', 'editor', 'thumbnail');
$portfolio->customTax = array(
	'heirarchical' => TRUE,
	'name' => 'type',
	'nameSingular' => 'type',
  'namePlural' => 'types',
	'taxSlug' => 'type',
);
add_action('init', array(&$portfolio, 'createTaxonomy'),0);
add_action('init', array(&$portfolio, 'createPostType'));


/**
 * Register meta boxes
 */
function rps_add_meta_boxes() {
	add_meta_box(
		'portfolio-meta',
		'Meta',
		'rps_print_portfolio_meta',
		'portfolio_item',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'rps_add_meta_boxes');


/**
 * Add extra contact meta to contact details post type
 */
function rps_print_portfolio_meta() {
	global $post;
	$url = get_post_meta($post->ID, '_url', true);
	$img = get_post_meta($post->ID, '_tagline', true);
	?>
	<input class="rps-url" type="url" name="rps-url" value="<?php echo $url; ?>" placeholder="site url">
	<input class="rps-tagline" type="text" name="rps-tagline" value="<?php echo $img; ?>" placeholder="tagline">
	<?php
}


/* Save post meta */
function rps_save_custom_meta() {
	global $post;

	// Stops WP from clearing post meta when autosaving
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	  return $post->ID;
	}
	if (isset($_POST['rps-url'])) {
		$clean = esc_url_raw($_POST['rps-url']);
		update_post_meta($post->ID, '_url' , $clean);
	}
	if (isset($_POST['rps-tagline'])) {
		$realclean = sanitize_text_field($_POST['rps-tagline']);
		update_post_meta($post->ID, '_tagline' , $realclean);
	}

}
add_action('save_post', 'rps_save_custom_meta');


include('Akismet.class.php');

/* Submit contact form via AJAX + check for spam with Akismet */
function rps_myajax_submit() {
	$response = array('spam' => 'no', 'mail_sent' => 'no');

	$id = (is_integer($_POST['id'])) ? $_POST['id'] : '';
	$name = sanitize_text_field($_POST['form']['name']);
	$email = sanitize_email($_POST['form']['email']);
	$message = sanitize_text_field($_POST['form']['message']);

	$akismet = new Akismet(URL, AKISMET_KEY);
	$akismet->setCommentAuthor($name);
	$akismet->setCommentAuthorEmail($email);
	$akismet->setCommentContent($message);
	$akismet->setPermalink($id);

	if ($akismet->isCommentSpam()) {
		$response['spam'] = 'yes';
	} else {
		$to = get_bloginfo('admin_email');
		$subject = __('New message from your website');
		$the_message = sprintf('<p>%s <a href="mailto:%s">%s</a> %s %s</p><p>%s</p>', __('Message from'), $email, $email, __('on'), date('jS F Y \@ H:i'), $message);
		$mail_sent = wp_mail($to, $subject, $the_message);
		$response['mail_sent'] = ($mail_sent) ? 'yes' : 'no';
	}
  $response = json_encode($response);
  header("Content-Type: application/json");
  echo $response;
  die;
}
add_action('wp_ajax_nopriv_contact-form-ajax', 'rps_myajax_submit');
add_action('wp_ajax_contact-form-ajax', 'rps_myajax_submit');


/* Submit comments form via AJAX */
function rps_comments_ajax_submit() {

	$response = array('spam' => 'no', 'comment_sent' => 'no');
	global $wpdb;

	if (is_user_logged_in()) {
		global $current_user;
    get_currentuserinfo();
		$name = (!empty($current_user->display_name)) ? $wpdb->escape($current_user->display_name) : $wpdb->escape($current_user->user_login);
		$email = $wpdb->escape($current_user->user_email);
		$user_id = (int) $current_user->ID;
	} else {
		$name = $wpdb->escape(sanitize_text_field($_POST['form']['name']));
		$email = $wpdb->escape(sanitize_email($_POST['form']['email']));
		$user_id = 0;
	}

	$message = $wpdb->escape(sanitize_text_field($_POST['form']['message']));
	$comment_approved = ($user_id == 1) ? 1 : 0;
	$comment_type = 'comment';
	$id = (int) $_POST['form']['id'];
	$time = current_time('mysql');
	$url = '';
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	$akismet = new Akismet(URL, AKISMET_KEY);
	$akismet->setCommentAuthor($name);
	$akismet->setCommentAuthorEmail($email);
	$akismet->setCommentContent($message);
	$akismet->setPermalink($id);

	if ($akismet->isCommentSpam()) {
		$response['spam'] = 'yes';
	} else {
		$comment_check = check_comment($name, $email, $url, $message, $user_ip, $user_agent, $comment_type);
		$check = ($comment_check) ? 1 : 0;
		$data = array(
	    'comment_post_ID' => $id,
	    'comment_author' => $name,
	    'comment_author_email' => $email,
	    'comment_author_url' => $url,
	    'comment_content' => $message,
	    'comment_type' => $comment_type,
	    'comment_parent' => 0,
	    'user_id' => $user_id,
	    'comment_author_IP' => $user_ip,
	    'comment_agent' => $user_agent,
	    'comment_date' => $time,
	    'comment_approved' => $check
		);
		// Insert comment
		wp_insert_comment($data);

		if(!$comment_check) {
			// Send myself a message
			$to = get_bloginfo('admin_email');
			$subject = __('A new comment is awaiting moderation');
			$the_message = 'Get on that Sweeney :)';
			$mail_sent = wp_mail($to, $subject, $the_message);
		}

		// Create HTML to append new comment to the DOM
	 	$avatar = get_avatar( $email, 80 );
  	$comment = '<li class="comment"><article>';
  	$comment .= $avatar;
  	$comment .= '<p class="who-wrote">' . $name . ' wrote</p>';
  	$comment .= '<div class="comment-container">';
  	$comment .= ($comment_check) ? '' : '<p><em>Your comment is awaiting moderation.</em></p>';
  	$comment .= '<p>' . $message . '</p>';
  	$comment .= '<footer><p class="post-meta">' . date('F jS, Y') . '</p></footer>';
		$comment .= '</div></article>';

		$response['comment_sent'] = 'yes';
		$response['comment'] = $comment;

	}
  $response = json_encode($response);
  header("Content-Type: application/json");
  echo $response;
  die;
}
add_action('wp_ajax_nopriv_rps-comments-ajax-submit', 'rps_comments_ajax_submit');
add_action('wp_ajax_rps-comments-ajax-submit', 'rps_comments_ajax_submit');


/* Change from email to admin email */
function rps_from_email($email) {
  $wpfrom = get_option('admin_email');
  return $wpfrom;
}
add_filter('wp_mail_from', 'rps_from_email');
add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));



/* comments custom function */
function newrico_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
 	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  	<article id="comment-<?php comment_ID(); ?>">
	 		<?php echo get_avatar( $comment, 80 ); ?>
  	  <p class="who-wrote"><?php echo get_comment_author_link(); ?> wrote</p>
  		<div class="comment-container">
  			<?php if ($comment->comment_approved == '0'): ?>
     			<p><em>Your comment is awaiting moderation.</em></p>
  			<?php endif; ?>
				<?php comment_text(); ?>
			</div>
  		<footer>
  	  	<p class="post-meta"><?php printf(__('%1$s'), get_comment_date('F jS, Y'),  get_comment_time()) ?></p>
	    	<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
	    	<?php edit_comment_link('edit comment','  ',''); ?>
  		</footer>
  	</article>
	<?php
	// The trailing </li> is intentionally ommitted
}

/* Pagination for the blogs page */
function rps_pagination() {
	global $cat, $posts_per_page, $wp_query;
	error_log(print_R($wp_query, 1));
	$numResults = ($cat != '') ? get_category($cat)->category_count : $wp_query->found_posts;
	$currentPage = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
	$res = ceil($numResults / $posts_per_page);
	// More than one page, let's paginate this bitch
	if ($res > 1) {
			$li = '';
		$pagination = '<div class="blog-pagination-container"><nav><ul class="blog-pagination">' . "\n";
		for ($j = 1; $j <= $res; $j++) {
			$url = ($j == 1) ? URL . '/blog/' : URL . '/blog/?page=' . $j;
			$class = ($currentPage == $j) ? 'current-page' : '';
			$pagination .= '<li><a class="' . $class . '" data-page="' . $j . '" href="' . $url . '">' . $j . '</a></li>' . "\n";
		}
		$pagination .= '</ul></nav></div>' . "\n";
		echo $pagination;
	}
}


/* Enable blog pagination via ajax */
function rps_myajax_pagination() {
	$page = (int) $_POST['page'];
	$offset = (int) $_POST['offset'];
	$offset = ($page - 1) * $offset;

	$args = array(
		'post_type' => 'post',
		'offset' => $offset,
		'post_status' => 'publish'
	);
	if (isset($_POST['cat'])) {
		$args['cat'] = (int) $_POST['cat'];
	}
	$query = new WP_Query($args);
	$posts = '';
	if($query->have_posts()) {
		$i = 0;
		while ($query->have_posts()) {
			$query->the_post();
			if (has_post_thumbnail()) {
		  	$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
		  	$posts .= '<div class="blog-page-excerpt has-thumbnail ' . $i . '"><article>' . "\n";
				$posts .= '<div class="header-excerpt-meta-container">';
				$posts .= '<header><h1 class="blog-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h1></header>'. "\n";
				$posts .= '<footer><p class="post-meta">Posted : ' . get_the_time('F jS, Y') . ' in ' . get_the_category_list(', ') . '</a></p></footer>' . "\n";
				$posts .= '<div class="excerpt-container">' . rps_nicer_excerpt(array('echo' => false, 'words' => 60)) . ' </div>' . "\n";
				$posts .= '</div>' . "\n";
				$posts .= '<div class="thumbnail-container"><a href="' . get_permalink() . '"><img class="post-thumbnail" src="' . $image[0] . '" alt="' . get_the_title() . '"></a></div>' . "\n";
				$posts .= '</article></div>' . "\n";
			} else {
		  	$posts .= '<div class="blog-page-excerpt no-thumbnail ' . $i . '"><article>' . "\n";
				$posts .= '<div class="header-meta-container">';
				$posts .= '<header><h1 class="blog-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h1></header>'. "\n";
				$posts .= '<footer><p class="post-meta">Posted : ' . get_the_time('F jS, Y') . ' in ' . get_the_category_list(', ') . '</a></p></footer>' . "\n";
				$posts .= '</div>' . "\n";
				$posts .= '<div class="excerpt-container">' . rps_nicer_excerpt(array('echo' => false, 'words' => 60)) . ' </div>' . "\n";
				$posts .= '</article></div>' . "\n";
			}
			$i++;
		}
	}
	echo $posts;
	die;
}
add_action('wp_ajax_nopriv_rps-ajax-pagination', 'rps_myajax_pagination');
add_action('wp_ajax_rps-ajax-pagination', 'rps_myajax_pagination');


/* Nicer excerpt */
if (!function_exists('rps_nicer_excerpt')) {
	function rps_nicer_excerpt($args = array()) {
		global $post;
		$defaults = array(
			'echo' => true,
			'words' => 28,
			'ellipsis' => '&hellip;',
			'link' => true,
			'linkClass' => 'read-more-link',
			'linkText' => 'Read the full entry',
			'linkContainer' => 'p',
			'allowedTags' => '<p><a><i><em><b><strong><ul><ol><li><span><blockquote>'
		);
		$args = wp_parse_args( $args, $defaults );
    $text = trim( strip_tags( $post->post_content, $args['allowedTags'] ) );
		$text = preg_replace( '/(?:(?:\r\n|\r|\n)\s*){2}/s', ' ', $text );
    $text = explode( ' ', $text );
    $numWords = count( $text );
    if( $numWords > $args['words'] ) {
		  array_splice( $text, $args['words'] );
		  $text = implode( ' ', $text );
		  if( $args['ellipsis'] != false ) {
			  $text .= $args['ellipsis'];
			}
		} else {
		  $text = implode( ' ', $text );
		}
		$text = force_balance_tags( $text );
	  if( $numWords > $args['words'] && $args['link'] == true ) {
	  	$text .= '<' . $args['linkContainer'] . ' class="' . $args['linkClass'] . '"><a href="' . get_permalink( $post->ID ) .  '" title="' . get_the_title( $post->ID ) . '">' . $args['linkText'] . '</a></' . $args['linkContainer'] . '>';
		}
		if( $args['echo'] ) {
	 		echo apply_filters('the_content', $text);
	 	} else {
	 		return apply_filters('the_content', $text);
	 	}
	}
}

function my_awesome_image_resize_dimensions( $payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop ){

	// Change this to a conditional that decides whether you
	// want to override the defaults for this image or not.
	if( false )
		return $payload;

	if ( $crop ) {
		// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = min($dest_w, $orig_w);
		$new_h = min($dest_h, $orig_h);

		if ( !$new_w ) {
			$new_w = intval($new_h * $aspect_ratio);
		}

		if ( !$new_h ) {
			$new_h = intval($new_w / $aspect_ratio);
		}

		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = 0; // [[ formerly ]] ==> floor( ($orig_w - $crop_w) / 2 );
		$s_y = 0; // [[ formerly ]] ==> floor( ($orig_h - $crop_h) / 2 );
	} else {
		// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
		$crop_w = $orig_w;
		$crop_h = $orig_h;

		$s_x = 0;
		$s_y = 0;

		list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
	}

	// if the resulting image would be the same size or larger we don't want to resize it
	if ( $new_w >= $orig_w && $new_h >= $orig_h )
		return false;

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );

}
add_filter( 'image_resize_dimensions', 'my_awesome_image_resize_dimensions', 10, 6 );


