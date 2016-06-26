<?php

namespace PHRE\Formatter;

class Currency extends Number {

	private $currencySymbol = null;
	private $currencyCode = null;

	private $negativeFormat = ['-', '', '', ''];

	public function __construct() {
		$this->setFormatCustom(2, '.', ',', false);
	}

	public function setCurrency($currencySymbol, $currencyCode = null) {
		$this->currencySymbol = $currencySymbol;
		$this->currencyCode = ($currencyCode ? ' '.$currencyCode : null);

		return $this;
	}

	public function setNegativeFormat($preCurrencySymbol, $preNumber, $postNumber, $postCurrencyCode) {
		$this->negativeFormat = [
			$preCurrencySymbol,
			$preNumber,
			$postNumber,
			$postCurrencyCode,
		];

		return $this;
	}

	public function formatValue($value) {
		$isNegative = ($value < 0);

		list($preCurrencySymbol, $preNumber, $postNumber, $postCurrencyCode) = (
			$isNegative ? $this->negativeFormat : ['', '', '', '']
		);

		return implode('', [
			$preCurrencySymbol,
			$this->currencySymbol,
			$preNumber,
			parent::formatValue(abs($value)),
			$postNumber,
			$this->currencyCode,
			$postCurrencyCode,
		]);
	}

}
