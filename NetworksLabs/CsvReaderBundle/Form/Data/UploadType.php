<?php

namespace NetworksLabs\CsvReaderBundle\Form\Data;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;


class UploadType extends AbstractType
{
    private $controller;
    private $upload_types = array();

    public function __construct($controller){
        $this->controller = $controller;
        $gallery_config = $this->controller->get('networks_labs_csv_reader')->get_config();
        $types = array_keys($gallery_config['uploads']);
        foreach($types as $type):
            $this->upload_types[] = array($type => $type); 
        endforeach;
    }


    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                                'label' => 'Fichier'));
            /*->add('upload_type', 'choice', array(
                                'choices'=> $this->upload_types,
                                'label' => 'Type fichier',
                                'empty_value' => '',
                                'empty_data'  => null,
                                'expanded'    => false,
                                'multiple'    => false));*/
    }

    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Collection(array(
            'file' => new NotBlank(),
        ));

        return array('validation_constraint' => $collectionConstraint);
    }

    public function getName()
    {
        return 'UPLOAD_DOC';    
    }
}
