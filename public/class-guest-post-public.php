<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://sumansarkar.github.io
 * @since      1.0.0
 *
 * @package    Guest_Post
 * @subpackage Guest_Post/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Guest_Post
 * @subpackage Guest_Post/public
 * @author     Suman Sarkar <suman07.rj@gmail.com>
 */
class Guest_Post_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Post_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Post_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/guest-post-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Post_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Post_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/guest-post-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * All guest post save in draft mode featch and listing with pagination
	 *
	 * @since    1.0.0
	 */

	public function list_all_gest_post_shortcode( $atts ) {

		global $paged;
		$posts_per_page = 10;
		$settings = array(
			'showposts' => $posts_per_page, 
			'post_type' => 'guestpost', 
			'orderby' => 'menu_order',
			'post_status' =>'draft', 
			'order' => 'ASC', 
			'paged' => $paged
		);

		//featch all draft post data
		$post_query = new WP_Query( $settings );	
		
		$total_found_posts = $post_query->found_posts;
		$total_page = ceil( $total_found_posts / $posts_per_page );
			
		$list = '<div class="guestpost-item-list">';
		while( $post_query->have_posts() ) : $post_query->the_post();

			$list .= '<div class="single-guestpost-item">';
			$list .= '<div class="guestpost-poster">' . get_the_post_thumbnail() . '</div>';
        	$list .= '<div class="guestpost-name">' . get_the_title() . '</div>';
        	$list .= '<div class="guestpost-desc">' . get_the_excerpt() . '</div>'; 	
			$list .= '</div>';  

		endwhile;
		
		$list.= '</div>';
		
		if(function_exists('wp_pagenavi')) {
			$list .='<div class="page-navigation">'. wp_pagenavi(array('query' => $post_query, 'echo' => false)). '</div>';
		} else {
			$list.='
			<span class="next-posts-links">'. get_next_posts_link('Next page', $total_page) .'</span>
			<span class="prev-posts-links">'. get_previous_posts_link('Previous page'). '</span>
			';
		}
		
		
		return $list;

		
	}

	/**
	 * Register short code for show guest post form in frontend
	 *
	 * @since    1.0.0
	 */

	function frontend_guestpost_shortcode(){

		if ( is_user_logged_in() ) {

		return $this->frontend_guestpost_htmlfrm();

		}else{

			return "<p> Please Login For Submit Guest Post </p>";
		}
		

	}

	/**
	 * Form code for frontend guest post submition
	 *
	 * @since    1.0.0
	 */

	function frontend_guestpost_htmlfrm(){


		

		
		$result = '<form id="new_post" name="new_post" method="post" action="'. admin_url('admin-ajax.php') .'"  enctype="multipart/form-data">';

		if (isset($_GET['status'])) {
			$result .= "<p class='message '" . $_GET['status'] . "'>";
		
			$result .= $_GET['status'] == "success" ? "Post successfully submited" : "Something wrong, please try again";
		
			$result .= "</p>";
		}

        $result .= '<p><label for="title"> Post Title </label><br />
        <input type="text" id="title" value="" tabindex="1" size="20" name="gptitle" required />
        </p>';

		$args = array(
			'public'   => true,
			'_builtin' => false
		 );
		   
		$post_types = get_post_types ( $args );
  
			if ( $post_types ) { 
			  
				$result .= '<p><label for="gpposttype"> Post Type </label><br />
				<select name="gpposttype" required>';

				$result .= '<option value="">--Select Post Type--</option>';
			  
				foreach ( $post_types  as $post_type ) {
					$result .= '<option value='. $post_type .'>' . $post_type . '</option>';
				}
			  
				$result .= '</select>';
			  
			}

		$result .= '<p><label for="description"> Description </label><br />
		<textarea name="gpdescription" id="gpdescription" rows="4" cols="20"></textarea>
		</p>';

		$result .= '<p><label for="excerpt"> Excerpt </label><br />
        <input type="text" id="gpexcerpt" value="" tabindex="1" size="200" name="gpexcerpt" />
        </p>';

        //$result .= wp_dropdown_categories( 'show_option_none=Category&tab_index=4&taxonomy=category' );

        $result .= '<p><label for="feature_image"> Featured Image </label>
		</br><input type="file" name="feature_image" id="feature_image" aria-required="true"></p><br>';

		$result .='<p><input type="submit" value="Publish" tabindex="6" id="gp_submit" name="submit" />
		<input type="hidden" name="action" value="guestposttosave"/></p>';

		$date = date('Y-m-d');
		$result .= wp_nonce_field('GP_POST_SAVE_KEY' . $date, 'gp_post_security');
        
        $result .='</form>';

		return $result;


	}

	/**
	 * Form validation function
	 *
	 * @since    1.0.0
	 */

	private function validateNonce( $data )
    {


        $isValid = false;


        $date = date('Y-m-d');

        if ( wp_verify_nonce( $_POST['gp_post_security'], 'GP_POST_SAVE_KEY' . $date ) ) {


            $isValid = true;


        }


        return $isValid;
    }

	/**
	 * Form data insert and image upload function
	 *
	 * @since    1.0.0
	 */

	function guestpost_if_submitted(){
		
        $status = $this->validateNonce( $_POST );

        $referer = explode("?", $_SERVER['HTTP_REFERER']);
        $referer_url = $referer[0];

        if ($status == true) {

            $post_information = array(

                'post_title' 	=> isset($_POST['gptitle']) ? wp_strip_all_tags($_POST['gptitle']) : "",
                'post_content' 	=> isset($_POST['gpdescription']) ? ($_POST['gpdescription']) : "",
                'post_type' 	=> isset($_POST['gpposttype']) ? ($_POST['gpposttype']) : "post",
				'post_excerpt' 	=> isset($_POST['gpposttype']) ? ($_POST['gpexcerpt'] ) : "",
                'post_status' 	=> 'Draft'

            );

            $postID = wp_insert_post($post_information);

            // These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            // Wordpress file upload logic
            if ( isset( $_FILES['feature_image']['tmp_name']  )) {

                $attachment_id = media_handle_upload( 'feature_image', $postID );
                set_post_thumbnail( $postID, $attachment_id );
            }

			// Email functionality for send notification email to admin
			$to 		= bloginfo('admin_email');
    		$subject 	= "New Guest Post Submited";
    		$message 	= "New Guest Post has submited.Please review and publish it";
    		wp_mail($to, $subject, $message );

            $redirect = $referer_url . "?status=success";

        } else {

            $redirect = $referer_url . "?status=failed";

        }


        wp_redirect($redirect);
        die('die');
    
		

	}

}
