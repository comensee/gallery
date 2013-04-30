<?php

namespace NetworksLabs\CsvReaderBundle\File;

use NetworksLabs\CsvReaderBundle\File\FileReaderInterface;
use Symfony\Component\HttpFoundation\File\File; 

class FileReader implements FileReaderInterface {
    
    private $path;

    public function __construct($path){
        $this->path = new File($path);    
    }   

    public function read(){
        
    }

    public function getFile(){
        $spl_file = $this->path->openFile();
        $spl_file->setFlags(\SplFileObject::READ_CSV);
        $spl_file->setCsvControl(';');
        return $spl_file;
    }

    public function checkExtension(){
        return $this->path->getExtension();    
    }
}
