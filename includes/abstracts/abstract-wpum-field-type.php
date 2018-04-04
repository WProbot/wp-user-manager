<?php
/**
 * Handles definition of all the field types supported by WPUM.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2018, Alessandro Tesoro
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main class that handles registration of field types.
 */
abstract class WPUM_Field_Type {

	/**
	 * Full name of the field type, eg "Paragraph Text".
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $name;

	/**
	 * Type of the field, eg "textarea".
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $type;

	/**
	 * Icon for the editor button. This is a class name from the Dashicons set.
	 *
	 * @since 2.0.0
	 * @var mixed
	 */
	public $icon = false;

	/**
	 * Priority order the field button should show inside the "Add Fields" tab.
	 *
	 * @since 2.0.0
	 * @var integer
	 */
	public $order = 20;

	/**
	 * Field group the field belongs to.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $group = 'standard';

	/**
	 * Placeholder to hold default value(s) for some field types.
	 *
	 * @since 2.0.0
	 * @var mixed
	 */
	public $defaults;

	/**
	 * Current form ID in the admin builder.
	 *
	 * @since 2.0.0
	 * @var mixed, int or false
	 */
	public $form_id;

	/**
	 * Current form data in admin builder.
	 *
	 * @since 2.0.0
	 * @var mixed, int or false
	 */
	public $form_data;

	/**
	 * List of settings available for this field type.
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param bool $init
	 */
	public function __construct() {

		// The form ID is to be accessed in the builder.
		$this->form_id = isset( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : false;

		// Bootstrap.
		$this->init();

		// Add fields tab.
		add_filter( 'wpum_registered_field_types', array( $this, 'register_field_type' ), 15 );

	}

	/**
	 * All systems go. Used by subclasses.
	 *
	 * @since 2.0.0
	 */
	public function init() {}

	/**
	 * Setup the editor settings for this field type.
	 *
	 * @return array
	 */
	public function get_editor_settings() {
		return [];
	}

	/**
	 * Setup the default settings for all field types.
	 *
	 * @return array
	 */
	private function get_default_editor_settings() {

		$settings = [
			'general' => [
				array(
					'type'        => 'input',
					'inputType'   => 'text',
					'label'       => esc_html__( 'Field title' ),
					'model'       => 'field_title',
					'required'    => true,
					'placeholder' => esc_html__( 'Enter a title for this field' ),
					'validator'   => [ 'string' ],
					'min'         => 1
				),
				array(
					'type'      => 'textArea',
					'inputType' => 'text',
					'label'     => esc_html__( 'Field description (optional)' ),
					'model'     => 'field_description',
					'rows'      => 3,
					'hint'      => esc_html__( 'This is the text that appears as a description within the forms. Leave blank if not needed.' )
				),
			]
		];

		return $settings;

	}

	/**
	 * Helper function for internal fields.
	 * If a field needs a placeholder setting, child classes can call this method.
	 *
	 * @return array
	 */
	protected function add_placeholder_setting() {

		$setting = array(
			'type'      => 'input',
			'inputType' => 'text',
			'label'     => esc_html__( 'Placeholder' ),
			'model'     => 'placeholder',
			'hint'      => esc_html__( 'This text will appear within the field when empty. Leave blank if not needed.' ),
		);

		return $setting;

	}

	/**
	 * Helper function for internal fields.
	 * If a field needs a "required" setting, child classes can call this method.
	 *
	 * @return array
	 */
	protected function add_requirement_setting() {

		$setting = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Set as required' ),
			'model'   => 'required',
			'default' => false,
			'hint'    => esc_html__( 'Enable this option so the field must be filled before the form can be processed.' ),
		);

		return $setting;

	}

	protected function add_visibility_setting() {

		$setting = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Profile visibility' ),
			'model'   => 'visibility',
			'hint'    => esc_html__( 'Set the visibility of this field on users profiles.' ),
			'values' => [
				[ 'id' => 'public', 'name' => esc_html__( 'Publicly visible' ) ],
				[ 'id' => 'hidden', 'name' => esc_html__( 'Hidden' ) ]
			],
			'noneSelectedText'     => '',
			'hideNoneSelectedText' =>  true,
		);

		return $setting;

	}

	/**
	 * Register fields into an array that can be easily retrieved.
	 *
	 * @param array $fields
	 * @return void
	 */
	public function register_field_type( $fields ) {

		$fields[ $this->group ]['fields'][] = array(
			'order'    => $this->order,
			'name'     => $this->name,
			'type'     => $this->type,
			'icon'     => $this->icon,
			'settings' => array_merge_recursive( $this->get_default_editor_settings(), $this->get_editor_settings() )
		);

		return $fields;

	}

}
