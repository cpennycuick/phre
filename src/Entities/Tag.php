<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;
use \PHRE\DataHolder\Attributes;

class Tag extends Element {

	use \PHRE\Entities\Feature\SubElements;
	use \PHRE\Entities\Feature\Visible;

	/**
	 * @var Attributes;
	 */
	private $attributes;
	private $tag;

	protected function __construct($tag) {
		parent::__construct();
		$this->attributes = new Attributes();
		$this->setTag($tag);
	}

	public static function __callStatic($name, $arguments) {
		$class = '\PHRE\Entities\Tag\\'.$name;

		if (class_exists($class)) {
			return $class::create(...$arguments);
		}

		return new static($name);
	}

	public function setAttribute($name, $value) {
		$this->attributes->set($name, $value);
		return $this;
	}

	public function setStyle($name, $value = null) {
		$this->attributes->style()->set($name, $value);
		return $this;
	}

	public function render(DataSource $data) {
		if (!$this->isVisible($data->current())) {
			return null;
		}

		return implode('', [
			$this->renderTagOpen(),
			$this->renderElements($data),
			$this->renderTagClose(),
		]);
	}

	public function reset() {
		$this->resetElements();
	}

	private function setTag($tag) {
		if (empty($tag)) {
			throw new \Exception('Element tag not provided.');
		}

		$this->tag = strtolower(trim($tag));
	}

	public function getTag() {
		return $this->tag;
	}

	protected function renderTagOpen() {
		$attributesText = ($this->attributes->hasAttributes()
			? ' ' . (string) $this->attributes
			: ''
		);

		return "<{$this->tag}{$attributesText}>";
	}

	protected function renderTagClose() {
		return "</{$this->tag}>";
	}

}
