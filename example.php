<?php

// Registering meta boxes

add_action('acf/init', function() {

    define('META_FIELDS', 'fields');

    require 'Meta.php';

    Meta::box('Author Options')->fields('Author')->on('author')->register();

    Meta::box('Flexible Modules')->flex(['Image', 'Text'])->on(['post', 'page'])->register();

    Meta::box('Header')->fields('Slider')->on('page')->register();

});


// Setting up fields

return [

	Meta::field('Text')->type('text')->set(),

	Meta::field('Textarea')->type('textarea')->set(),

	Meta::field('Number')->type('number')->set(['required' => true]),

	Meta::field('Email')->type('email')->set(),

	Meta::field('Url')->type('url')->set(),

	Meta::field('Password')->type('password')->set(),

	Meta::field('Wysiwyg')->type('wysiwyg')->set(),

	Meta::field('oEmbed')->type('oembed')->set(),

	Meta::field('Image')->type('image')->set(),

	Meta::field('File')->type('file')->set(),

	Meta::field('Gallery')->type('gallery')->set(),

	Meta::field('Select')->type('select')->choices([
		'black' => 'Black',
		'white' => 'White'
	])->set(),

	Meta::field('Checkbox')->type('checkbox')->choices([
		'red' => 'Red',
		'green' => 'Green'
	])->set(),

	Meta::field('Radio')->type('radio')->choices([
		'yellow' => 'Yellow',
		'blue' => 'Blue'
	])->set([ 'default_value' => 'blue' ]),

	Meta::field('True False')->type('true_false')->set(['required' => true]),

	Meta::field('Post Object')->type('post_object')->postType(['post', 'acmeta'])->set(),

	Meta::field('Page Link')->type('page_link')->postType('page')->set(),

	Meta::field('Relationship')->type('relationship')->postType(['page'])->filter(['taxonomy'])->set(),

	Meta::field('Taxonomy')->type('taxonomy')->taxonomy('category')->set(),

	Meta::field('User')->type('user')->role('editor')->set(),

	Meta::field('Date Picker')->type('date_picker')->wrapper(['width' => 50])->set(),

	Meta::field('Color Picker')->type('color_picker')->wrapper(['width' => 50])->set(),

	Meta::field('Conditional')->type('text')->when([
		'True False', '==', '1'
	])->set(),

	Meta::repeat('Slider', [

	  Meta::field('Image')->type('image')->set(),

		Meta::field('Description')->type('textarea')->set()

	])

];
