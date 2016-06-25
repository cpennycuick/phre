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

	private $styleSheets = [];

	public function __construct() {
		$this->config = new ReportConfig();
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

		ob_start();

		$parts = ['<html id="'.strtolower($format).'"><head>'];

		foreach ($this->styleSheets as $styleSheet) {
			$parts[] = '<style>' . (string) $styleSheet . '</style>';
		}

		$parts[] = '<style>' . file_get_contents(__DIR__.'/default.css') . '</style>';

		if ($format === self::FORMAT_HTML) {
			$parts[] = '<style>' . (string) $this->createHTMLStyleSheetFromConfig() . '</style>';
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
			$output = $this->outputPDF($html);
		} else if ($format === self::FORMAT_HTML) {
			$output = $html;
		} else {
			throw new Exception('Unknown format: '.$format);
		}

		$debug = ob_get_contents();
		ob_end_clean();

//		echo $debug;

		return $output;
	}

	protected function reset() {
		$this->resetElements();
	}

	private function createHTMLStyleSheetFromConfig() {
		$margins = $this->config->get(ReportConfig::KEY_MARGIN);

		$styleSheet = (new StyleSheet())
			->add('html#html .page', (new DataHolder\Style())
				->set('padding-top', $margins['Top'].'mm')
				->set('padding-right', $margins['Right'].'mm')
				->set('padding-bottom', $margins['Bottom'].'mm')
				->set('padding-left', $margins['Left'].'mm')
			);

		return $styleSheet;
	}

	private function outputPDF($html) {
		$margins = $this->config->get(ReportConfig::KEY_MARGIN);

		$pdf = new \mikehaertl\wkhtmlto\Pdf([
			'binary' => 'vendor/wemersonjanuario/wkhtmltopdf-windows/bin/64bit/wkhtmltopdf.exe',
			'no-outline', // Make Chrome not complain
			'margin-top' => $margins['Top'],
			'margin-right' => $margins['Right'],
			'margin-bottom' => $margins['Bottom'],
			'margin-left' => $margins['Left'],
			'disable-smart-shrinking', // Default page options

			// Windows options
			'commandOptions' => [
				'escapeArgs' => false,
				'procOptions' => [
					// This will bypass the cmd.exe which seems to be recommended on Windows
					'bypass_shell' => true,
					// Also worth a try if you get unexplainable errors
					'suppress_errors' => true,
				],
			],
		]);

		$pdf->addPage($html);

		return $pdf->toString();
	}

}
