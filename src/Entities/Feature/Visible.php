<?php

namespace PHRE\Entities\Feature;

use PHRE\DataSource\DataRecord;

trait Visible {

	private $visibleFn;

	/**
	 * function (DataRecord $record);
	 * @param callable $visibleFn
	 */
	public function setVisible($visibleFn) {
		$this->visibleFn = $visibleFn;
		return $this;
	}

	/**
	 * @param DataRecord $record
	 * @return boolean
	 */
	protected function isVisible(DataRecord $record) {
		if (is_callable($this->visibleFn)) {
			return (boolean) call_user_func($this->visibleFn, $record);
		} elseif ($this->visibleFn) {
			return (boolean) $this->visibleFn;
		} else {
			return true;
		}
	}

}
