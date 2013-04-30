<?php

namespace NetworksLabs\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use NetworksLabs\GalleryBundle\Form\Data\UploadPicType;

class DefaultController extends Controller
{
    
    public function indexAction()
    {

        $request = $this->get('request');
        $files = $this->get('networks_labs_gallery')->readUploadDir('free');
        $uploadform = $this->get('form.factory')->create(new UploadPicType($this));
        if($request->getMethod()=='POST'):

        $uploadform->bindRequest($request);
            if($uploadform->isValid()):
                $request_form = $request->request->get('UPLOAD');
                $file = $this->get('request')->files->get('UPLOAD');
                $new_name = md5(uniqid(rand())).$file['file']->getClientOriginalName();
                $file['file']->move($this->get('networks_labs_gallery')->getUploadDir('free'),$new_name);
            endif;
        endif;

        return $this->render('NetworksLabsGalleryBundle:Default:index.html.twig', 
            array('form'=>$uploadform->createView(), 'message'=>'', 'files' => $files, 'host'=>$_SERVER['HTTP_HOST']));
    }

    public function pdfAction()
    {
        $files = $this->get('networks_labs_gallery')->readUploadDir('pdf');
        
        $request = $this->get('request');
        $uploadform = $this->get('form.factory')->create(new UploadPicType($this));
        if($request->getMethod()=='POST'):
            $uploadform->bindRequest($request);
            if($uploadform->isValid()):
                $request_form = $request->request->get('UPLOAD');
                $file = $this->get('request')->files->get('UPLOAD');
                $new_name = md5(uniqid(rand())).$file['file']->getClientOriginalName();
                $file['file']->move($this->get('networks_labs_gallery')->getUploadDir('pdf'),$new_name);
            endif;
        endif;
        return $this->render('NetworksLabsGalleryBundle:Default:pdf.html.twig', 
            array('form'=>$uploadform->createView(), 'message'=>'', 'files' => $files, 'host'=>$_SERVER['HTTP_HOST']));
    }

    public function uploadAction()
    {
        $request = $this->get('request');
        $uploadform = $this->get('form.factory')->create(new UploadPicType($this));
        if($request->getMethod()=='POST'):

        $uploadform->bindRequest($request);
            if($uploadform->isValid()):
                $request_form = $request->request->get('UPLOAD');
                $file = $this->get('request')->files->get('UPLOAD');
                $new_name = md5(uniqid(rand())).$file['file']->getClientOriginalName();
                $file['file']->move($this->get('networks_labs_gallery')->getUploadDir('free'),$new_name);
            endif;
        endif;
        //return new Response('<h1>ok</h1>');
        return $this->redirect($this->generateUrl('NetworksLabsGalleryBundle_homepage'));
    }

    public function uploadpdfAction()
    {
        $request = $this->get('request');
        $uploadform = $this->get('form.factory')->create(new UploadPicType($this));
        $uploadform->bindRequest($request);
        if($request->getMethod()=='POST'):
            if($uploadform->isValid()):
                $request_form = $request->request->get('UPLOAD');
                $file = $this->get('request')->files->get('UPLOAD');
                $new_name = md5(uniqid(rand())).$file['file']->getClientOriginalName();
                $file['file']->move($this->get('networks_labs_gallery')->getUploadDir('pdf'),$new_name);
            else:
            endif;
        endif;
        return $this->redirect($this->generateUrl('NetworksLabsGalleryBundle_pdf'));

    }

    public function deleteAction($picture)
    {
        $files = $this->get('networks_labs_gallery')->readUploadDir();
        foreach($files as $file):
            if(preg_match('#'.$picture.'#', $file)):
                unlink($file);
            endif;
        endforeach;
        return $this->redirect($this->generateUrl('NetworksLabsGalleryBundle_homepage'));
    }

    public function deletepdfAction($picture)
    {
        $files = $this->get('networks_labs_gallery')->readUploadDir();
        foreach($files as $file):
            if(preg_match('#'.$picture.'#', $file)):
                unlink($file);
            endif;
        endforeach;
        return $this->redirect($this->generateUrl('NetworksLabsGalleryBundle_pdf'));
    }

    public function nicedituploadAction(){
        
    }
}
