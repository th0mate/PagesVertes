<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use App\Service\DateServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['adresseEmail'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_CODE', fields: ['code'])]
#[UniqueEntity(fields: ['login'], message: 'Ce login est déjà utilisé!')]
#[UniqueEntity(fields: ['adresseEmail'], message: 'Cette adresse email est déjà utilisée!')]
#[UniqueEntity(fields: ['code'], message: 'Ce code est déjà utilisé!')]
#[ORM\HasLifecycleCallbacks]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Length(min: 4, max: 20, minMessage: 'Il faut au moins 4 caractères!', maxMessage: 'Il faut au plus 20 caractères!')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $login = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Email (message: 'L\'adresse mail n\'est pas valide')]
    private ?string $adresseEmail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 6, exactMessage: 'Le code doit contenir exactement 6 caractères')]
    #[Assert\Regex(pattern:'#^[a-zA-Z0-9]+$#', message: 'Le code ne doit contenir que des caractères alphanumériques.')]
    private ?string $code = null;

    #[ORM\Column(options: ["default" => true])]
    private ?bool $estVisible = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Il faut au plus 255 caractères!')]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Il faut au plus 255 caractères!')]
    private ?string $nom = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Length(min: 10, max:  10, exactMessage: 'Il faut exactement 10 caractères pour un numéro de téléphone!')]
    private ?string $telephone = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Il faut au plus 255 caractères!')]
    private ?string $facebook = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDerniereConnexion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDerniereEdition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->login;
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {

    }

    public function getAdresseEmail(): ?string
    {
        return $this->adresseEmail;
    }

    public function setAdresseEmail(string $adresseEmail): static
    {
        $this->adresseEmail = $adresseEmail;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function isEstVisible(): ?bool
    {
        return $this->estVisible;
    }

    public function setEstVisible(bool $estVisible): static
    {
        $this->estVisible = $estVisible;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getDateDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->dateDerniereConnexion;
    }

    public function setDateDerniereConnexion(\DateTimeInterface $dateDerniereConnexion): static
    {
        $this->dateDerniereConnexion = $dateDerniereConnexion;

        return $this;
    }

    public function getDateDerniereEdition(): ?\DateTimeInterface
    {
        return $this->dateDerniereEdition;
    }

    public function setDateDerniereEdition(\DateTimeInterface $dateDerniereEdition): static
    {
        $this->dateDerniereEdition = $dateDerniereEdition;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersistExempleChamp() : void {

        try
        {
            $parisTimezone = new \DateTimeZone('Europe/Paris');
            $date = new \DateTime('now', $parisTimezone);
        } catch (\Exception $e)
        {
            $date = new \DateTime();
        }
        $this->dateDerniereConnexion = $date;
        $this->dateDerniereEdition = $date;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'login' => $this->getLogin(),
            'roles' => $this->getRoles(),
            'adresseEmail' => $this->getAdresseEmail(),
            'code' => $this->getCode(),
            'estVisible' => $this->isEstVisible(),
            'prenom' => $this->getPrenom(),
            'nom' => $this->getNom(),
            'telephone' => $this->getTelephone(),
            'facebook' => $this->getFacebook(),
            'dateDerniereConnexion' => $this->getDateDerniereConnexion(),
            'dateDerniereEdition' => $this->getDateDerniereEdition(),
        ];
    }
}
