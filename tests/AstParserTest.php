<?php

use Osbre\FabricSchema\AstParser;
use PHPUnit\Framework\TestCase;

final class AstParserTest extends TestCase
{
    public function test_it_can_return_objects_structure(): void
    {
        $astJson = file_get_contents(__DIR__.'/fabric-jsdoc-ast.json');
        $astData = json_decode($astJson);

        $objectsStructure = (new AstParser)->parse($astData);
        dd($objectsStructure);
    }
}
