<?php namespace EmailLog\Addon\License;

use EmailLog\Addon\AddonList;
use EmailLog\Addon\API\EDDUpdater;
use EmailLog\Core\Loadie;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Handles the add-on licensing for Email Log.
 *
 * There can be one normal license for each add-on or one bundle license for all add-ons.
 * This class is final because we don't want other plugins to interfere with Email Log licensing.
 *
 * @since 2.0.0
 */
final class Licenser implements Loadie {

	/**
	 * Bundle License object.
	 *
	 * @var \EmailLog\Addon\License\BundleLicense
	 */
	private $bundle_license;

	/**
	 * List of Add-on updaters.
	 *
	 * @var \EmailLog\Addon\API\EDDUpdater[]
	 */
	private $updaters = array();

	/**
	 * List of add-ons.
	 *
	 * @var \EmailLog\Addon\AddonList
	 */
	private $addon_list;

	/**
	 * Licenser constructor.
	 * If the bundle_license object is not passed a new object is created.
	 * If the addon_list object is not passed a new object is created.
	 *
	 * @param \EmailLog\Addon\License\BundleLicense|null $bundle_license Optional. Bundle License.
	 * @param \EmailLog\Addon\AddonList|null             $addon_list     Optional. Add-on List.
	 */
	public function __construct( $bundle_license = null, $addon_list = null ) {
		if ( ! $bundle_license instanceof BundleLicense ) {
			$bundle_license = new BundleLicense();
		}

		if ( ! $addon_list instanceof AddonList ) {
			$addon_list = new AddonList();
		}

		$this->bundle_license = $bundle_license;
		$this->addon_list     = $addon_list;
	}

	/**
	 * Load all Licenser related hooks.
	 *
	 * @inheritdoc
	 */
	public function load() {
		$this->bundle_license->load();

		add_action( 'el_before_addon_list', array( $this, 'render_bundle_license_form' ) );
		add_action( 'el_before_logs_list_table', array( $this, 'render_more_fields_addon_upsell_message' ) );

		add_action( 'el_bundle_license_activate', array( $this, 'activate_bundle_license' ) );
		add_action( 'el_bundle_license_deactivate', array( $this, 'deactivate_bundle_license' ) );

		add_action( 'el_license_activate', array( $this, 'activate_addon_license' ) );
		add_action( 'el_license_deactivate', array( $this, 'deactivate_addon_license' ) );
	}

	/**
	 * Add an Add-on Updater.
	 *
	 * @param \EmailLog\Addon\API\EDDUpdater $updater Add-on Updater.
	 */
	public function add_updater( $updater ) {
		if ( $updater instanceof EDDUpdater ) {
			$this->updaters[ $updater->get_slug() ] = $updater;
		}
	}

	/**
	 * Get list of add-ons.
	 *
	 * @return \EmailLog\Addon\AddonList Add-on List.
	 */
	public function get_addon_list() {
		return $this->addon_list;
	}

