<?php

namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;

/**
 * Class CodeBlock | CodeBlock.php
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
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

            if ($lineNr >= $actualLine - 8 && $lineNr <= $actualLine + 4) {

                $l = str_replace("\t", $tabChar, $line);
                $l = str_replace(" ", $regularChar, $l);
                $l = str_replace(['<', '>'], ['&lsaquo;', '&rsaquo;'], $l);

                $data = '<code>' . $l . '</code>';

                if ($lineNr === $actualLine) {
                    $lines .= '<span class="line highlight"><span class="line-nr">' . $lineNr . '</span>' . $data . '</span>';
                } else {
                    $lines .= '<span class="line"><span class="line-nr">' . $lineNr . '</span>' . $data . '</span>';
                }

            }

        }

        fclose($file);

        return $lines;

    }

}