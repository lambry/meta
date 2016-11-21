<?php

/**
 * Meta
 *
 * A helper class for registering ACF fields 
 */

class Meta {

	// Static Methods
	private $box = '';
	private $field = '';

	// Primary Methods
	private $flex = [];
	private $fields = [];

	// Chained Methods
	private $type = '';
	private $postTypes = [];
	private $taxonomies = [];
	private $filters = [];
	private $roles = [];
	private $wrapper = [];
	private $choices = [];
	private $when = [];
	private $types = [];

	/**
	 * Return an instance of the class for chaining
	 *
	 * @param string $type
	 * @param string $content
	 */
	public function __construct($type, $content) {

		if (! defined('META_FIELDS')) {
			define('META_FIELDS', 'fields');
		}

		$this->{$type} = $content;

		return $this;

	}

	/**
	 * Return an instance of the class for chaining
	 *
	 * @param string $box
	 */
	public static function box($box) {

		return new Meta('box', $box);

	}

	/**
	 * Return an instance of the class for chaining
	 *
	 * @param string $field
	 */
	public static function field($field) {

		return new Meta('field', $field);

	}

	// Main types

	/**
	 * Register the flexible content modules
	 *
	 * @param string|array $flex
	 */
	public function flex($flex) {

		$this->flex = (array) $flex;

		return $this;

	}

	/**
	 * Register the module fields
	 *
	 * @param string|array $fields
	 */
	public function fields($fields) {

		$this->fields = (array) $fields;

		return $this;

	}

	// Field methods

	/**
	 * Register the field type
	 *
	 * @param string $type
	 */
	public function type($type) {

		$this->type = $type;

		return $this;

	}

	/**
	 * Register the repeater
	 *
	 * @param string $label
	 * @param array $fields
	 */
	public function repeat($label, $fields) {

		$key = $this->clean($this->box . $label);

		return [
			'key' => "field_{$key}",
			'label' => $label,
			'name' => $this->clean($label),
			'type' => 'repeater',
			'sub_fields' => $fields
		];

	}

	/**
	 * Set any wrapping properties
	 *
	 * @param array $wrapper
	 */
	public function wrapper($wrapper) {

		$this->wrapper = (array) $wrapper;

		return $this;

	}

	/**
	 * Set any postType options
	 *
	 * @param array $postTypes
	 */
	public function postType($postTypes) {

		$this->postTypes = (array) $postTypes;

		return $this;

	}

	/**
	 * Set any taxonomy options
	 *
	 * @param array $taxonomies
	 */
	public function taxonomy($taxonomies) {

		$this->taxonomies = $taxonomies;

		return $this;

	}

	/**
	 * Set any role options
	 *
	 * @param array $roles
	 */
	public function role($roles) {

		$this->roles = (array) $roles;

		return $this;

	}

	/**
	 * Set any filter options
	 *
	 * @param array $filter
	 */
	public function filter($filters) {

		$this->filters = (array) $filters;

		return $this;

	}

	/**
	 * Set any choices i.e. for select, radio, checkbox
	 *
	 * @param array $choices
	 */
	public function choices($choices) {

		$this->choices = (array) $choices;

		return $this;

	}

	/**
	 * Set where you want the modules to display
	 *
	 * @param array $conditions
	 */
	public function when($conditions) {

		if (! is_array($conditions[0])) {
			$conditions = [ $conditions ];
		}

		array_map(function($condition) {

			$key = $this->clean($condition[0]);

			array_push($this->when, $this->getParams("field_{$key}", $condition[1], $condition[2], 'field', false));

		}, $conditions);


		return $this;

	}

	// Box methods

	/**
	 * Set where you want the modules to display
	 *
	 * @param string|array $types
	 */
	public function on($types) {

		$this->types = (array) $types;

		return $this;

	}

	// Class methods

	/**
	 * Set up the flexible array
	 *
	 * @param array $flex
	 */
	private function getFlexible($flex) {

		$key = $this->clean($this->box);

		return [
			'key' => "field_{$key}",
			'name' => $this->clean($this->box),
			'label' => $this->box,
			'type' => 'flexible_content',
			'layouts' => $this->getFields($flex, true)
		];

	}

	/**
	 * Set up the fields array
	 *
	 * @param array $fields
	 * @param bool $sub
	 */
	private function getFields($fields, $sub = false) {

		$formatted = [];

		foreach ($fields as $field) {
			$fetched = $this->includes($field);

			if ($sub) {

				$key = $this->clean($this->box . $field);

				array_push($formatted, [
					'key' => "field_{$key}",
					'name' => $this->clean($field),
					'label' => $field,
					'sub_fields' => $fetched
				]);

			} else {

				foreach ($fetched as $fetch) {
					array_push($formatted, $fetch);
				}

			}

		}

		return $formatted;

	}

	/**
	 * Set up the needed parameters
	 *
	 * @param string $param
	 * @param string $operator
	 * @param array $values
	 * @param string $type
	 * @param bool $orStatment
	 */
	private function getParams($param, $operator, $values, $type = 'param', $orStatment = false) {

		return array_map(function($value) use ($param, $operator, $type, $orStatment) {

			$params = [
				$type => $param,
				'operator' => $operator,
				'value' => $value
			];

			if ($orStatment) {
				$params = [ $params ];
			}

			return $params;

		}, (array) $values);


	}

	/**
	 * Set the fields default options
	 *
	 * @param array $field
	 */
	public function set($field = []) {

		$key = $this->clean($this->box . $this->field);

		$field = array_merge($field, [
			'type' => $this->type,
			'key' => "field_{$key}",
			'label' => $this->field,
			'name' => $this->clean($this->field),
		]);

		return $this->setOptions($field);

	}

	/**
	 * Set the fields extra options
	 *
	 * @param array $field
	 */
	public function setOptions($field) {

		if ($this->choices) {
			$field['choices'] = $this->choices;
		}
		if ($this->wrapper) {
			$field['wrapper'] = $this->wrapper;
		}
		if ($this->when) {
			$field['conditional_logic'] = $this->when;
		}
		if ($this->postTypes) {
			$field['post_type'] = $this->postTypes;
		}
		if ($this->taxonomies) {
			$field['taxonomy'] = $this->taxonomies;
		}
		if ($this->filters) {
			$field['filters'] = $this->filters;
		}
		if ($this->roles) {
			$field['role'] = $this->roles;
		}

		return $field;

	}

	/**
	 * Register the finalized content
	 */
	public function register() {

		$fields = [];
		$key = $this->clean($this->box);

		if ($this->flex) {
			$fields = [ $this->getFlexible($this->flex) ];
		} elseif ($this->fields) {
			$fields = $this->getFields($this->fields);
		}

		$reg = [
			'key' => "group_{$key}",
			'title' => $this->box,
			'fields' => $fields,
			'location' => $this->getParams('post_type', '==', $this->types, 'param', true)
		];

		acf_add_local_field_group($reg);

	}

	/**
	 * Includes
	 *
	 * @param string $field
	 */
	private function includes($field) {

		$file = $this->clean($field);

		$fields = require plugin_dir_path(__FILE__) . META_FIELDS . "/{$file}.php";

		return $fields;

	}

	/**
	 * Clean and format the string
	 *
	 * @param string $text
	 */
	private function clean($text) {

		return preg_replace('/[^a-zA-Z0-9_\-]/', '', lcfirst(ucwords($text)));

	}

}