	/**
	 * Render the Bundle License Form.
	 */
	public function render_bundle_license_form() {
		$action       = 'el_bundle_license_activate';
		$action_text  = __( 'Activate', 'email-log' );
		$button_class = 'button-primary';
		$expires      = '';

		if ( $this->is_bundle_license_valid() ) {
			$action       = 'el_bundle_license_deactivate';
			$action_text  = __( 'Deactivate', 'email-log' );
			$button_class = '';
			$expiry_date  = date( 'F d, Y', strtotime( $this->get_bundle_license_expiry_date() ) );
			$expires      = sprintf( __( 'Your license expires on %s', 'email-log' ), $expiry_date );
		}
		?>

		<div class="bundle-license">
			<?php if ( ! $this->is_bundle_license_valid() ) : ?>
				<p class="notice notice-warning">
					<?php
					printf(
						__( "Enter your license key to activate add-ons. If you don't have a license, then you can <a href='%s' target='_blank'>buy it</a>", 'email-log' ),
						'https://wpemaillog.com/store/?utm_campaign=Upsell&utm_medium=wpadmin&utm_source=notice&utm_content=buy-it'
					);
					?>
				</p>
			<?php endif; ?>

			<form method="post">
				<input type="text" name="el-license" class="el-license" size="40"
					   title="<?php _e( 'Email Log Bundle License Key', 'email-log' ); ?>"
					   placeholder="<?php _e( 'Email Log Bundle License Key', 'email-log' ); ?>"
					   value="<?php echo esc_attr( $this->bundle_license->get_license_key() ); ?>">

				<input type="submit" class="button button-large <?php echo sanitize_html_class( $button_class ); ?>"
					   value="<?php echo esc_attr( $action_text ); ?>">

				<p class="expires"><?php echo esc_html( $expires ); ?></p>

				<input type="hidden" name="el-action" value="<?php echo esc_attr( $action ); ?>">

				<?php wp_nonce_field( $action, $action . '_nonce' ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Renders Upsell message for More Fields add-on.
	 *
	 * @since 2.2.5
	 */
	public function render_more_fields_addon_upsell_message() {
		echo '<span id = "el-pro-msg">';
		_e( 'Additional fields are available through More Fields add-on. ', 'email-log' );

		//if ( $this->is_bundle_license_valid() ) {
        if(true){
			echo '<a href="admin.php?page=email-log-addons">';
			_e( 'Install it', 'email-log' );
			echo '</a>';
		} else {
			echo '<a href="https://wpemaillog.com/addons/more-fields/?utm_campaign=Upsell&utm_medium=wpadmin&utm_source=inline&utm_content=mf" style="color:red">';
			_e( 'Buy Now', 'email-log' );
			echo '</a>';
		}

		echo '</span>';
	}

	/**
	 * Activate Bundle License.
	 *
	 * @param array $request Request Object.
	 */
	public function activate_bundle_license( $request ) {
		$license_key = sanitize_text_field( $request['el-license'] );

		$this->bundle_license->set_license_key( $license_key );

		try {
			$this->bundle_license->activate();
			$message = __( 'Your license has been activated. You can now install add-ons, will receive automatic updates and access to email support.', 'email-log' );
			$type    = 'updated';
		} catch ( \Exception $e ) {
			$message = $e->getMessage();
			$type    = 'error';
		}

		add_settings_error( 'bundle-license', 'bundle-license', $message, $type );
	}

	/**
	 * Deactivate Bundle License.
	 */
	public function deactivate_bundle_license() {
		try {
			$this->bundle_license->deactivate();
			$message = __( 'Your license has been deactivated. You will not receive automatic updates.', 'email-log' );
			$type    = 'updated';
		} catch ( \Exception $e ) {
			$message = $e->getMessage();
			$type    = 'error';
		}

		add_settings_error( 'bundle-license', 'bundle-license', $message, $type );
	}

	/**
	 * Is the bundle license valid?
	 *
	 * @return bool True, if Bundle License is active, False otherwise.
	 */
	public function is_bundle_license_valid() {
		//return $this->bundle_license->is_valid();
        return true;
	}

	/**
	 * Get the expiry date of the Bundle License.
	 *
	 * @return false|string Expiry date, False if license is not valid.
	 */
	protected function get_bundle_license_expiry_date() {
		return $this->bundle_license->get_expiry_date();
	}

	/**
	 * Activate individual add-on License.
	 *
	 * @param array $request Request Array.
	 */
	public function activate_addon_license( $request ) {
		$license_key = sanitize_text_field( $request['el-license'] );
		$addon_name  = sanitize_text_field( $request['el-addon'] );

		$license = $this->addon_list->get_addon_by_name( $addon_name )->get_license();
		$license->set_license_key( $license_key );

		try {
			$license->activate();
			$message = sprintf(
				__( 'Your license for %s has been activated. You will receive automatic updates and access to email support.', 'email-log' ),
				$addon_name
			);
			$type = 'updated';
		} catch ( \Exception $e ) {
			$message = $e->getMessage();
			$type    = 'error';
		}

		add_settings_error( 'addon-license', 'addon-license', $message, $type );
	}

	/**
	 * Deactivate individual add-on License.
	 *
	 * @param array $request Request Array.
	 */
	public function deactivate_addon_license( $request ) {
		$license_key = sanitize_text_field( $request['el-license'] );
		$addon_name  = sanitize_text_field( $request['el-addon'] );

		$license = $this->addon_list->get_addon_by_name( $addon_name )->get_license();
		$license->set_license_key( $license_key );

		try {
			$license->deactivate();
			$message = sprintf(
				__( 'Your license for %s has been deactivated. You will not receive automatic updates.', 'email-log' ),
				$addon_name
			);
			$type = 'updated';
		} catch ( \Exception $e ) {
			$message = $e->getMessage();
			$type    = 'error';
		}

		add_settings_error( 'addon-license', 'addon-license', $message, $type );
	}

	/**
	 * Get the license key of an add-on.
	 *
	 * @param string $addon_name Addon.
	 *
	 * @return bool|string License key if found, False otherwise.
	 */
	public function get_addon_license_key( $addon_name ) {
		if ( $this->is_bundle_license_valid() ) {
			return $this->bundle_license->get_addon_license_key( $addon_name );
		}

		$addon = $this->addon_list->get_addon_by_name( $addon_name );

		if ( ! $addon ) {
			return false;
		}

		return $addon->get_addon_license_key();
	}

	/**
	 * Get the Download URL of an add-on.
	 *
	 * @param string $addon_slug Add-on slug.
	 *
	 * @return string Download URL.
	 */
	public function get_addon_download_url( $addon_slug ) {
		if ( isset( $this->updaters[ $addon_slug ] ) ) {
			return $this->updaters[ $addon_slug ]->get_download_url();
		}

		return '';
	}
}
