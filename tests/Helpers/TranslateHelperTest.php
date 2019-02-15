<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
class TranslateHelperTest extends TestCase
{
    
    public function testTranslate(){
        $this->assertEquals('Sugerido',translate('suggested'));
    }
}
 