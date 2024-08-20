<?php

include 'include/tools.php'; // fonctions utiles
include 'images/sprite.php'; // sprite SVG

include 'include/classes/class-pc-walker-nav.php'; // navigation custom
include 'include/classes/class-pc-post.php'; // objet post custom
include 'include/classes/class-pc-contact-form.php'; // formulaire de contact
include 'include/classes/class-pc-hcaptcha.php'; // hcaptcha
include 'include/classes/class-pc-math-captcha.php'; // math captcha

include 'include/register.php'; // CPTs

include 'include/editors/block-editor/block-editor.php'; // block editor & blocks ACF
include 'include/editors/acf-tinymce/acf-tinymce.php'; // tinymce ACF

// groupes de champs ACF
include 'include/acf-fields/acf-wpr-settings.php';
include 'include/acf-fields/acf-blocks.php';
include 'include/acf-fields/acf-page.php';
include 'include/acf-fields/acf-news.php';
include 'include/acf-fields/acf-events.php';

include 'include/admin/admin.php'; // admin custom

include 'include/templates/templates.php'; // hooks & functions templates