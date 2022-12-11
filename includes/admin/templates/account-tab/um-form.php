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
$forms = get_posts(
	array(
		'post_type'      => 'um_form',
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key'     => '_um_mode',
				'compare' => '=',
				'value'   => 'profile',
			),
		),
	)
);
// phpcs:enable
if ( is_array( $forms ) ) {
	foreach ( $forms as $form ) {
		$forms_options[ $form->ID ] = $form->post_title;
	}
}

$selected_form = get_post_meta( $tab_id, '_um_form', true );

if ( $selected_form ) {
	echo '<p>[ultimatemember form_id="' . absint( $selected_form ) . '" /]</p>';
}

$fields = array(
	array(
		'id'      => '_um_form',
		'type'    => 'select',
		'label'   => __( 'Embed a profile form', 'um-account-tabs' ),
		'options' => $forms_options,
		'value'   => $selected_form,
	),
);

UM()->admin_forms(
	array(
		'class'     => 'um-account-tab-um-form um-top-label',
		'prefix_id' => 'um_account_tab',
		'fields'    => $fields,
	)
)->render_form();
