<?php

namespace NetworksLabs\CsvReaderBundle\Importer;

use NetworksLabs\CsvReaderBundle\File\FileReader;
use NetworksLabs\CsvReaderBundle\Importer\Exception\ImporterException;

class ExcelImporter implements ImporterInterface {
   
    private $file;
    private $elements = array();

    public function __construct(FileReader $file){
        $this->file = $file;    
    }
    
    public function import($config){
        $objReader = new \PHPExcel_Reader_Excel2007();
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($this->file->getFile()->getRealPath());

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
        foreach($rowIterator as $row_):
            $cellIterator = $row_->getCellIterator();
            $row = array();
            foreach($cellIterator as $cell):
                $row[] = $cell->getValue();
            endforeach;
                $this->elements[] = $row;
        endforeach;
        return $this->elements;
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
