<?php

namespace JPWPToolkit\Admin;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Abstracts\Settings_Page;
use JPWPToolkit\Core\Settings_Group_Field;

/**
 * Social_Page class
 *
 * @package        JPWPToolkit
 * @subpackage     Admin
 * @since          0.3.0
 * @author         Javier Prieto
 */
final class Social_Network_Page extends Settings_Page {

  /**
   * Constructor
   *
   * @since 0.3.0
   */
  public function __construct() {
    // Define the basis of the settings page
    $this->option_page          = 'social_network_links';
    $this->option_group         = 'social_network_links';
    $this->option_name          = 'social_network_links';
    $this->settings_group_field = new Settings_Group_Field( $this->option_name );

    parent::__construct( 'options-general.php', $this->option_page );

    // Adds the settings menu
    add_action( 'admin_menu', [ $this, 'add_social_network_page' ] );

    // Adds the settings section
    add_action( 'admin_init', [ $this, 'add_social_network_links_section' ] );
  }

  /**
   * Add Security page to Settings menu
   *
   * @since 0.3.0
   */
  public function add_social_network_page() {
    parent::add_submenu_page( __( 'Social Networks Links', 'jpwp-toolkit' ), __( 'Social', 'jpwp-toolkit' ), 'activate_plugins' );
  }

  /**
   * Add social networks links tab
   *
   * @since 0.3.0
   */
  public function add_social_network_links_section() {
    $this->add_settings_section( 'social_network_links_settings_section_links' );

    $networks = [
        'facebook'  => 'Facebook',
        'dribbble'  => 'Dribble',
        'instagram' => 'Instagram',
        'linkedin'  => 'LinkedIn',
        'pinterest' => 'Pinterest',
        'rss'       => 'RSS',
        'skype'     => 'Skype',
        'twitter'   => 'Twitter',
        'whatsapp'  => 'WhatsApp',
        'yelp'      => 'Yelp',
        'youtube'   => 'YouTube',
    ];

    // Filter to allow to plugins/themes add more social networks
    $social_network_links = apply_filters( 'jpwp_toolkit_social_network_links', $networks );

    foreach ( $social_network_links as $key => $label ) {
      $this->settings_group_field->add_settings_field( $this->submenu_slug, "{$this->option_page}_settings_section_links", [
          'title'       => $label,
          'id'          => $key,
          'type'        => 'text',
          'input_class' => 'regular-text code',
      ] );
    }
  }

}
