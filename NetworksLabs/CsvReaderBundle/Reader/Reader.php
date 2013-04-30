<?php

namespace NetworksLabs\CsvReaderBundle\Reader;

use NetworksLabs\CsvReaderBundle\Importer\CsvImporter;
use NetworksLabs\CsvReaderBundle\Importer\ExcelImporter;
use NetworksLabs\CsvReaderBundle\Importer\RowCreated;
use NetworksLabs\CsvReaderBundle\File\FileReader;
use NetworksLabs\CsvReaderBundle\Importer\InputCreated;


use NetworksLabs\CsvReaderBundle\Validator\Validator;

use NetworksLabs\CsvReaderBundle\Importer\UnRecognizedTypeException;
use NetworksLabs\CsvReaderBundle\Validator\InvalidFormatException;



class Reader implements ReaderInterface{

    private $config;

    public function __construct($config){
        $this->config = $config;
    }
    
    public function get_config($upload_type = null){
        return ($upload_type)?$this->config['uploads'][$upload_type]:$this->config;
    }


    public function getUploadDir($upload_type){
        if(!is_dir($this->config['default_upload_dir'])):
            mkdir($this->config['default_upload_dir']);
        endif;
        return $this->config['default_upload_dir'];
    }   

    public function import($new_name, $upload_type)
    {
        $results = array();
        $errors = array();
        $unique_data = array();
        $upload_config = $this->get_config($upload_type);
        $file = new FileReader($this->getUploadDir($upload_type).'/'.$new_name);
      /*  if($file->getFile()->getExtension()=="csv"):
            $import = new CsvImporter($file);
        else:*/
            $import = new ExcelImporter($file);
       /* endif;*/
        $cells = $import->import($upload_config);
        $headers = array_keys($upload_config['inputs']);
        foreach($cells as $cell):
            $row = new RowCreated();
            for($i = 0; $i < count($upload_config['inputs']); $i++){
                $label = $headers[$i];
                try{
                    $input = new InputCreated( $label, base64_encode($cell[$i]), $upload_config['inputs'][$label]);
                    if($label=="email"){
                        Validator::isUnique($input, $unique_data);
                        $unique_data[] = $input->getValue();
                    }
                } catch (UnRecognizedTypeException $e){
                    $errors[] = $e->getMessage();
                } catch (InvalidFormatException $e){
                    $errors[] = $e->getMessage();
                    $row->add_error($e->getMessage());
                }
                $row->add($input); 
            }
        $results[] = $row;

        endforeach;
        return array($headers,$results, $errors);
    }

    public function save($import_list, $file_name){
            $text = "email;nom;prenom\n";
            $list_imported = array();
            foreach($import_list as $key=>$line):
                try{
                      foreach($line->getInputs() as $input):
                            if($input->getLabel()=="email" && Validator::isUnique($input, $list_imported)):
                            $text .= base64_decode($line->email->getValue()).";".base64_decode($line->nom->getValue()).";".base64_decode($line->prenom->getValue())."\n";
                            $list_imported[] = $line;
                        endif;
                      endforeach;
                    }
                    catch (InvalidFormatException $e){
                    }
            endforeach;

            $csv_file = fopen(__DIR__.'/../../../../web/documents/'.$file_name, 'w');
            fwrite($csv_file, $text);
            fclose($csv_file);
    }


}
