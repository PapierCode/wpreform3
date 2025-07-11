<?php

include 'include/tools.php'; // fonctions utiles
include 'images/sprite.php'; // sprite SVG

include 'include/classes/class-pc-walker-nav.php'; // navigation custom
include 'include/classes/class-pc-post.php'; // objet post custom
include 'include/classes/class-pc-contact-form.php'; // formulaire de contact
include 'include/classes/class-pc-hcaptcha.php'; // hcaptcha
include 'include/classes/class-pc-math-captcha.php'; // math captcha

include 'include/editors/block-editor/block-editor.php'; // block editor & blocks ACF
include 'include/editors/acf-tinymce/acf-tinymce.php'; // tinymce ACF

// groupes de champs ACF
include 'include/acf-fields/acf-wpr-settings.php';
include 'include/acf-fields/acf-blocks.php';
include 'include/acf-fields/acf-page.php';
if ( apply_filters( 'pc_filter_single_main_hero', false ) ) { include 'include/acf-fields/acf-post-hero.php'; }

if ( get_option('options_news_enabled') || get_option('options_events_enabled') ) {
    include 'include/register-cpts.php';
    if ( get_option('options_news_enabled') ) { include 'include/acf-fields/acf-news.php'; }
    if ( get_option('options_events_enabled') ) { include 'include/acf-fields/acf-events.php'; }
}
if ( get_option('options_faq_enabled') ) { include 'include/acf-fields/acf-faq.php'; }

if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) { include 'include/gravityforms.php'; }

include 'include/admin/admin.php'; // admin custom

include 'include/templates/templates.php'; // hooks & functions templates