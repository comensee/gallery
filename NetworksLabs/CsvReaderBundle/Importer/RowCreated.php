<?php

namespace NetworksLabs\CsvReaderBundle\Importer;

use NetWorksLabs\CsvReaderBundle\Importer\InputCreated;

class RowCreated{
 
    private $has_error = false;
    private $errors = array();
    private $inputs = array();

    public function __get($property){
        foreach($this->inputs as $input):
            if($input->getLabel() == $property):
                return $input;
            endif;
        endforeach;
    }

    public function getInputs(){
        return $this->inputs;
    }

    public function add(InputCreated $input){
       $this->inputs[] = $input; 
    }

    public function add_error($message){
        $this->errors[] = $message;
        $this->has_error = true;
    
    }

    public function hasErrors(){
        return $this->has_error;    
    }

    public function getErrors(){
        return $this->errors;    
    }
    
}
