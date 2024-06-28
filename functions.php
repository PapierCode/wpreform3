<?php

include 'include/tools.php'; // useful functions
include 'images/sprite.php'; // array of SVG

include 'include/classes/class-pc-walker-nav.php'; // navigation structure
include 'include/classes/class-pc-post.php'; // custom objet post

include 'include/news.php'; // CPT news or blog

include 'include/editors/block-editor/block-editor.php'; // block editor & ACF blocks
include 'include/editors/acf-tinymce/acf-tinymce.php'; // tinymce ACF

include 'include/admin/admin.php'; // admin custom

// ACF groups fields
include 'include/acf-fields/acf-wpr-settings.php';
include 'include/acf-fields/acf-blocks.php';
include 'include/acf-fields/acf-page.php';
include 'include/acf-fields/acf-news.php';

include 'include/templates/templates.php'; // templates hooks & functions