<?php declare (strict_types = 1);
/**
 * This file is part of silex-twig-breadcrumb-extension
 *
 * (c) Gregor Panek <gp@gregorpanek.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace nymo\Twig\Extension;

use Pimple\Container;

/**
 * Class BreadCrumbExtension
 * @package nymo\Twig\Extension
 * @author Gregor Panek <gp@gregorpanek.de>
 */
class BreadCrumbExtension extends \Twig_Extension
{
    const _CONFIG = [
        'separator' => '>',
        'template' => 'breadcrumbs'
    ];

    /**
     * @var Container
     */
    protected $app;

    /**
     * config values
     *
     * @var array;
     */
    protected $currentConfig;

    /**
     * @param Container $app
     */
    public function __construct(Container $app, array $config = null)
    {
        $this->app = $app;
        $this->currentConfig = array_merge(self::_CONFIG, $config ?? []);

        //create loader to load base template which can be overridden by user
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../Resources/Views');
        $this->app['twig.loader']->addLoader($loader);
    }

    /**
     * @inheritDoc
     */
    public function getFunctions() : array
    {
        return [
            'renderBreadCrumbs' => new \Twig_SimpleFunction(
                'renderBreadCrumbs',
                [$this, 'renderBreadCrumbs'],
                ['is_safe' => ['html']]
            )
        ];
    }

    /**
     * Returns the rendered breadcrumb template
     * @return string
     */
    public function renderBreadCrumbs() : string
    {
        $translator = isset($this->app['translator']) ? true : false;

        return $this->app['twig']->render(
            $this->currentConfig['template'] . '.html.twig',
            [
                'breadcrumbs' => $this->app['breadcrumbs']->getItems(),
                'separator' => $this->currentConfig['separator'],
                'translator' => $translator
            ]
        );
    }

    /**
     * Returns the name of the extension
     * @return string The extension name
     */
    public function getName() : string
    {
        return 'renderBreadCrumbs';
    }
}
