<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once('library/bones.php'); // if you remove this, bones will break
/*
2. library/custom-post-type.php
	- an example custom post type
	- example custom taxonomy (like categories)
	- example custom taxonomy (like tags)
*/
require_once('library/custom-post-type.php'); // you can disable this if you like
/*
3. library/admin.php
	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin
*/
// require_once('library/admin.php'); // this comes turned off by default
/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once('library/translation/translation.php'); // this comes turned off by default

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __('Sidebar 1', 'bonestheme'),
		'description' => __('The first (primary) sidebar.', 'bonestheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __('Sidebar 2', 'bonestheme'),
		'description' => __('The second (secondary) sidebar.', 'bonestheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!

/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php
				/*
					this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
					echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				*/
				?>
				<!-- custom gravatar call -->
				<?php
					// create variable
					$bgauthemail = get_comment_author_email();
				?>
				<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				<!-- end custom gravatar call -->
				<?php printf(__('<cite class="fn">%s</cite>', 'bonestheme'), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__('F jS, Y', 'bonestheme')); ?> </a></time>
				<?php edit_comment_link(__('(Edit)', 'bonestheme'),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
				<div class="alert alert-info">
					<p><?php _e('Your comment is awaiting moderation.', 'bonestheme') ?></p>
				</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
	<!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bones_wpsearch($form) {
	$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __('Search for:', 'bonestheme') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','bonestheme').'" />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</form>';
	return $form;
} // don't remove this bracket!






add_filter('show_admin_bar', '__return_false'); //Remove Admin Bar






// Custom Nav Images - Images & Text
function md_nmi_custom_content( $content, $item_id, $original_content ) {
  $content = $content . '<span class="page-title">' . $original_content . '</span>';

  return $content;
}
add_filter( 'nmi_menu_item_content', 'md_nmi_custom_content', 10, 3 ); //



//add custom post_types	
	
add_action( 'init', 'create_my_post_types' );

function create_my_post_types() {
	
	register_post_type( 'quote',
		array(
			'labels' => array(
			'name' => __( 'Quotes' ),
			'singular_name' => __( 'Quote' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Quote' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit Quote' ),
			'new_item' => __( 'New Quote' ),
			'view' => __( 'View Quote' ),
			'view_item' => __( 'View Quote' ),
			'search_items' => __( 'Search Quote' ),
			'not_found' => __( 'No Quotes found' ),
			'not_found_in_trash' => __( 'No Quotes found in Trash' ),
			'parent' => __( 'Parent Quote' ),
		),
			'public' => true,
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'query_var' => true,
			'supports' => array( 'title'),
			'taxonomies' => array('category', 'post_tag') // this is IMPORTANT
		)
	);
	
	register_post_type( 'fact',
		array(
			'labels' => array(
			'name' => __( 'Facts' ),
			'singular_name' => __( 'Fact' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Fact' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit Fact' ),
			'new_item' => __( 'New Fact' ),
			'view' => __( 'View Fact' ),
			'view_item' => __( 'View Fact' ),
			'search_items' => __( 'Search Fact' ),
			'not_found' => __( 'No Facts found' ),
			'not_found_in_trash' => __( 'No Facts found in Trash' ),
			'parent' => __( 'Parent Fact' ),
		),
			'public' => true,
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'query_var' => true,
			'supports' => array( 'title'),
			'taxonomies' => array('category', 'post_tag') // this is IMPORTANT
		)
	);
	
	register_post_type( 'latest',
		array(
			'labels' => array(
			'name' => __( 'Latest' ),
			'singular_name' => __( 'Latest' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Latest Item' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit Latest Item' ),
			'new_item' => __( 'New Latest Item' ),
			'view' => __( 'View Latest Item' ),
			'view_item' => __( 'View Latest Item' ),
			'search_items' => __( 'Search Latest Items' ),
			'not_found' => __( 'No Latest Items found' ),
			'not_found_in_trash' => __( 'No Latest Items found in Trash' ),
			'parent' => __( 'Parent Latest Item' ),
		),
			'public' => true,
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'query_var' => true,
			'supports' => array( 'title', 'thumbnail', 'editor', 'excerpt'),
			'taxonomies' => array('category', 'post_tag') // this is IMPORTANT
		)
	);
	
	register_post_type( 'award',
		array(
			'labels' => array(
			'name' => __( 'Awards' ),
			'singular_name' => __( 'Award' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Award' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit Award' ),
			'new_item' => __( 'New Award' ),
			'view' => __( 'View Award' ),
			'view_item' => __( 'View Award' ),
			'search_items' => __( 'Search Awards' ),
			'not_found' => __( 'No Awards found' ),
			'not_found_in_trash' => __( 'No Awards found in Trash' ),
			'parent' => __( 'Parent Award' ),
		),
			'public' => true,
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'query_var' => true,
			'supports' => array( 'title', 'thumbnail'),
			'taxonomies' => array('category', 'post_tag') // this is IMPORTANT
		)
	);
	
	register_post_type( 'latestDigital',
		array(
			'labels' => array(
			'name' => __( 'Latest Digital Edition' ),
			'singular_name' => __( 'Digital Edition' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Digital Edition' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit Digital Edition' ),
			'new_item' => __( 'New Digital Edition' ),
			'view' => __( 'View Digital Edition' ),
			'view_item' => __( 'View Digital Edition' ),
			'search_items' => __( 'Search Digital Editions' ),
			'not_found' => __( 'No Digital Editions found' ),
			'not_found_in_trash' => __( 'No Digital Editions found in Trash' ),
			'parent' => __( 'Parent Digital Edition' ),
		),
			'public' => true,
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'query_var' => true,
			'supports' => array( 'title', 'excerpt', 'thumbnail'),
			'taxonomies' => array('category', 'post_tag') // this is IMPORTANT
		)
	);


}
	

add_action("admin_init", "admin_init");
 
function admin_init(){
	add_meta_box("recipient", "Company or Magazine Name", "recipient", "award", "normal", "low");
	add_meta_box("source", "Quote Source", "source", "quote", "normal", "low");
	add_meta_box("issuu", "Issuu Link", "issuu", "latest", "normal", "low");
	add_meta_box("youtube", "Youtube", "youtube", "latest", "normal", "low");
	add_meta_box("pagelink", "Page Link", "pagelink", "latest", "normal", "low");
	add_meta_box("issuuLink", "Digital Edition Link", "issuuLink", "latestDigital", "normal", "low");
}

function recipient() {
  global $post;
  $custom = get_post_custom($post->ID);
  $recipient = $custom["recipient"][0];
  ?>
  <Input type="text" name="recipient" style="width:600px;" value="<?php echo $recipient; ?>" />
  <?php
}
 
function source() {
  global $post;
  $custom = get_post_custom($post->ID);
  $source = $custom["source"][0];
  ?>
  <Input type="text" name="source" style="width:600px;" value="<?php echo $source; ?>" />
  <?php
}

function issuu() {
  global $post;
  $custom = get_post_custom($post->ID);
  $issuu = $custom["issuu"][0];
  ?>
  <Input type="text" name="issuu" style="width:600px;" value="<?php echo $issuu; ?>" />
  <?php
}

function youtube() {
  global $post;
  $custom = get_post_custom($post->ID);
  $youtube = $custom["youtube"][0];
  ?>
  <Input type="text" name="youtube" style="width:600px;" value="<?php echo $youtube; ?>" />
  <?php
}

function pagelink() {
  global $post;
  $custom = get_post_custom($post->ID);
  $pagelink = $custom["pagelink"][0];
  ?>
  <Input type="text" name="pagelink" style="width:600px;" value="<?php echo $pagelink; ?>" />
  <?php
}

function issuuLink() {
  global $post;
  $custom = get_post_custom($post->ID);
  $issuuLink = $custom["issuuLink"][0];
  ?>
  <Input type="text" name="issuuLink" style="width:600px;" value="<?php echo $issuuLink; ?>" />
  <?php
}

add_action('save_post', 'save_details');


function save_details(){
  global $post;
  update_post_meta($post->ID, "recipient", $_POST["recipient"]);
  update_post_meta($post->ID, "source", $_POST["source"]);
  update_post_meta($post->ID, "issuu", $_POST["issuu"]);
  update_post_meta($post->ID, "youtube", $_POST["youtube"]);
  update_post_meta($post->ID, "pagelink", $_POST["pagelink"]);
  update_post_meta($post->ID, "issuuLink", $_POST["issuuLink"]);

}




// GET LATEST [get_latest]
function get_latest() {

	ob_start();

	query_posts('post_type=latest&posts_per_page=8');
	
	echo '<div class="grid clearfix">';
	
		$i = 0; while ( have_posts() ) : the_post(); $i++;
	
		global $post;
	
		setup_postdata($post);
	
		$issuu = get_post_meta($post->ID, 'issuu', true);
		$youtube = get_post_meta($post->ID, 'youtube', true);
		$pagelink = get_post_meta($post->ID, 'pagelink', true);
		
		if( $i == 4) {
			$style = 'last';
			$i = 0;
		}
		else $style='';
		
		$styles = array(
		    $style,
		    'item'
		  );
		
		echo '<div '; post_class($styles); echo '>';
			
			the_post_thumbnail( 'gridThumb' );
			
			echo '<div class="caption">';
			
			echo '<h2>';
					the_title(); 
			echo '</h2>';
				
			the_excerpt();
				
			if (isset($issuu) && ($issuu != '')){
				
				echo '<a class="buttonWrap" rel="shadowbox;width=960;height=648;"  title="'; echo the_title(); echo '" href="'; echo $issuu; echo '" frameborder="0">View magazine</a>';
			}
			
			if (isset($youtube) && ($youtube != '')){
				
				echo '<a class="ir play" rel="shadowbox;width=632;height=353;"  title="'; echo the_title(); echo '" href="'; echo $youtube; echo '" frameborder="0">View magazine</a>';
			}
			
			if (isset($pagelink) && ($pagelink != '')){
				
				echo '<a class="buttonWrap"  title="'; echo the_title(); echo '" href="'; echo $pagelink; echo '">View magazine</a>';
			}
			
			echo '</div>';
		echo '</div>';
			
	endwhile;
	
	echo '</div>';

	// Reset Query
	wp_reset_query();
	
	return ob_get_clean();

}

add_shortcode('get_latest', 'get_latest');



// GET QUOTES [get_quotes]
function get_quotes() {

	ob_start();

	query_posts('post_type=quote&posts_per_page=8&orderby=rand');

	echo '<div class="flexslider-container quotes">';
		echo '<div class="flexslider">';
			echo '<ul class="slides">';
				
			while ( have_posts() ) : the_post();
	
				global $post;
	
				setup_postdata($post); 
					
				$source = get_post_meta($post->ID, 'source', true);	
					
				echo '<li><blockquote>'.$post->post_title.'<cite>'.$source.'</cite></blockquote></li>';
				
			endwhile;
										
			echo '</ul>';
		echo '</div>';
	echo '</div>';
	
	// Reset Query
	wp_reset_query();
	
	return ob_get_clean();

}

add_shortcode('get_quotes', 'get_quotes');





// GET DIGITAL EDITIONS [get_digital_editions]
function get_digital_editions() {

	ob_start();

	query_posts('post_type=latestDigital&posts_per_page=9');
				
			while ( have_posts() ) : the_post();
	
				global $post;
	
				setup_postdata($post); 
					
				$issuuLink = get_post_meta($post->ID, 'issuuLink', true); 	
					
				echo '<a class="book" rel="shadowbox;width=960;height=648;" title="View '; echo the_title(); echo '" href="'; echo $issuuLink; echo '" frameborder="0">'; the_post_thumbnail( bookThumb ); echo'</a>';
				
			endwhile;

	// Reset Query
	wp_reset_query();
	
	return ob_get_clean();

}

add_shortcode('get_digital_editions', 'get_digital_editions');





// GET AWARDS [get_awards]
function get_awards() {

	ob_start();

	query_posts('post_type=award&posts_per_page=6');
				
			while ( have_posts() ) : the_post();
	
				global $post;
	
				setup_postdata($post); 
					
				$recipient = get_post_meta($post->ID, 'recipient', true); 	
					
				echo'<div class="awardContainer sixcol first clearfix">';
					echo'<div class="sixcol first">';
						the_post_thumbnail( 'gridThumb' );
					echo'</div>';
					echo'<div class="sixcol last">';
						echo'<h2>'; the_title(); echo'</h2>';
						echo'<p>';echo $recipient; echo'</p>';
					echo'</div>';
				echo'</div>';
				
			endwhile;

	// Reset Query
	wp_reset_query();
	
	return ob_get_clean();

}

add_shortcode('get_awards', 'get_awards');


?>
