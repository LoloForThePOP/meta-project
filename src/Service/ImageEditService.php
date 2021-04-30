<?php

namespace App\Service;

use \Gumlet\ImageResize;

class ImageEditService {

    /**
    * Allow to modify an image according to its category (for example : project logo; teammate picture; presentation image slide; ...)
    */

    public function edit ($imageCategory, $imageName){

        if ($imageName==null) {

            return false;
        }

        /* $ext = strtolower(substr($imageName, strrpos($imageName, '.') + 1));

        # tiff images not resize by gumlet plugin, so currently they are not resized :-(

        if ($ext == 'tif' || $ext == 'tiff') {

            return false;
            
        } */

        switch ($imageCategory) {

            case 'presentation_slide':

                $imagePath='images/projects/slides_images/';

                $image = new ImageResize($imagePath.$imageName);
                $image->quality_jpg = 100;
                $image->resizeToBestFit(700, 700);
                $image->save($imagePath.$imageName);

                break;

            case 'presentation_logo':

                $imagePath='images/projects/logos/';

                $image = new ImageResize($imagePath.$imageName);
                $image->quality_jpg = 100;
                $image->resizeToBestFit(200, 200);
                $image->save($imagePath.$imageName);

                break;

            case 'presentation_teammate':

                $imagePath='images/persorgs/';

                $image = new ImageResize($imagePath.$imageName);
                $image->quality_jpg = 100;
                $image->resizeToBestFit(200, 200);
                $image->save($imagePath.$imageName);

                break;

            case 'presentation_external_contributor':

                $imagePath='images/persorgs/';

                $image = new ImageResize($imagePath.$imageName);
                $image->quality_jpg = 100;
                $image->resizeToBestFit(200, 200);
                $image->save($imagePath.$imageName);

                break;

            case 'presentation_project_owner':

                $imagePath='images/persorgs/';

                $image = new ImageResize($imagePath.$imageName);
                $image->quality_jpg = 100;
                $image->resizeToBestFit(200, 200);
                $image->save($imagePath.$imageName);

                break;
            
            default:
                # code...
                break;
        }

    }




}
