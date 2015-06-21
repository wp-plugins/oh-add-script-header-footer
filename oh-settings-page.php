<?php
class OHHeaderFooterSetting
{
    /**
     * Holds the values to be used in the fields callbacks
     */

    /**
     * Start up
     */
    public function __construct()
    {

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );


    }




    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Header Footer Settings',
            'Header Footer Settings',
            'manage_options',
            'oh-header-footer-settings',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'sogo_header_footer' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Header Footer Script</h2>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'sogo_header_footer', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'The following section is to be used for all pages in the site. ', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );
        add_settings_field(
            'oh_posttype', // ID
            'limit Google Analytics to:', // Title
            array( $this, 'oh_posttype_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );
        add_settings_field(
            'oh_header_script', // ID
            'Google Analytics', // Title
            array( $this, 'oh_header_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );
        add_settings_field(
            'oh_posttype_footer', // ID
            'Limit Google Remarketing to:', // Title
            array( $this, 'oh_posttype_footer_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );
        add_settings_field(
            'oh_footer_script', // ID
            'Google Remarketing ', // Title
            array( $this, 'oh_footer_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );



    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['oh_header'] ) )
            $new_input['oh_header'] =  $input['oh_header'] ;
        if( isset( $input['oh_footer'] ) )
            $new_input['oh_footer'] =  $input['oh_footer'] ;
        if( isset( $input['oh_posttype'] ) )
            $new_input['oh_posttype'] =  $input['oh_posttype'] ;
        if( isset( $input['oh_posttype_footer'] ) )
            $new_input['oh_posttype_footer'] =  $input['oh_posttype_footer'] ;

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'For individual pages please go to the page itself and use the Header Footer script section there';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function oh_header_callback()
    {
        printf(
            '<textarea style="margin: 0px; width: 730px; height: 211px;" id="oh_header" name="sogo_header_footer[oh_header]"   >%s</textarea>',
            isset( $this->options['oh_header'] ) ? esc_attr( $this->options['oh_header']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function oh_posttype_callback()
    {
        $post_types = get_post_types();
        $selected = isset( $this->options['oh_posttype'] ) ?   $this->options['oh_posttype']  : array();
        foreach ( $post_types  as $key=>$post_type ) {
            $checked  = (in_array($post_type, $selected)) ? 'checked="checked"' : '';
            echo '<input value="'.$post_type.'" type="checkbox" name="sogo_header_footer[oh_posttype][]" '.$checked.' id="'.$key.'"/>
                <label for="'.$key.'">'.$post_type.'</label>';
        }
    }
    /**
     * Get the settings option array and print one of its values
     */
    public function oh_posttype_footer_callback()
    {

        $selected = isset( $this->options['oh_posttype_footer'] ) ?   $this->options['oh_posttype_footer']  : array();

        $post_types = get_post_types();

        foreach ( $post_types  as $key=>$post_type ) {
            $checked  = (in_array($post_type, $selected)) ? 'checked="checked"' : '';
            echo '<input value="'.$post_type.'" type="checkbox" name="sogo_header_footer[oh_posttype_footer][]"
            '.$checked.' id="footer_'.$key.'"/>
                <label for="footer_'.$key.'">'.$post_type.'</label>';
        }
    }

    private function get_post_types(){
        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $post_types = get_post_types( $args, $output, $operator );
        $post_types[] ='post';
        $post_types[] ='page';
        return $post_types;
    }
    /**
     * Get the settings option array and print one of its values
     */
    public function oh_footer_callback()
    {
        printf(
            '<textarea style="margin: 0px; width: 730px; height: 211px;" id="oh_footer" name="sogo_header_footer[oh_footer]"   >%s</textarea>',
            isset( $this->options['oh_footer'] ) ? esc_attr( $this->options['oh_footer']) : ''
        );
        echo "<br/><a target='_blank' href='http://sogo.co.il/'><img src='//sogo.co.il/WPADS/sogo-header-footer.png' alt='Sogo Web Development'/></a>";
    }


}

if( is_admin() )
    $my_settings_page = new OHHeaderFooterSetting();