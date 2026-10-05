<?php
// path: model/manager/ArticleManager.php
// typage strict
declare(strict_types=1);

namespace model\manager;

use model\interface\ManagerInterface;
use model\MyPDO;
use model\mapping\ArticleMapping;
use model\mapping\UserMapping;
use Exception;

class ArticleManager implements ManagerInterface
{
    protected MyPDO $connect;

    public function __construct(MyPDO $connect)
    {
        $this->connect = $connect;
    }

    // récupération de tous les articles pour la page d'accueil
    public function getAllArticles(): array
    {
        $sql = "SELECT 
            a.article_id, a.article_title, a.article_slug, LEFT(a.article_text,300) as article_text, a.article_create_at ,
            u.user_login, u.user_full_name
            FROM article a
            INNER JOIN user u ON a.user_user_id = u.user_id
            WHERE a.article_status='publié' 
                         ORDER BY a.article_create_at DESC";
        $stmt = $this->connect->prepare($sql);
        try{
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des articles : " . $e->getMessage());
        }

        // si on a pas d'article, on retourne un tableau vide
        if($stmt->rowCount() === 0) {
            return [];
        }
        // récupération des articles sous forme de tableau d'objets ArticleMapping
        $articles = $stmt->fetchAll();
        // fermeture de la requête
        $stmt->closeCursor();
        // création d'un tableau d'objets ArticleMapping
        $result = [];
        // tant qu'on a des articles, on les transforme en objets ArticleMapping
        foreach ($articles as $article) {
            $art = new ArticleMapping($article);
            $user = new UserMapping($article);
            $art->setUser($user);
            $result[] = $art;
        }
    return $result;
    }

    // récupération d'un article publié complet grâce à son slug
    // retourne null si l'article n'existe pas ou n'est pas publié
    public function getArticleBySlug(string $slug): ?ArticleMapping
    {
        $sql = "SELECT
            a.article_id, a.article_title, a.article_slug, a.article_text, a.article_create_at,
            a.article_validate_at, a.article_status, a.user_user_id,
            u.user_id, u.user_login, u.user_full_name
            FROM article a
            INNER JOIN user u ON a.user_user_id = u.user_id
            WHERE a.article_slug = :slug AND a.article_status='publié'";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        try{
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération de l'article : " . $e->getMessage());
        }

        // si on n'a pas trouvé l'article, on retourne null
        if($stmt->rowCount() === 0) {
            return null;
        }
        // récupération de l'article (le slug est unique, une seule ligne)
        $article = $stmt->fetch();
        // fermeture de la requête
        $stmt->closeCursor();
        // création de l'objet ArticleMapping avec son auteur
        $art = new ArticleMapping($article);
        $user = new UserMapping($article);
        $art->setUser($user);
        return $art;
    }
}