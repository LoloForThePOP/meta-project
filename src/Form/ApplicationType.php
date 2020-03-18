<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

      /**
     * Permet de configurer un champ de formulaire
     *
     * @param string $label
     * @param string $placeHolder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($label, $placeHolder,$options=[]){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeHolder' => $placeHolder,
            ]
            ], $options);


    }

}

?>