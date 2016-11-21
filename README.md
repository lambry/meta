## Meta: A helper class for registering ACF fields. 
**`Note: This is a WIP / POC not ready for the prime time.`**

#### Registering meta boxes:

```
add_action('acf/init', function() {

	// Define the location where all fields are stored
	// All field names need to be camelCased
	define('META_FIELDS', 'fields');

	// Include the Meta class
	require 'Meta.php';

	// Display a meta box of author options on the authors post type
	Meta::box('Author Options')->fields('Author')->on('authors')->register();

	// Display a header meta box with a slider on pages
	Meta::box('Header')->fields('Slider')->on('page')->register();

	// Display the image and text blocks as flexible content on posts and pages
	Meta::box('Flexible Modules')->flex(['Image Block', 'Text Block'])->on(['post', 'page'])->register();

});
```

#### Setting up field groups:
```
// Example of author.php
<?php

return [

	Meta::field('Avatar')->type('image')->set(),

	Meta::field('Show Bio')->type('true_false')->set(),

	Meta::field('Bio')->type('textarea')->when([
	'Show Bio', '==', '1'
	])->set(),

	Meta::field('Age')->type('number')->wrapper(['width' => 50])->set(['required' => 1]),

];
```

```
// Example of slider.php
<?php

return [

	Meta::field('Slider Title')->type('text')->set(),

	Meta::repeat('Slider', [

		Meta::field('Image')->type('image')->set(['require' => true]),

		Meta::field('Description')->type('textarea')->set()

	])

];

```
