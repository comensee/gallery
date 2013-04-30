<?php

namespace NetworksLabs\CsvReaderBundle\Importer;


interface ImporterInterface {
    
    public function import($config);   

    public function checkIntegrity($config);

}
