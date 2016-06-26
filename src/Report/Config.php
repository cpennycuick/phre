<?php

namespace PHRE\Report;

class Config {

	const KEY_ORIENTATION = 'Orientation';
	const KEY_MARGIN = 'Margin';

	const ORIENTATION_LANDSCAPE = 'Landscape';
	const ORIENTATION_PORTRAIT = 'Portrait';

	private $config = [];

	public function __construct() {
		$this->setOrientationPortrait();
		$this->setMargin(10, 10, 10, 10);
	}

	private function set($key, $value) {
		$this->config[$key] = $value;
		return $this;
	}

	private function add($key, $value, $index = null) {
		if (!isset($this->config[$key])) {
			$this->config[$key] = [];
		}

		if ($index !== null) {
			$this->config[$key][$index] = $value;
		} else {
			$this->config[$key][] = $value;
		}
		return $this;
	}

	public function setOrientationLandscape() {
		return $this->set(self::KEY_ORIENTATION, self::ORIENTATION_LANDSCAPE);
	}
	public function setOrientationPortrait() {
		return $this->set(self::KEY_ORIENTATION, self::ORIENTATION_PORTRAIT);
	}

	public function setMargin($top, $right, $bottom, $left) {
		return $this->set(self::KEY_MARGIN, [
			'Top' => $top,
			'Right' => $right,
			'Bottom' => $bottom,
			'Left' => $left
		]);
	}

	public function get($key) {
		if (!array_key_exists($key, $this->config)) {
			return null;
		}

		return $this->config[$key];
	}

}
