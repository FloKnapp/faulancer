<?php
/**
 * Class Link | Link.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\RouteInvalidException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Http\Request;
use Faulancer\Service\Config;
use Faulancer\Service\RequestService;
use Faulancer\Translate\Translator;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Link
 */
class Link extends AbstractViewHelper
{

    /**
     * Render a ready-to-use link within an 'a' tag
     *
     * @param ViewController $view
     * @param string         $routeName
     * @param array          $elementAttributes
     * @return string
     *
     * @throws RouteInvalidException
     * @throws ServiceNotFoundException
     * @throws ConfigInvalidException
     *
     * @codeCoverageIgnore
     */
    public function __invoke(ViewController $view, $routeName, $elementAttributes = [])
    {
        $id    = '';
        $class = '';
        $style = '';

        $serviceLocator = $this->getServiceLocator();

        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);

        /** @var Request $request */
        $request = $serviceLocator->get(RequestService::class);

        $route = $config->get('routes:' . $routeName);

        if (empty($route)) {
            throw new RouteInvalidException('No valid route for "' . $routeName . '" found.');
        }

        if ($request->getPath() === $route['path']) {
            $elementAttributes['class'][] = 'selected';
        }

        if (!empty($elementAttributes['id'])) {
            $id = ' id="' . implode(' ', $elementAttributes['id']) . '" ';
        }

        if (!empty($elementAttributes['class'])){
            $class = 'class="' . implode(' ', $elementAttributes['class']) . '" ';
        }

        if (!empty($elementAttributes['style'])) {
            $style = 'style="' . implode(' ', $elementAttributes['style']) . '" ';
        }

        $linkPattern = '<a ' . $id . $class . $style . 'href="%s" onfocus="blur()">%s</a>';

        if (!empty($route['i18n_key']) && !empty($route['title'])) {
            $translator = new Translator();
            $route['title'] = $translator->translate($route['i18n_key']);
        }

        $link = sprintf($linkPattern, $route['path'], $route['title']);

        return $link;

    }

}