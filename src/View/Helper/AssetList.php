<?php

namespace Faulancer\View\Helper;

use Faulancer\Http\Request;
use Faulancer\Service\Config;
use Faulancer\Service\RequestService;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class AssetList
 *
 * @category ViewHelper
 * @package  Faulancer\View\Helper
 * @author   Florian Knapp <office@florianknapp.de>
 * @license  MIT License
 * @link     No link provided
 */
class AssetList extends AbstractViewHelper
{

    /**
     * Render a asset list by type
     *
     * @param ViewController $view   The current view
     * @param string         $type   The asset type
     * @param bool           $inHead If all assets should be concatenated
     *                               within style tag in head
     *
     * @return string
     */
    public function __invoke(ViewController $view, $type, $inHead = false)
    {
        $result  = '';
        $pattern = '';

        switch ($type) {

        case 'js':
            $pattern = '<script src="%s"></script>';
            break;

        case 'css':
            $pattern = '<link rel="stylesheet" type="text/css" href="%s">';
            break;

        }

        /** @var array $files */
        $files = $view->getVariable('assets' . ucfirst($type));

        if (empty($files)) {
            return '';
        }

        if (defined('APPLICATION_ENV') && APPLICATION_ENV === 'production' && $inHead) {

            $result  = '<style type="text/css">';
            $result .= $this->_collectAssetsContent($files);
            $result .= '</style>';
            return $result;

        }

        foreach ($files AS $file) {
            $result .= sprintf($pattern, $file) . "\n";
        }

        return $result;
    }

    /**
     * Collect all assets content for concatenation
     *
     * @param array $files The asset files
     *
     * @return string
     */
    private function _collectAssetsContent(array $files)
    {
        /** @var Config $config */
        $config  = $this->getServiceLocator()->get(Config::class);
        $docRoot = realpath($config->get('projectRoot') . '/public');

        $contents = [];

        foreach ($files as $file) {

            if (file_exists($docRoot . $file)) {
                $contents[] = file_get_contents($docRoot . $file);
            }

        }

        $content = str_replace(
            ["\n", "\t", "  ", ": ", " {", "{ ", " }", ";}"],
            ["", "", "", ":", "{", "{", "}", "}"],
            implode('', $contents)
        );

        return $content;
    }

}