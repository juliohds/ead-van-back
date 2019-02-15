<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Facet;
class EntityTest extends TestCase
{
    public function testGenericConstruct()
    {

        $facet = new Facet(['id' => 1,'kind' => 'discipline','title' => 'Disciplina']);

        $this->assertEquals(
            1, $facet->id
        );
        $this->assertEquals(
            'discipline', $facet->kind
        );
        $this->assertEquals(
            'Disciplina', $facet->title
        );
    }
}
