<?php
namespace NetworksLabs\GalleryBundle\Twig\Extension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Certivea\EnewsBundle\Utils\String;

class GalleryExtension extends \Twig_Extension
{
    protected $kernel;
    /**
    *
    * @var \Twig_Environment
    */
    protected $environment;
    
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getRequest()
    {
        if ($this->kernel->getContainer()->has('request')) {
            $request = $this->kernel->getContainer()->get('request');
        } else {
            $request = Request::createFromGlobals();
        }
        return $request;
    }
    
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'image_name' => new \Twig_Function_Method($this, 'image_name'),
            'dump' => new \Twig_Function_Method($this, 'dump'),
            'get_picto_for_img' => new \Twig_Function_Method($this, 'get_picto_for_img'),
            'String' => new \Twig_Function_Method($this, 'string'),

        );
    }

    public function dump($var)
    {
        print_r($var);
    }

    public function getName()
    {
        return 'app_extension';
    }

    public function string()
    {
        $string = new String();
        return $string;
    }

    public function image_name($image_path){
       $tab = explode('/', $image_path);

       return end($tab);
    }

    public function get_picto_for_img($img)
    {
        $ext = array(
                        'docx' => 'word',
                        'doc' => 'word',
                        'xls' => 'excel',
                        'xlsx' => 'excel',
                        'ppt' => 'powerpoint',
                        'pptx' => 'powerpoint',
                        'pdf' => 'pdf',
                    );
        $tab = explode('.', $img);
        return (in_array(end($tab),array_keys($ext)))?'bundles/certiveaenews/img/'.$ext[end($tab)].'.png':'bundles/certiveaenews/img/others.png';
    }

}
