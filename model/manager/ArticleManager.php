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
// appel du trait pour créer des slugs uniques
use model\trait\SlugifyTrait;

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

    // ---------- Administration (CRUD) ----------

    // récupération de tous les articles, quel que soit leur statut
    public function getAllArticlesAdmin(): array
    {
        $sql = "SELECT
            a.article_id, a.article_title, a.article_slug, a.article_create_at,
            a.article_validate_at, a.article_status, a.user_user_id,
            u.user_id, u.user_login, u.user_full_name
            FROM article a
            INNER JOIN user u ON a.user_user_id = u.user_id
            ORDER BY a.article_create_at DESC";
        $stmt = $this->connect->prepare($sql);
        try{
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des articles : " . $e->getMessage());
        }

        $articles = $stmt->fetchAll();
        $stmt->closeCursor();

        $result = [];
        foreach ($articles as $article) {
            $art = new ArticleMapping($article);
            $art->setUser(new UserMapping($article));
            $result[] = $art;
        }
        return $result;
    }

    // récupération d'un article par son id, quel que soit son statut
    public function getArticleById(int $id): ?ArticleMapping
    {
        $sql = "SELECT
            a.article_id, a.article_title, a.article_slug, a.article_text, a.article_create_at,
            a.article_validate_at, a.article_status, a.user_user_id,
            u.user_id, u.user_login, u.user_full_name
            FROM article a
            INNER JOIN user u ON a.user_user_id = u.user_id
            WHERE a.article_id = :id";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':id', $id, MyPDO::PARAM_INT);
        try{
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération de l'article : " . $e->getMessage());
        }

        if($stmt->rowCount() === 0) {
            return null;
        }
        $article = $stmt->fetch();
        $stmt->closeCursor();

        $art = new ArticleMapping($article);
        $art->setUser(new UserMapping($article));
        return $art;
    }

    // création des slugs pour les nouveaux articles
    use SlugifyTrait;

    // création d'un article, retourne l'id du nouvel article
    public function insertArticle(ArticleMapping $article): int
    {
        // création du slug unique à partir du titre (préfixe aléatoire du trait)
        // limité à 184 caractères comme en base
        $slug = rtrim(substr($this->slugify($article->getArticleTitle()), 0, 184), '-');

        // si l'article est publié directement, on le valide maintenant
        $sql = "INSERT INTO article
            (article_title, article_slug, article_text, article_status, article_validate_at, user_user_id)
            VALUES (:title, :slug, :text, :status, IF(:status_check = 'publié', NOW(), NULL), :user_id)";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':title', $article->getArticleTitle());
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':text', $article->getArticleText());
        $stmt->bindValue(':status', $article->getArticleStatus());
        $stmt->bindValue(':status_check', $article->getArticleStatus());
        $stmt->bindValue(':user_id', $article->getUserUserId(), MyPDO::PARAM_INT);
        try{
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création de l'article : " . $e->getMessage());
        }

        return (int) $this->connect->lastInsertId();
    }

    // modification d'un article (le slug ne change pas pour garder les liens valides)
    public function updateArticle(ArticleMapping $article): bool
    {
        // la date de validation est remplie la première fois que l'article passe en "publié"
        $sql = "UPDATE article SET
            article_title = :title,
            article_text = :text,
            article_validate_at = IF(article_validate_at IS NULL AND :status_check = 'publié', NOW(), article_validate_at),
            article_status = :status
            WHERE article_id = :id";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':title', $article->getArticleTitle());
        $stmt->bindValue(':text', $article->getArticleText());
        $stmt->bindValue(':status_check', $article->getArticleStatus());
        $stmt->bindValue(':status', $article->getArticleStatus());
        $stmt->bindValue(':id', $article->getArticleId(), MyPDO::PARAM_INT);
        try{
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la modification de l'article : " . $e->getMessage());
        }

        return true;
    }

    // suppression d'un article, retourne false si l'article n'existait pas
    public function deleteArticle(int $id): bool
    {
        $sql = "DELETE FROM article WHERE article_id = :id";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':id', $id, MyPDO::PARAM_INT);
        try{
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression de l'article : " . $e->getMessage());
        }

        return $stmt->rowCount() === 1;
    }
}