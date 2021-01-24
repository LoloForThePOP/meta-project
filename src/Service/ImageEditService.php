<?php

namespace App\Service;

use \Gumlet\ImageResize;

class ImageEditService {

    /**
    * Allow to modify an image according to its category (for example : project logo; teammate picture; presentation image slide; ...)
    */

    public function edit ($imageCategory, $imageName){

        switch ($imageCategory) {

            case 'presentation_slide':

                $imagePath='images/projects/slides_images/';

                $image = new ImageResize($imagePath.$imageName);
                $image->resizeToBestFit(700, 700);
                $image->save($imagePath.$imageName);

                break;
            
            default:
                # code...
                break;
        }

    }




}
