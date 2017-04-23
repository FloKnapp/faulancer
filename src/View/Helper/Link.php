<?php
/**
 * Class Link | Link.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Http\Request;
use Faulancer\Service\Config;
use Faulancer\Service\RequestService;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Link
 */
class Link extends AbstractViewHelper
{

    /**
     * @param ViewController $view
     * @param string         $routeName
     * @return string
     */
    public function __invoke(ViewController $view, $routeName)
    {

        $active         = '';
        $serviceLocator = $this->getServiceLocator();

        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);

        /** @var Request $request */
        $request = $serviceLocator->get(RequestService::class);

        $route = $config->get('routes:' . $routeName);

        if ($request->getUri() === $route['path']) {
            $active = 'class="selected" ';
        }

        $linkPattern = '<a ' . $active . 'href="%s" onfocus="blur()">%s</a>';

        $link = sprintf($linkPattern, $route['path'], $route['title']);

        return $link;

    }

}