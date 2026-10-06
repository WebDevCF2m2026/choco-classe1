<?php
// path: model/mapping/UserMapping.php
// typage strict
declare(strict_types=1);

namespace model\mapping;

use model\abstract\AbstractMapping;

// Exception si une donnée de l'utilisateur n'est pas valide
use Exception;

class UserMapping extends AbstractMapping
{
    // propriétés correspondant aux champs de la table `user`
    protected ?int $user_id = null;
    protected ?string $user_login = null;
    protected ?string $user_pwd = null;
    protected ?string $user_full_name = null;
    protected ?string $user_email = null;
    protected ?string $user_role = null;

    // valeurs autorisées pour `user_role`
    // constante privée pour les rôles d'utilisateur
    private const ROLES = ['admin', 'user'];

    // Le constructeur est hérité de AbstractMapping, il appelle la méthode hydrate() pour initialiser les propriétés avec les données passées en paramètre.

    // getters

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getUserLogin(): ?string
    {
        return $this->user_login;
    }

    public function getUserPwd(): ?string
    {
        return $this->user_pwd;
    }

    public function getUserFullName(): ?string
    {
        return $this->user_full_name;
    }

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function getUserRole(): ?string
    {
        return $this->user_role;
    }

    // setters

    public function setUserId(int|string|null $user_id): void
    {
        // PDO renvoie des chaînes, on convertit en entier
        if ($user_id === null) {
            $this->user_id = null;
            return;
        }
        $user_id = (int) $user_id;
        if ($user_id < 1) {
            throw new Exception("L'id de l'utilisateur doit être positif");
        }
        $this->user_id = $user_id;
    }

    public function setUserLogin(?string $user_login): void
    {
        if ($user_login === null) {
            $this->user_login = null;
            return;
        }
        $user_login = trim(strip_tags($user_login));
        if ($user_login === '' || mb_strlen($user_login) > 50) {
            throw new Exception("Le login doit contenir entre 1 et 50 caractères");
        }
        $this->user_login = $user_login;
    }

    public function setUserPwd(?string $user_pwd): void
    {
        // le mot de passe est stocké tel quel (déjà hashé en base),
        // le hashage se fait au moment de l'insertion
        if ($user_pwd === null) {
            $this->user_pwd = null;
            return;
        }
        if ($user_pwd === '' || strlen($user_pwd) > 255) {
            throw new Exception("Le mot de passe doit contenir entre 1 et 255 caractères");
        }
        $this->user_pwd = $user_pwd;
    }

    public function setUserFullName(?string $user_full_name): void
    {
        // champ facultatif (NULL autorisé en base)
        if ($user_full_name === null) {
            $this->user_full_name = null;
            return;
        }
        $user_full_name = trim(strip_tags($user_full_name));
        if (mb_strlen($user_full_name) > 100) {
            throw new Exception("Le nom complet ne peut dépasser 100 caractères");
        }
        $this->user_full_name = $user_full_name === '' ? null : $user_full_name;
    }

    public function setUserEmail(?string $user_email): void
    {
        if ($user_email === null) {
            $this->user_email = null;
            return;
        }
        $user_email = trim($user_email);
        if (mb_strlen($user_email) > 100 || filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception("L'adresse email n'est pas valide");
        }
        $this->user_email = $user_email;
    }

    public function setUserRole(?string $user_role): void
    {
        if ($user_role !== null && !in_array($user_role, self::ROLES, true)) {
            throw new Exception("Rôle d'utilisateur invalide");
        }
        $this->user_role = $user_role;
    }
}
