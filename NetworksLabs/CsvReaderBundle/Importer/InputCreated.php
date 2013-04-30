<?php

namespace NetworksLabs\CsvReaderBundle\Importer;

use NetworksLabs\CsvReaderBundle\Validator\Validator;

class UnRecognizedTypeException extends \Exception
{
}

class InputCreated {
   
    private $input_types = array('email', 'string', 'int');

    private $type;
    private $val;
    private $label;

    public function __construct($label, $value, $type){
        $this->type = $type;
        $this->label = $label;
        $this->val = $value;
        $this->validate();
    }

    public function getType(){
        return $this->type;
    }  

    public function getLabel(){
        return $this->label;    
    }

    public function getValue(){
        return $this->val;    
    }

    public function validate(){
        if(!in_array($this->type, $this->input_types)):
            throw new UnRecognizedTypeException('Ce format est inconnu. Veuillez l\'ajouter à la liste des formats existants');
        endif;

        switch($this->type){
            case 'email':
                Validator::isEmail($this);
                break;
            case 'string':
                Validator::isString($this);
                break;
            case 'int':
                Validator::isInt($this);
                break;
            defaut:
                Validator::isNotEmpty($this);
                break;
        }
        return true;

                
    }


    
}
