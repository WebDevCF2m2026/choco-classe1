<?php
// path: model/mapping/ArticleMapping.php
// typage strict
declare(strict_types=1);

namespace model\mapping;

use model\abstract\AbstractMapping;

// Exception si le statut de l'article n'est pas valide
use Exception;

use model\mapping\UserMapping;

class ArticleMapping extends AbstractMapping
{
    // propriétés correspondant aux champs de la table `article`
    protected ?int $article_id = null;
    protected ?string $article_title = null;
    protected ?string $article_slug = null;
    protected ?string $article_text = null;
    protected ?string $article_create_at = null;
    protected ?string $article_validate_at = null;
    protected ?string $article_status = null;
    protected ?int $user_user_id = null;

    // ajout de l'utilisateur lié lors d'une requête
    protected ?UserMapping $user = null;

    public function getUser(): ?UserMapping
    {
        return $this->user;
    }

    public function setUser(?UserMapping $user): void
    {
        $this->user = $user;
    }


    // valeurs autorisées pour `article_status`
    // constante privée pour les statuts d'article
    private const STATUS = ['publié', 'en attente', 'désactivé'];

    // statut par défaut d'un nouvel article
    public const DEFAULT_STATUS = 'en attente';

    // liste des statuts autorisés (pour les formulaires)
    public static function getStatusList(): array
    {
        return self::STATUS;
    }

    // Le constructeur est hérité de AbstractMapping, il appelle la méthode hydrate() pour initialiser les propriétés avec les données passées en paramètre.

    // getters

    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function getArticleTitle(): ?string
    {
        return $this->article_title;
    }

    public function getArticleSlug(): ?string
    {
        return $this->article_slug;
    }

    public function getArticleText(): ?string
    {
        return $this->article_text;
    }

    public function getArticleCreateAt(): ?string
    {
        return $this->article_create_at;
    }

    public function getArticleValidateAt(): ?string
    {
        return $this->article_validate_at;
    }

    public function getArticleStatus(): ?string
    {
        return $this->article_status;
    }

    public function getUserUserId(): ?int
    {
        return $this->user_user_id;
    }

    // setters

    public function setArticleId(int|string|null $article_id): void
    {
        // PDO renvoie des chaînes, on convertit en entier
        if ($article_id === null) {
            $this->article_id = null;
            return;
        }
        $article_id = (int) $article_id;
        if ($article_id < 1) {
            throw new Exception("L'id de l'article doit être positif");
        }
        $this->article_id = $article_id;
    }

    public function setArticleTitle(?string $article_title): void
    {
        if ($article_title === null) {
            $this->article_title = null;
            return;
        }
        $article_title = trim(strip_tags($article_title));
        if ($article_title === '' || mb_strlen($article_title) > 180) {
            throw new Exception("Le titre doit contenir entre 1 et 180 caractères");
        }
        $this->article_title = $article_title;
    }

    public function setArticleSlug(?string $article_slug): void
    {
        if ($article_slug === null) {
            $this->article_slug = null;
            return;
        }
        $article_slug = trim($article_slug);
        if ($article_slug === '' || mb_strlen($article_slug) > 184) {
            throw new Exception("Le slug doit contenir entre 1 et 184 caractères");
        }
        $this->article_slug = $article_slug;
    }

    public function setArticleText(?string $article_text): void
    {
        if ($article_text === null) {
            $this->article_text = null;
            return;
        }
        $article_text = trim($article_text);
        if ($article_text === '') {
            throw new Exception("Le texte de l'article ne peut être vide");
        }
        $this->article_text = $article_text;
    }

    public function setArticleCreateAt(?string $article_create_at): void
    {
        $this->article_create_at = $article_create_at;
    }

    public function setArticleValidateAt(?string $article_validate_at): void
    {
        $this->article_validate_at = $article_validate_at;
    }

    public function setArticleStatus(?string $article_status): void
    {
        if ($article_status !== null && !in_array($article_status, self::STATUS, true)) {
            throw new Exception("Statut d'article invalide");
        }
        $this->article_status = $article_status;
    }

    public function setUserUserId(int|string|null $user_user_id): void
    {
        if ($user_user_id === null) {
            $this->user_user_id = null;
            return;
        }
        $user_user_id = (int) $user_user_id;
        if ($user_user_id < 1) {
            throw new Exception("L'id de l'utilisateur doit être positif");
        }
        $this->user_user_id = $user_user_id;
    }
}
