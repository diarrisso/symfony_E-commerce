<?php

namespace App\Service;

use PHPUnit\Util\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    /**
     * @var
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @throws \Exception
     */
    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // on donne un nouveau nom a l image
        $fichier = md5(uniqid(rand(), true ) ). '.webp';
        // on recupere la largeur et hauteur
        $picture_info = getimagesize($picture);
        if ( $picture_info === false)
        {
            throw new \Exception('Format d\'image');
        }
        switch (  $picture_info['mime'])
        {
            case 'image/png':
                $src_image = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $src_image = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $src_image = imagecreatefromwebp($picture);
                break;
            default:
                throw  new Exception('Format d\'image incorrecte');
        }

        // on recadre image
        // on recupere les dimenssions
        $imageWidth = $picture_info[0];
        $imageHeight = $picture_info[1];
        // on verifie orientation de image
        switch (  $imageWidth <=> $imageHeight ){
            case -1: // Portrait
                $squareSize =  $imageWidth;
                $src_x = 0;
                $src_y = ( $imageHeight - $squareSize) / 2;
                break;
            case 0: // carre
                $squareSize =  $imageWidth;
                $src_x = 0;
                $src_y = ( $imageHeight - $squareSize) / 2;
                break;
            case 1: // paysage
                $squareSize =  $imageHeight;
                $src_x = 0;
                $src_y = ( $imageHeight - $squareSize) / 2;
                break;
        }
        // on cree une nouvelle image "vierge"
        $dst_image =  imagecreatetruecolor($width, $height);
        imagecopyresampled($dst_image, $src_image, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);
         $path = $this->params->get('images_directory'). $folder;

         //
        if (!file_exists($path .'/mini')) {
            mkdir($path . '/mini/', 0755, true);
        }
        imagewebp($dst_image, $path . '/mini/' . $width .'x' . $height . '-' .$fichier );

        $picture->move($path . '/' , $fichier);
        
        return $fichier;


    }

    public function delete( string $ficher, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if ($ficher !== 'dafault.webp')
        {
            $success = false;
            $path = $this->params->get('images_directory'). $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $ficher;

            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $ficher;
            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }

            return $success;

        }

        return true;
        
    }
}