<?php
// path: model/trait/SlugifyTrait.php

namespace model\trait;

use Exception;

trait SlugifyTrait
{
    public function slugify(string $text, bool $prefix=true, bool $suffix=false,string $separator = '-'): string
    {
        // Replace non-letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // Trim
        $text = trim($text, $separator);

        // Remove duplicate -
        $text = preg_replace('~-+~', $separator, $text);

        // Lowercase
        $text = strtolower($text);

        // suffix
        if($prefix===true) {
            $text = bin2hex(random_bytes(2)) . "-" . $text;
        }

        // suffix
        if($suffix===true) {
            $text = $text . "-" . bin2hex(random_bytes(2));
        }

        if (empty($text)) {
            throw new Exception('Échec du slugify : le texte ne peut pas être vide après transformation.');
        }

        return $text;
    }
}