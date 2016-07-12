<?php

namespace PHRE\Report;

/**
 * Class for configuring the options of the report.
 *
 * All features of the report which can be configured
 * are available as public methods. When generating,
 * the report will read apply these configuration options.
 *
 * <b>Defaults:</b>
 * <ul>
 *     <li>Orientation: Portrait</li>
 *     <li>Margins: 10, 10, 10, 10</li>
 * </ul>
 */
class Config
{

    const KEY_ORIENTATION = 'Orientation';
    const KEY_MARGIN = 'Margin';
    const ORIENTATION_LANDSCAPE = 'Landscape';
    const ORIENTATION_PORTRAIT = 'Portrait';

    private $config = [];

    public function __construct()
    {
        $this->setOrientationPortrait();
        $this->setMargin(10, 10, 10, 10);
    }

    /**
     * Set report page orientation to Landscape.
     *
     * @return Config
     */
    public function setOrientationLandscape()
    {
        return $this->set(self::KEY_ORIENTATION, self::ORIENTATION_LANDSCAPE);
    }

    /**
     * Set report page orientation to Portait.
     *
     * @return Config
     */
    public function setOrientationPortrait()
    {
        return $this->set(self::KEY_ORIENTATION, self::ORIENTATION_PORTRAIT);
    }

    /**
     * Set the margin for all the pages.
     *
     * All measurements are in milimetres.
     *
     * @param double $top Height at the top of the page.
     * @param double $right Width at the right of the page.
     * @param double $bottom Height at the bottom of the page.
     * @param double $left Width at the left of the page.
     * @return Config
     */
    public function setMargin($top, $right, $bottom, $left)
    {
        return $this->set(self::KEY_MARGIN, [
                'Top' => $top,
                'Right' => $right,
                'Bottom' => $bottom,
                'Left' => $left
        ]);
    }

    /**
     * Function to get the config option values.
     *
     * @param string $key The key of the stored value. Can be referened by the constants on this class prefixed with <code>KEY_*</pre>.
     * @return mixed
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->config)) {
            return null;
        }

        return $this->config[$key];
    }

    private function set($key, $value)
    {
        $this->config[$key] = $value;
        return $this;
    }

    private function add($key, $value, $index = null)
    {
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

}
