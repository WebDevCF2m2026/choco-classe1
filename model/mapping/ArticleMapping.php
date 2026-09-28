<?php
// path: model/mapping/ArticleMapping.php

// typage strict
declare(strict_types=1);

namespace model\mapping;

use Exception;
use model\abstract\AbstractMapping;


class ArticleMapping extends AbstractMapping
{
    // propriétés (nom des champs de la table article)
    protected ?int $article_id = null;
    protected ?string $article_title = null;
    // constructeur et hydrate hérités par la classe parent

    // création des getters / setters

    // getter
    public function getArticleId(): ?int
    {
        return $this->article_id;
    }
    // setter
    public function setArticleId(int $id):void
    {
        // si le chiffre est trop petit (négatif)
        if($id<=0) throw new Exception("L'id ne peut pas être négatif ou valoir 0",333);
        // sinon on met à jour la propriété
        $this->article_id= $id;
    }

    public function getArticleTitle(): ?string
    {
        return $this->article_title;
    }

    public function setArticleTitle(string $title): void
    {
        // protection stricte (titre)
        $title = trim(htmlentities(strip_tags($title)));
        // longueur de chaîne
        $nbTitle = strlen($title);
        // test de longueur
        if($nbTitle <=2 || $nbTitle >180)
            // erreur
            throw new Exception("Le titre doit avoir entre 3 et 180 caractères");
        // ok, mise à jour du titre    
        $this->article_title = $title;
    }

}