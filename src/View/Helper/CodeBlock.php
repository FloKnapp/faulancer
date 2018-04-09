<?php
/**
 * Class CodeBlock | CodeBlock.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class CodeBlock
 */
class CodeBlock extends AbstractViewHelper
{

    /**
     * @param                $filename
     * @param int            $actualLine
     * @return string
     * @codeCoverageIgnore
     */
    public function __invoke($filename, $actualLine = 0)
    {

        $lineNr = 0;
        $lines  = '';
        $file   = fopen($filename, 'r');

        $tabChar = '&nbsp;&nbsp;&nbsp;&nbsp;';
        $regularChar = '&nbsp;';

        while ($line = fgets($file)) {

            $lineNr++;

            if ($lineNr >= $actualLine - 6 && $lineNr <= $actualLine + 3) {

                //$l = str_replace("\t", $tabChar, $line);
                //$l = str_replace(" ", $regularChar, $l);

                $data = '<span>' . highlight_string($line, true) . '</span>';

                if ($lineNr === $actualLine) {
                    $lines .= '<span class="line highlight"><span class="line-nr">' . $lineNr . '</span>' . $data . '</span>';
                } else {
                    $lines .= '<span class="line"><span class="line-nr">' . $lineNr . '</span>' . $data . '</span>';
                }

            }

        }

        return $lines;

    }

}