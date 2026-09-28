<?php

declare(strict_types=1);

// appel via le namespace et l'autoload de ArticleMapping
use model\mapping\ArticleMapping;

// article hérite du __construct de la classe abstraite
$article = new ArticleMapping(
    [
        'article_id'=>5,
        'article_title'=> "Yes, ça fonctionne",
        'le_titre'=>'Bonjour les amis',
        'texte_a_garder'=> 'vous allez bien ?',
    ]
);
?>
<h3><?= $article->getArticleId() ?>) <?= $article->getArticleTitle() ?></h3>
<?php

var_dump($article);
