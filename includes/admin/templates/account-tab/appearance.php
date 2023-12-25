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

$position = get_post_meta( $tab_id, '_position', true );
if ( empty( $position ) ) {
	$position = 800;
}

$fields = array(
	array(
		'id'    => '_color',
		'type'  => 'color',
		'label' => __( 'Background color', 'um-account-tabs' ),
		'value' => (string) get_post_meta( $tab_id, '_color', true ),
	),
	array(
		'id'    => '_icon',
		'type'  => 'icon',
		'label' => __( 'Icon', 'um-account-tabs' ),
		'value' => (string) get_post_meta( $tab_id, '_icon', true ),
	),
	array(
		'id'      => '_position',
		'type'    => 'number',
		'label'   => __( 'Position', 'um-account-tabs' ),
		'value'   => (int) $position,
		'tooltip' => __( 'A number from 1 to 999. Default is 800. The value for each tab must be unique.', 'um-account-tabs' ),
	),
);

UM()->admin_forms(
	array(
		'class'     => 'um-account-tab-appearance um-top-label',
		'prefix_id' => 'um_account_tab',
		'fields'    => $fields,
	)
)->render_form();

?>
<style type="text/css">
	.um-account-tab-appearance .um-forms-line td > label {
		margin: 0 0 5px 0;
		width: 100%;
	}
</style>
<?php
