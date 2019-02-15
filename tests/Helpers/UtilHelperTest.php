<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UtilHelperTest extends TestCase
{
    public function testSplitName()
    {
        $fl = split_name("Anderson de Suza Lima");

        $this->assertEquals(
            "Anderson", $fl[0]
        );
        $this->assertEquals(
            "de Suza Lima", $fl[1]
        );
    }

    public function testReadFile(){
        $txt = "Two and two is four!\nTwo and two is four!\n";
        writeFile("/tmp/phpUnitTest.txt",$txt);

        $r = file_get_contents("/tmp/phpUnitTest.txt");
        $this->assertEquals($txt,$r);

    }
}
 