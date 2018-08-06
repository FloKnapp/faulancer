<?php

namespace Faulancer\View\Helper;

use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\Config;
use Faulancer\View\AbstractViewHelper;

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
     * @param string         $type     The asset type
     * @param bool           $optimize If all assets should be concatenated
     *                                 within style tag in head
     *
     * @return string
     *
     * @throws ServiceNotFoundException
     */
    public function __invoke($type, $optimize = false)
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
        $files = $this->view->getVariable('assets' . ucfirst($type));

        if (empty($files)) {
            return '';
        }

        if ($type === 'css' && $optimize) {

            $result  = '<style type="text/css">';
            $result .= $this->_collectAssetsContent($files, $type);
            $result .= '</style>';
            return $result;

        }

        if ($type === 'js' && $optimize) {

            $result  = '<script>';
            $result .= $this->_collectAssetsContent($files, $type);
            $result .= '</script>';
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
     * @param array  $files The asset files
     * @param string $type  The assets type
     *
     * @return string
     */
    private function _collectAssetsContent(array $files, string $type)
    {
        /** @var Config $config */
        $config  = $this->getServiceLocator()->get(Config::class);
        $docRoot = realpath($config->get('projectRoot') . '/public');

        $content  = '';
        $contents = [];

        foreach ($files as $file) {

            if (file_exists($docRoot . $file)) {
                $contents[] = file_get_contents($docRoot . $file);
            }

        }

        if ($type === 'css') {

            $content = str_replace(
                ["\n", "\t", "  ", ": ", " {", "{ ", " }", ";}"],
                ["", "", "", ":", "{", "{", "}", "}"],
                implode('', $contents)
            );

        }

        return $content;
    }

}