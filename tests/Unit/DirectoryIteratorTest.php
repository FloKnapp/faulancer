<?php

namespace Faulancer\Test\Unit;

use Faulancer\Helper\DirectoryIterator;
use PHPUnit\Framework\TestCase;

/**
 * File DirectoryIteratorTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class DirectoryIteratorTest extends TestCase
{

    public function testReadWithoutConstants()
    {
        $files = DirectoryIterator::getFiles();
        $this->assertNotEmpty($files);
        $this->assertTrue(is_array($files));

        foreach ($files as $key => $file) {
            $this->assertTrue(is_string($key));
            $this->assertTrue(is_string($file[0]));
        }

    }

}