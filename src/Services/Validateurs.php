<?php

namespace App\Services;

class Validateurs
{
    public function verifMdp(string $mdp):bool{
        // au moins une minuscule, au moins une majuscule, au moins un chiffre, au moins un caractère spécial (?;!:=).
        $contMaj = 0;
        $contMin = 0;
        $contChiffre = 0;
        $contCaracSpe = 0;
        $caractSpe=['?','!',';',':','='];

       for ($i=0; $i < strlen($mdp); $i++) {
           if (ctype_digit($mdp[$i])) {
               $contMaj += 1;
           }
           if (ctype_digit($mdp[$i])) {
               $contChiffre += 1;
           }
           if(ctype_digit($mdp[$i])){
               $contMin += 1;
           }
           if(in_array($mdp[$i], $caractSpe)){
               $contCaracSpe += 1;
           }
       }
       if ($contMaj >=1 and $contMin >=1 and $contChiffre >=1 and $contCaracSpe >=1){
           return true;
       }else{
           return false;
       }
    }
}