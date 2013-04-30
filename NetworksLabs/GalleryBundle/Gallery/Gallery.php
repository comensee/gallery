<?php

namespace NetworksLabs\GalleryBundle\Gallery;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

use NetworksLabs\GalleryBundle\Utils\Images;

class Gallery implements GalleryInterface {

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
    
    public function getUploadDir($upload_type){
        if($upload_type=='pdf'){
            return $this->config['upload_types'][$upload_type]['upload_dir'];
        }elseif($upload_type=='free'){
            return $this->config['upload_types'][$upload_type]['upload_dir'];
        }else{

        if(!is_dir($this->config['default_upload_dir'])):
            mkdir($this->config['default_upload_dir']);
        endif;
        if(!is_dir($this->config['default_upload_dir'].'/'.$this->config['upload_types'][$upload_type]['upload_dir'])):
            mkdir($this->config['default_upload_dir'].'/'.$this->config['upload_types'][$upload_type]['upload_dir']);
        endif;

            return $this->config['default_upload_dir'].'/'.$this->config['upload_types'][$upload_type]['upload_dir'];
        }
    }   

    public function readUploadDir($upload_type = null){
        if($upload_type):
            return $this->get_in_dir($this->config['upload_types'][$upload_type]['upload_dir']);
        else:
            return $this->get_in_dir($this->config['default_upload_dir']);
        endif;
    }

    public function deletePic($image){}
    
    public function get_config_for($upload_type = null){
        return ($upload_type)?$this->config['upload_types'][$upload_type]:$this->config;
    }
   
    public function resize($image, $new_height){
        $new_image = new Images($image);
        $new_image->resize($image,$new_height,$new_height,0);
    }

    protected function get_in_dir($dir){
        $finder = new Finder();
        $finder->files()->in($dir);
        return $finder;
    }

    public function getWebDir(){
        return $this->config['default_web_dir'];    
    }
 
}
