<?php

namespace App\Service;

use App\Entity\Photo;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageService
{

private $params;

public function __construct(ParameterBagInterface $params)
{
    $this->params = $params;    
}

  public function addPhoto($image, $parameter, $post)
  {

    if (file_exists($image)) {
        $size = filesize($image);
        if (strlen($size) <= 4096000) {
            $alt = $image->getClientOriginalName();
            $url = md5(uniqid($alt)) . '.' . $image->guessExtension();
            $image->move($this->params->get($parameter), $url);
            $img = new Photo();
            $img->setName($alt);
            $img->setUrl($url);
            $post->addPhoto($img);
        } else {
            return false;
        }
    }
    return $post;
  }
}
