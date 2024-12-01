<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$tab_id = $post->ID;
if ( UM()->external_integrations()->is_wpml_active() ) {
	global $sitepress;
	$default_lang_tab_id = $sitepress->get_object_id( $tab_id, 'um_account_tabs', true, $sitepress->get_default_language() );
	if ( $default_lang_tab_id && $default_lang_tab_id !== $tab_id ) {
		$tab_id = $default_lang_tab_id;
		echo '<p>' . esc_html__( 'These settings are obtained from a Account tab in the default language', 'um-account-tabs' ) . '</p>';
	}
}

$forms_options = array(
	'' => __( 'No form', 'um-account-tabs' ),
);

// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_query
$args = array(
	'numberposts' => -1,
	'post_status' => 'publish',
	'post_type'   => 'um_form',
	'meta_query'  => array(
		array(
			'key'     => '_um_mode',
			'compare' => '=',
			'value'   => 'profile',
		),
	),
);
$forms = get_posts( $args );
// phpcs:enable

if ( is_array( $forms ) ) {
	foreach ( $forms as $form ) {
		$forms_options[ $form->ID ] = $form->post_title . ' (' . $form->ID . ')';
	}
}

$fields = array(
	array(
		'id'          => '_um_form',
		'type'        => 'select',
		'label'       => __( 'Embed a profile form', 'um-account-tabs' ),
		'description' => __( 'Account tabs can not contain forms. Use this tool if you need to embed profile form fields into the tab.', 'um-account-tabs' ),
		'options'     => $forms_options,
		'value'       => get_post_meta( $tab_id, '_um_form', true ),
	),
	array(
		'id'          => '_um_form_header',
		'type'        => 'checkbox',
		'label'       => __( 'Display the profile header', 'um-account-tabs' ),
		'description' => __( 'Profile header is a place where you can upload the cover photo and the profile photo. You can turn on or off profile header elements using settings in the "Customize this form" section in the profile form builder.', 'um-account-tabs' ),
		'conditional' => array( '_um_form', '!=', '' ),
		'value'       => get_post_meta( $tab_id, '_um_form_header', true ),
	),
	array(
		'id'          => '_um_form_button',
		'type'        => 'text',
		'label'       => __( 'Submit button text', 'um-account-tabs' ),
		'placeholder' => __( 'Update', 'um-account-tabs' ),
		'conditional' => array( '_um_form', '!=', '' ),
		'value'       => get_post_meta( $tab_id, '_um_form_button', true ),
	),
);

UM()->admin_forms(
	array(
		'class'     => 'um-account-tab-um-form um-half-column',
		'prefix_id' => 'um_account_tab',
		'fields'    => $fields,
	)
)->render_form();
?>
<hr>
<p>
	<?php esc_html_e( 'The tab content supports placeholders:', 'um-account-tabs' ); ?>
	{display_name},
	{first_name},
	{last_name},
	{username},
	{gender},
	{email},
	{admin_email},
	{site_name},
	{site_url},
	{user_account_link},
	{user_profile_link},
	{user_avatar},
	{usermeta:<em>meta_key</em>}.
	<?php esc_html_e( 'You may get more details about placeholders', 'um-account-tabs' ); ?> <a href="https://docs.ultimatemember.com/article/1340-placeholders-for-email-templates" target="_blank"><?php esc_html_e( 'here', 'um-account-tabs' ); ?></a>.
</p>
