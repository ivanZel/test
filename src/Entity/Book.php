<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\Book\GetMyBooks;
use App\Controller\Book\NewBook;
use App\Controller\FileDownloadController;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\OpenApi\Model;
use ArrayObject;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]

#[ORM\Entity(repositoryClass: "App\Repository\BookRepository")]
#[ORM\Table(name: "books")]
#[ApiResource(
    operations: [
        new Post(
            controller: NewBook::class,
            openapi: new Model\Operation(
                parameters: [],
                requestBody: new Model\RequestBody(
                    content: new ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => [
                                    'title',
                                    'file'
                                ],
                                'properties' => [
                                    'title' => [
                                        'type' => 'string',
                                    ],
                                    'file' => [
                                        'type' => 'file',
                                    ]
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
            name: 'new_book'
        ),
        new Get(
            uriTemplate: '/books/{id}/download',
            controller: FileDownloadController::class,
            openapi: new Model\Operation(
                description: 'Download the book file',
                parameters: [new Model\Parameter(
                        name: 'id',
                        in: 'path',
                        required: true,
                        schema: [
                            'type' => 'integer',
                        ],
                    ),
                ],

            ),
            normalizationContext: [
                'skip_null_values' => false,
                'groups' => 'get'
            ],
            read: false,
            deserialize: false,
            serialize: false,
            name: 'book-download'
        ),
         new Get(
             uriTemplate: "/my-books",
             controller: GetMyBooks::class,
             openapi: new Model\Operation(
                 description: "Get my info",
                 parameters: []
             ),
             read: false,
             deserialize: false,
             name: 'my_books',
         ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
        'groups' => ['get']
    ],
    denormalizationContext: [
        'groups' => ['get', 'post']
    ],
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["post", "get"])]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ApiProperty(types: ['https://schema.org/contentUrl'], writable: false)]
    #[Groups(['media_object:read'])]
    public ?string $contentUrl;

    #[Vich\UploadableField(mapping: 'file', fileNameProperty: 'filePath')]
    public $file;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    #[Groups(["post", "get"])]
    public ?string $filePath;

    #[ApiProperty(writable: false)]
    #[ORM\Column(type: "datetime",nullable: true)]
    private ?\DateTimeInterface $uploadTimestamp ;

    public function __construct(
        #[ORM\Column(type: "string", nullable: true)]
        #[Groups(["post", "get"])]
        private ?string $title,
        #[ORM\ManyToOne(targetEntity: "User", cascade: ["persist"], inversedBy: "books")]
        #[ORM\JoinColumn(name: "user", referencedColumnName: "id", onDelete: "CASCADE")]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private User $user,
    )
    {
        $this->uploadTimestamp = new DateTime();
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(?string $contentUrl): void
    {
        $this->contentUrl = $contentUrl;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile( $file): void
    {
        $this->file = $file;

    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getUploadTimestamp(): ?\DateTimeInterface
    {
        return $this->uploadTimestamp;
    }

    public function setUploadTimestamp(?\DateTimeInterface $uploadTimestamp): void
    {
        $this->uploadTimestamp = $uploadTimestamp;
    }

}
