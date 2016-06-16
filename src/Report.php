<?php

namespace PHRE;

use PHRE\DataSource\DataSource;
use PHRE\DataHolder\StyleSheet;

class Report {

	use \PHRE\Entities\Feature\SubElements;

	const FORMAT_HTML = 'HTML';
	const FORMAT_PDF = 'PDF';

	/**
	 * @var ReportConfig
	 */
	private $config;
	/**
	 * @var StyleSheet
	 */
	private $htmlStyleSheet;

	private $styleSheets = [];

	public function __construct() {
		$this->config = new ReportConfig();
		$this->htmlStyleSheet = (new StyleSheet())
			->add('html', (new DataHolder\Style())
				->set('margin', '0')
				->set('padding', '0')
				->set('background-color', '#EEE')
			)
			->add('body', (new DataHolder\Style())
				->set('margin', '0')
				->set('padding', '0')
				->set('text-align', 'center')
			)
			->add('page', (new DataHolder\Style())
				->set('margin', '20px auto')
				->set('padding', '4px 10px')
				->set('border', '1px solid red')
				->set('text-align', 'left')
				->set('background-color', 'white')
				->set('display', 'block')
				->set('width', '600px')
				->set('min-height', '800px')
			);
	}

	public static function create() {
		return new static();
	}

	/**
	 * @return ReportConfig
	 */
	public function config() {
		return $this->config;
	}

	public function addStyleSheet(StyleSheet $styleSheet) {
		$this->styleSheets[] = $styleSheet;
		return $this;
	}

	public function generateHTML(DataSource $data) {
		return $this->generate(self::FORMAT_HTML, $data);
	}

	public function generatePDF(DataSource $data) {
		return $this->generate(self::FORMAT_PDF, $data);
	}

	private function generate($format, DataSource $data) {
		$data->reset();
		$this->reset();

		$parts = ['<html><head>'];

		foreach ($this->styleSheets as $styleSheet) {
			$parts[] = '<style>' . (string) $styleSheet . '</style>';
		}
		if ($format === self::FORMAT_HTML) {
			$parts[] = '<style>' . (string) $this->htmlStyleSheet . '</style>';
		}

		$parts[] = '</head><body>';

		if ($format === self::FORMAT_PDF) {
			// include PDF specific html
		}

		while ($data->current()->valid() and count($parts) < 1000) {
			$parts[] = $this->renderElements($data);

			$data->next();
		}

		$parts[] = '</body></html>';

		$html = implode("\n", $parts);

		if ($format === self::FORMAT_PDF) {
			$mpdf = new \mPDF('c','A4');
			//$mpdf->SetProtection(array('print'));
			//$mpdf->SetTitle("Acme Trading Co. - Invoice");
			//$mpdf->SetAuthor("Acme Trading Co.");
			//$mpdf->SetWatermarkText("Paid");
			//$mpdf->showWatermarkText = true;
			//$mpdf->watermark_font = 'DejaVuSansCondensed';
			//$mpdf->watermarkTextAlpha = 0.1;
			//$mpdf->SetDisplayMode('fullpage');
			$mpdf->WriteHTML($html);

			$output = $mpdf->Output(null, 'S'); // return [S]tring

		} else if ($format === self::FORMAT_HTML) {
			$output = $html;
		} else {
			throw new Exception('Unknown format: '.$format);
		}

		return $output;
	}

	protected function reset() {
		$this->resetElements();
	}

}
