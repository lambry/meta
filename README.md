## Meta: A helper class for registering ACF fields.
**`Note: This is a WIP / POC not ready for the prime time.`**

#### Registering meta boxes:

```php
add_action('acf/init', function() {

	// Define the location where all field files are stored, this defaults to a folder called fields
	define('META_FIELDS', 'includes');

	// Include the Meta class
	require 'Meta.php';

	// Display a header meta box with slider on all pages
	Meta::box('Header')->fields('Slider')->on('page')->register();

	// Display a meta box of author options on the author post type
	Meta::box('Author Options')->fields('Author')->on('author')->register();

	// Display the image and text blocks as flexible content on posts and pages
	Meta::box('Flexible Content')->flex(['Image Block', 'Text Block'])->on(['post', 'page'])->register();

});
```

#### Setting up field groups:
`Note: File names need to be camelCased.`

```php
<?php
// Example of author.php

return [

	Meta::field('Avatar')->type('image')->set(),

	Meta::field('Show Bio')->type('true_false')->set(),

	Meta::field('Bio')->type('textarea')->when([
	'Show Bio', '==', '1'
	])->set(),

	Meta::field('Age')->type('number')->wrapper(['width' => 50])->set(['required' => true]),

];
```

```php
<?php
// Example of slider.php

return [

	Meta::field('Slider Title')->type('text')->set(),

	Meta::repeat('Slider', [

		Meta::field('Image')->type('image')->set(['required' => true]),

		Meta::field('Description')->type('textarea')->set()

	])

];

```
