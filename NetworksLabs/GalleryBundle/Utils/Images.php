<?php 
namespace NetworksLabs\GalleryBundle\Utils;

/**
 * @author : Yaug - Manuel Esteban
 * @version :  0.1
 * @date : 18.04.07
 * @comment : First Version of this class that allows you to format image size.
**/

class Images{
	//caracatéristiques de l'image source
	private $source;
	private $to;
	private $width;
	private $height;
	private $type;
	private $extensions;
	public $widthoriginal;
	public $heightoriginal;
	
	
	//caractéristique de l'image destination.
	private $new_width;
	private $new_height;

        //Constructeur
	public function __construct($_source,$_extensions=array('jpg'=>2,'jpeg'=>2,'gif'=>1,'png'=>3)){
		$this->source=(string)$_source;
		$this->extensions=$_extensions;

		list($w, $h, $t, $a) = getimagesize($_source);
		$this->width=$w;
		$this->height=$h;
		$this->type=$t;
		$this->widthoriginal=$w;
		$this->heightoriginal=$h;
	}

        //Fonction de vérification de l'extension.
	private function verif_extension(){
		return(in_array($this->type,$this->extensions));
	}
	public function ImagesType(){
            return $this->extensions;
        }
	/**Fonction de redimensionnement,
         *$_to sera le nom de la nouvelle image,
         * $_new_width et $_new_height sa nouvelle taille.
         * Si $_samesize vaut 1, la photo gardera ses proportions.
         **/
	public function resize($_to,$_new_width,$_new_height,$_samesize){
		$this->to=(string)$_to;
		$this->new_width=(int)$_new_width;
		$this->new_height=(int)$_new_height;

		//On vérifie l'extention
		if($this->verif_extension()){
                        //On vérifie le rapport
			$ratio=$this->get_ratio();
			if($ratio>1){//La nouvelle image est plus petite que l'image source, on la redimenssione.
				$new_w=($_samesize)?$this->new_width:$this->width/$ratio;
				$new_h=($_samesize)?$this->new_height:$this->height/$ratio;
				$thumb=imagecreatetruecolor($new_w,$new_h);//on prépare une image vide aux bonnes dimensions.
				switch ($this->type) // On teste l'extension du fichier pour utiliser la fonction adéquate
				{
					case 1: $source = imagecreatefromgif($this->source); break;
					case 2: $source = imagecreatefromjpeg($this->source); break;
					case 3: $source = imagecreatefrompng($this->source); break;
				} 
				//On copie notre image d'origine dans la nouvelle image.
				imagecopyresampled($thumb,$source,0,0,0,0,$new_w,$new_h,$this->width,$this->height);
                                imagejpeg($thumb,$_to, 100);//On sauve l'image
				//On détruit les ressources inutilisées.
				imagedestroy($thumb);
				imagedestroy($source);
                                return new Images($_to);
			}else{ copy($this->source,$this->to);return new Images($_to);} //L'image est plus petite que l'image source, on se contente de la copier.
		}else print_r($this->type);
	}

        //Fonction qui calcul le rapport entre les cotés
	public function get_ratio(){
		return max($this->width/$this->new_width,$this->height/$this->new_height);
	}
        public function getSource(){
            return $this->source;
        }
        public function getName(){
            return array_pop(explode('/',$this->source));
        }
        public function getMimeType(){
            return \exif_imagetype($this->source);
        }
}
/*UTILISATION
$Img=new Image("test/66.JPG");
$Img->resize("test/exemple.jpg",100,100,0);//Si vous vouler conserver les proportions, le dernier parametre doit etre a 1.
*/

