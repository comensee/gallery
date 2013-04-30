<?php

namespace NetworksLabs\CsvReaderBundle\Tests\Importer;

use NetworksLabs\CsvReaderBundle\Importer\Importer;
use NetworksLabs\CsvReaderBundle\File\FileReader;
use Symfony\Component\HttpFoundation\File\File;

class ImportTest extends \PHPUnit_Framework_TestCase {
    
    public function testImportExcel()
    {
        $import = new Importer(new FileReader(__DIR__.'/fete.xlsx'));
        $import_result = $import->import();

        // assert that our calculator added the numbers correctly!
        $this->assertNotEquals(42, count($import_result));
        $this->assertEquals(93, count($import_result));
    }    

    public function testImportCsv()
    {
        $import = new Importer(new FileReader(__DIR__.'/test.csv'));
        $import_result = $import->import();

        // assert that our calculator added the numbers correctly!
        $this->assertEquals(3, count($import_result));
    }  


}
