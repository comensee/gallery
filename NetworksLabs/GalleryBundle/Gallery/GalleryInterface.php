<?php

namespace NetworksLabs\GalleryBundle\Gallery;

interface GalleryInterface {
   
    /**
     * Fonction permettant de récupérer le dossier configuré pour le type d'upload
     **/
    public function getUploadDir($upload_type);
    
    /**
     * Fonction listant les fichiers contenu dans un dossier
     **/
    public function readUploadDir();

    /**
     * Fonction supprimant le fichier indiqué
     **/
    public function deletePic($image);

}
