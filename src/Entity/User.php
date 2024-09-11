<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\User\Register;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\OpenApi\Model;
use ArrayObject;

#[ApiResource(
    operations: [
        new Post(
            controller: Register::class,
            openapi: new Model\Operation(
                parameters: [],
                requestBody: new Model\RequestBody(
                    content: new ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => [
                                    'email',
                                    'password',
                                    'confirmPassword',
                                    'firstName',
                                    'lastName',
                                    'dateOfBirth',
                                    'phone'
                                ],
                                'properties' => [
                                    'email' => [
                                        'type' => 'string',
                                        'format' =>'email'
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                    ],
                                    'confirmPassword' => [
                                        'type' => 'string',
                                    ],
                                    'firstName' => [
                                        'type' => 'string',
                                    ],
                                    'lastName' => [
                                        'type' => 'string',
                                    ],
                                    'dateOfBirth' => [
                                        'type' => 'string'
                                    ],
                                    'phone' => [
                                        'type' => 'string',
                                    ],
                                ]
                            ]
                        ]
                    ])
                )
            ),
            normalizationContext: [
                'skip_null_values' => false,
                'groups' => 'post'
            ],
            read: false,
            deserialize: false,
            name: 'create_user'
        )
    ],
    normalizationContext: [
        'skip_null_values' => false,
        'groups' => ['get']
    ],
    denormalizationContext: [
        'groups' => ['get', 'post']
    ],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'users')]
#[UniqueEntity(fields: ['email'], message: 'user.email.duplicate', errorPath: 'email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{

    public const USER_SUPER = 'ROLE_USER';


    public const USER_ROLES = [
        self::USER_SUPER,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "uuid", unique: true)]
    #[Groups(["post", "get"])]
    private Uuid $uuid;

    #[ORM\Column(type: "string", nullable: true)]
    #[Groups(["post", "get"])]
    private ?string $fullName;

    #[ORM\Column(type: "string")]
    #[Groups(["post", "get", "get_post_with_comments", "get_posts_list"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $firstName;

    #[ORM\Column(type: "string")]
    #[Groups(["post", "get", "get_post_with_comments", "get_posts_list"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $lastName;

    #[ORM\Column(type: "string", unique: true, length: 600)]
    #[Groups(["post", "get"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Email()]
    private ?string $email;

    #[ORM\Column(type: "string")]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $password;

    #[ORM\Column(type: "string", length: 500)]
    #[Groups(["post", "get"])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $phone;

    #[ORM\Column(type: "string")]
    #[Assert\NotNull]
    private ?string $dateOfBirth;

    #[ORM\Column(type: "integer")]
    #[Groups(["post", "get"])]
    private int $age = 0;

    #[ORM\Column(type: "string", length: 500)]
    #[Groups(["post", "get"])]
    private string $api;

    #[ORM\Column(type: "json")]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Groups(["get"])]
    private ?array $roles;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "Book")]
    private ?Collection $books;

    public function __construct(
        ?string $email,
        ?string $password,
        ?string $firstName,
        ?string $lastName,
        ?string $dateOfBirth,
        ?string $phone,
        ?array $roles,
    )
    {
        $this->uuid = new UuidV6();
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->roles = $roles;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getApi(): string
    {
        return $this->api;
    }

    public function setApi(string $api): void
    {
        $this->api = $api;
    }

    public function __toString(): string {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        // This method is not needed when using bcrypt or argon2i for password hashing
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    /**
     * @return array
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getBooks(): ?Collection
    {
        return $this->books;
    }

    public function setBooks(?Collection $books): void
    {
        $this->books = $books;
    }

}
