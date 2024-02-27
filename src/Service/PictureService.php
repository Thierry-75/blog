<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{

    private $params;


    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        $picture_infos = getimagesize($picture);

        if ($picture_infos === false) {
            throw new Exception('Format d\'image incorrect');
        }

        switch ($picture_infos['mime']) {
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
    
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception('Format d\'image incorrect');
        }

        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        switch ($imageWidth <=> $imageHeight) {
            case -1:  //portrait
                $squarreSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squarreSize) / 2;
                break;
            case 0:  //carré
                $squarreSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;;
                break;
            case 1:  //paysage
                $squarreSize = $imageHeight;
                $src_x = ($imageWidth - $squarreSize) / 2;
                $src_y = 0;
                break;
        }

        //new image
        $resize_picture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resize_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squarreSize, $squarreSize);

        $path = $this->params->get('photo_directory') . $folder;

        //dossier de destination
        if (!file_exists($path . '/mini/')) {
            mkdir($path . '/mini/', 0775, true);
        }
        //stockage
        imagewebp($resize_picture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
        $picture->move($path . '/', $fichier);

        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if ($fichier !== 'default.webp') {
            $success = false;
            $path = $this->params->get('photo_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;
            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;
            if (file_exists($original)) {
                unlink($mini);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}
