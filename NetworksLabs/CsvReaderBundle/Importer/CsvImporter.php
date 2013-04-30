<?php

namespace NetworksLabs\CsvReaderBundle\Importer;

use NetworksLabs\CsvReaderBundle\File\FileReader;
use NetworksLabs\CsvReaderBundle\Importer\Exception\ImporterException;

class CsvImporter implements ImporterInterface {
   
    private $file;
    private $elements = array();

    public function __construct(FileReader $file){
        $this->file = $file;    
    }

    public function import($config){
        foreach ($this->file->getFile() as $row) {
            if(count($row) > 0 && !$row[0]==''):
                $this->elements[] = $row ;
            endif;
        }
        if($this->checkIntegrity($config)):
            return $this->elements;
        endif;
    }


    public function checkIntegrity($config){
        $numb_rows = count($config['inputs']);
        foreach($this->elements as $row):
            if(count($row)!=$numb_rows):
                throw new ImporterException('There are more columns in the file than in the configuration');
            endif;
        endforeach;

        return true;
         
    }
}
