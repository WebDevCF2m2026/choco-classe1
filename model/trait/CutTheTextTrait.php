<?php
// path: model/trait/CutTheTextTrait.php

namespace model\trait;

trait CutTheTextTrait
{
    public function cutTheText(string $text, int $length): string
    {
        // si le texte est plus court que la longueur max
        if(strlen($text)<=$length){
            return $text;
        }
        // on coupe à la longueur maximum
        $cutText = substr($text,0,$length);
        // on cherche le dernier espace pour ne pas couper un mot
        $lastSpace = strrpos($cutText,' ');
        // s'il y a un espace
        if($lastSpace!==false){
            // on coupe au dernier espace
            $cutText = substr($cutText,0,$lastSpace);
        }
        // on ajoute des points de suspension
        $cutText .= '...';
        return $cutText;
    }
}