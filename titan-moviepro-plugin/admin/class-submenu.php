<?php
/**
 * Creates the submenu item for the plugin.
 *
 * @package titan-moviepro-plugin
 */
 
/**
 * Creates the submenu item for the plugin.
 *
 * Registers a new menu item under 'Users' and uses the dependency passed into
 * the constructor in order to display the page corresponding to this menu item.
 *
 * @package titan-moviepro-plugin
 */
class Submenu {
 
    /**
     * A reference to the class responsible for rendering the submenu page.
     *
     * @var    Submenu_Page
     * @access private
     */
    private $submenu_page;
 
    /**
     * Initializes all of the partial classes.
     *
     * @param Submenu_Page $submenu_page A reference to the class that renders the
     * page for the plugin.
     */
    public function __construct( $submenu_page ) {
        $this->submenu_page = $submenu_page;
    }
 
    /**
     * Adds a submenu for this plugin to the 'Tools' menu.
     */
    public function init() {
         add_action( 'admin_menu', array( $this, 'add_options_page' ) );
    }
 
    /**
     * Creates the submenu item and calls on the Submenu Page object to render
     * the actual contents of the page.
     */
    public function add_options_page() {
 
        add_menu_page(
            'MoviePro',
            'MoviePro',
            'manage_options',
            'movie-pro-admin'
        );
        add_submenu_page(
            'movie-pro-settings',
            'Settings',
            'Settings',
            'manage_options',
            'movie-pro-admin',
            array( $this->submenu_page, 'render' )
        );
    }
}