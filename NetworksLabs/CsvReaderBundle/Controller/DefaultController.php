<?php

namespace NetworksLabs\CsvReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NetworksLabs\CsvReaderBundle\Form\Data\UploadType;
use Symfony\Component\HttpFoundation\Response;



class DefaultController extends Controller
{
    
    public function indexAction()
    {

        $imports = $this->get('session')->get('imports');
        $uploadform = $this->get('form.factory')->create(new UploadType($this));
        return $this->render('NetworksLabsCsvReaderBundle:Default:index.html.twig', array(
                                                                                    'form'=>$uploadform->createView(),
                                                                                    'imports'=>$imports));
    }

    public function verifAction()
    {
        $request = $this->get('request');
        $request_form = $request->request->get('UPLOAD_DOC');
        $file = $this->get('request')->files->get('UPLOAD_DOC');
        $md5_key = md5(uniqid(rand()));
        $new_name = $md5_key.$file['file']->getClientOriginalName($request_form['upload_type']);
        $file['file']->move($this->get('networks_labs_csv_reader')->getUploadDir($request_form['upload_type']),$new_name);
        $import = $this->get('networks_labs_csv_reader')->import($new_name, $request_form['upload_type']);
        $session = $this->get('session');

        $imports = $session->get('imports');

        if(!$imports):
            $imports = array();
        endif;
        $imports[$md5_key] = $import;
        $session->set('imports', $imports);
        $upload_config = $this->get('networks_labs_csv_reader')->get_config($request_form['upload_type']);
        $header_inputs = array_keys($upload_config['inputs']);

        $uploadform = $this->get('form.factory')->create(new UploadType($this));
        return $this->render('NetworksLabsCsvReaderBundle:Default:verif.html.twig', 
                                array(
                                    'form'=>$uploadform->createView(), 
                                    'errors'=>$import[2], 
                                    'headers'=>$import[0],
                                    'rows'=> $import[1],
                                    'key'=>$md5_key));
    }

    public function importAction($import_key)
    {
        $imports = $this->get('session')->get('imports');
        $import = $imports[$import_key];

        return $this->render('NetworksLabsCsvReaderBundle:Default:import.html.twig', 
                                array(
                                    'errors'=>$import[2], 
                                    'headers'=>$import[0],
                                    'rows'=> $import[1],
                                    'key'=>$import_key) );
    }


    public function deleteAction($import_key)
    {
        $imports = $this->get('session')->get('imports');
        unset($imports[$import_key]);
        $this->get('session')->set('imports', $imports);
        return $this->redirect($this->generateurl('NetworksLabsCsvReaderBundle_homepage')); 
    }

    public function ajaxAction($import_key)
    {
        $imports = $this->get('session')->get('imports');    
        $import = $imports[$import_key];
        $lines = array();

        foreach($import[1] as $line):
            $row = array();
            foreach($line->getInputs() as $cell):
                $row[] = $cell->getValue();
            endforeach;
            if($line->hasErrors()):
                $row[] = '<div class="import-failed"></div>';
            else:
                $row[] = " <div class='import-ok'></div>";
            endif;
            $lines[] = $row;
        endforeach;

        $response = new Response(json_encode(array('aaData'=> $lines)));
        $response->headers->set('Content-Type', 'application/json'); 

        return $response;
    }
}
