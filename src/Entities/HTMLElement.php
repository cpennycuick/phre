<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;
use \PHRE\DataHolder\Attributes;
use \PHRE\DataHolder\Style;

abstract class HTMLElement extends Element {

	use \PHRE\Entities\Feature\SubElements;

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

	public function setAttribute($name, $value) {
		$this->attributes->set($name, $value);
		return $this;
	}

	public function setStyle($name, $value = null) {
		$this->attributes->style()->set($name, $value);
		return $this;
	}

	public final function render(DataSource $data) {
		return implode("\n", [
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

		$this->tag = (string) $tag;
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
