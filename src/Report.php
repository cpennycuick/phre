<?php

namespace PHRE;

use PHRE\DataSource\DataSource;
use PHRE\DataHolder\StyleSheet;

/**
 * Class for defining a report.
 *
 * This class is the first entry point from which the report
 * structure is defined. All other elements are added to this
 * class.
 *
 * Currently this report engine only outputs in two different
 * formats: HTML and PDF. In both cases, the string contents
 * is returned to the user to handle. The user can save it to
 * a file, or output it to the client.
 *
 * When outputting PDF, use <code>header('Content-Type: application/pdf');</code>
 * before echoing the contents to the client. The page will now
 * render as a PDF.
 *
 * <b>Basic example:</b>
 * <pre>
 * PHRE\Report::create()
 *     ->add(PHRE\Entities\Tag::h1()
 *         ->add(PHRE\Entities\Text::create('Hello World!')
 *     )
 *     ->generateHTML($data);
 * </pre>
 * <b>Output:</b>
 * <pre>
 * &lt;h1&gt;Hello World&lt;/h1&gt;
 * </pre>
 */
class Report
{

    use \PHRE\Entities\Feature\SubElements;

    const FORMAT_HTML = 'HTML';
    const FORMAT_PDF = 'PDF';

    /**
     * @var Report\Config
     */
    private $config;
    /**
     * @var StyleSheet[]
     */
    private $styleSheets = [];

    public function __construct()
    {
        $this->config = new Report\Config();
    }

    /**
     * Shorthand way of creating a new report.
     *
     * @return \static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Get the configuration object for this report.
     *
     * @return Report\Config
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * Add a StyleSheet object to this report.
     *
     * These style sheet objects are added in the head
     * of the html document.
     *
     * @param StyleSheet $styleSheet
     * @return \PHRE\Report
     */
    public function addStyleSheet(StyleSheet $styleSheet)
    {
        $this->styleSheets[] = $styleSheet;
        return $this;
    }

    /**
     * Generate the report and return it's HTML content.
     * @param mixed $data
     * @return string The generated report HTML.
     */
    public function generateHTML($data)
    {
        return $this->generate(self::FORMAT_HTML, $data);
    }

    /**
     * Generate the report and return it's raw PDF content.
     * @param type $data
     * @return string The generated report PDF.
     */
    public function generatePDF($data)
    {
        return $this->generate(self::FORMAT_PDF, $data);
    }

    /**
     * @todo Cleanup.
     * @todo Support debugging better - config option.
     */
    private function generate($format, $data)
    {
        $dataSource = new DataSource($data);

        $this->reset();

        ob_start();

        $parts = ['<html id="' . strtolower($format) . '"><head>'];

        foreach ($this->styleSheets as $styleSheet) {
            $parts[] = '<style>' . (string) $styleSheet . '</style>';
        }

        $parts[] = '<style>' . file_get_contents(__DIR__ . '/default.css') . '</style>';

        if ($format === self::FORMAT_HTML) {
            $parts[] = '<style>' . (string) $this->createHTMLStyleSheetFromConfig() . '</style>';
        }

        $parts[] = '</head><body>';

        if ($format === self::FORMAT_PDF) {
            // include PDF specific html
        }

        while ($dataSource->current()->valid() and count($parts) < 1000) {
            $parts[] = $this->renderElements($dataSource);

            $dataSource->next();
        }

        $parts[] = '</body></html>';

        $html = implode("\n", $parts);

        if ($format === self::FORMAT_PDF) {
            $output = $this->outputPDF($html);
        } elseif ($format === self::FORMAT_HTML) {
            $output = $html;
        } else {
            throw new Exception('Unknown format: ' . $format);
        }

        $debug = ob_get_contents();
        ob_end_clean();

//        echo $debug;

        return $output;
    }

    protected function reset()
    {
        $this->resetElements();
    }

    private function createHTMLStyleSheetFromConfig()
    {
        $margins = $this->config->get(Report\Config::KEY_MARGIN);

        $styleSheet = (new StyleSheet())
            ->add('html#html .page', (new DataHolder\Style())
            ->set('padding-top', $margins['Top'] . 'mm')
            ->set('padding-right', $margins['Right'] . 'mm')
            ->set('padding-bottom', $margins['Bottom'] . 'mm')
            ->set('padding-left', $margins['Left'] . 'mm')
        );

        return $styleSheet;
    }

    /**
     * @todo Allow the user to specify the path to the binary.
     * @todo Test on all OS's.
     */
    private function outputPDF($html)
    {
        $margins = $this->config->get(Report\Config::KEY_MARGIN);

        $pdf = new \mikehaertl\wkhtmlto\Pdf([
            'binary' => __DIR__ . '/../vendor/wemersonjanuario/wkhtmltopdf-windows/bin/64bit/wkhtmltopdf.exe',
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
