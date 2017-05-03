<?php
/**
 * @link            http://www.sterc.nl
 * @version         1.0.0
 * @package         Site
 * @author          Sander Drenth <sander@sterc.nl>
 */

class Site
{
    /**
     * The modX object.
     *
     * @since    1.0.0
     * @access   public
     * @var      null|modX      $modx
     */
    public $modx;

    /**
     * The namespace for this package.
     *
     * @since    1.0.0
     * @access   public
     * @var      string         $namespace
     */
    public $namespace = 'site';

    /**
     * Holds all configs values.
     *
     * @since    1.0.0
     * @access   public
     * @var      array          $config
     */
    public $options = [];

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     * @param    modX    $modx
     * @param    array   $options
     */
    public function __construct(modX $modx, array $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->modx->getOption('namespace', $options, 'site');

        $basePath   = $this->modx->getOption(
            'site.core_path',
            $options,
            $this->modx->getOption('core_path') . 'components/site/'
        );
        $assetsUrl  = $this->modx->getOption(
            'site.assets_url',
            $options,
            $this->modx->getOption('assets_url') . 'components/site/'
        );
        $assetsPath = $this->modx->getOption(
            'site.assets_path',
            $options,
            $this->modx->getOption('assets_path') . 'components/site/'
        );

        $this->options = array_merge([
            'base_path'       => $basePath,
            'core_path'       => $basePath,
            'model_path'      => $basePath . 'model/',
            'processors_path' => $basePath . 'processors/',
            'elements_path'   => $basePath . 'elements/',
            'templates_path'  => $basePath . 'templates/',
            'assets_path'     => $assetsPath,
            'js_url'          => $assetsUrl . 'js/',
            'css_url'         => $assetsUrl . 'css/',
            'assets_url'      => $assetsUrl,
            'connector_url'   => $assetsUrl . 'connector.php',
        ], $options);

        $this->modx->addPackage('site', $this->options['model_path']);
        $this->modx->lexicon->load('site:default');

        $this->modx->getService('parser', 'modParser');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @since   1.0.0
     * @param   string      $key
     * @param   array       $options
     * @param   mixed       $default
     *
     * @return  mixed
     */
    public function getOption($key, array $options = [], $default = null)
    {
        $option = $default;

        if (!empty($key) && is_string($key)) {
            if ($options !== null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }

        return $option;
    }
}
