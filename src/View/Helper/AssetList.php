<?php
/**
 * Class AssetList | AssetList.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Service\Config;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class AssetList
 */
class AssetList extends AbstractViewHelper
{

    /**
     * Render a asset list by type
     *
     * @param ViewController $view
     * @param                $type
     * @return string
     */
    public function __invoke(ViewController $view, $type)
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

        foreach ($files AS $file) {
            $result .= sprintf($pattern, $file) . "\n";
        }

        return $result;
    }

}