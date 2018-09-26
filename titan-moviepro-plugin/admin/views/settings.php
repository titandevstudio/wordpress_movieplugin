<div class="wrap">
 
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 
    <form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
 
        <div id="universal-message-container">
            <h2>Universal Message</h2>
 
            <div class="options">
                <p>
                    <label>What message would you like to display above each post?</label>
                    <br />
                    <input type="text" name="acme-message" value="" />
                </p>
            </div>
        </div><!-- #universal-message-container -->
 
        <?php
            wp_nonce_field( 'acme-settings-save', 'acme-custom-message' );
            submit_button();
        ?>
 
    </form>

    <div class="options">
    	
    	<?php $args = array(
			'blog_id'      => $GLOBALS['blog_id'],
			'role'         => '',
			'role__in'     => array(),
			'role__not_in' => array(),
			'meta_key'     => '',
			'meta_value'   => '',
			'meta_compare' => '',
			'meta_query'   => array(),
			'date_query'   => array(),        
			'include'      => array(),
			'exclude'      => array(),
			'orderby'      => 'login',
			'order'        => 'ASC',
			'offset'       => '',
			'search'       => '',
			'number'       => '',
			'count_total'  => false,
			'fields'       => 'all',
			'who'          => '',
		 ); 
		$users = get_users( $args ); 
		foreach($users as $user) {	?>
			
			<div><?php echo $user->user_login ?></div>

    	<?php } ?>
    </div>

    <div class="options">
    	<?php 
    		$wp_list_table = new User_Table();
    		$wp_list_table->prepare_items(); 
    		$wp_list_table->display();?>

    </div>
 
</div><!-- .wrap -->