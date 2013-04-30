<?php

namespace NetworksLabs\CsvReaderBundle\Validator;

use NetworksLabs\CsvReaderBundle\Validator\ValidatorInterface;
use NetworksLabs\CsvReaderBundle\Importer\InputCreated;


class InvalidFormatException extends \Exception
{}

class Validator implements ValidatorInterface{
    
    static public function isNotEmpty(InputCreated $input){
        if(!$input->getValue() || $input->getValue()==""):
            return false;
        endif;
        return true;
    }


    static public function isString(InputCreated $input){
        self::isNotEmpty($input);
        if(false===is_string($input->getValue())):
            throw new InvalidFormatException('"'.$input->getValue().'" is not a string');
        endif;
        return true;
    }

    static public function isInt(InputCreated $input){
        self::isNotEmpty($input);
        if(false===is_int($input->getValue())):
            throw new InvalidFormatException('"'.$input->getValue().'" is not an int');
        endif;
        return true;
    }

    static public function isEmail(InputCreated $input){
        self::isNotEmpty($input);
        if(false===filter_var(base64_decode($input->getValue()), FILTER_VALIDATE_EMAIL)):
            throw new InvalidFormatException('"'.$input->getValue().'" is not a valid email');
        endif;
        return true;
    }

    static public function isUnique(InputCreated $input, $row_list){
        self::isNotEmpty($input);
        //foreach($row_list as $row):
            //foreach($row->getInputs() as $input_):
                //if(($input_->getLabel()=="email") &&  ($input->getValue() == $input_->getValue())):
                    //throw new InvalidFormatException('"'.$input->getValue().'" is already saved');
                //endif;
            //endforeach;
        //endforeach;
        if(in_array($input->getValue(), $row_list)):
            throw new InvalidFormatException('"'.$input->getValue().'" is already saved');
        endif;
       
        return true;
    }

}
