<?php

declare(strict_types=1);

namespace App\Service\Book;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Book;
use App\Entity\User;
use App\Exception\NotFoundItemException;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Exception;

/**
 * Class UserService
 * @package App\Service
 */
class BookService
{
    public const FORMAT = 'multipart';

    public function __construct(
         BookRepository $bookRepository,
         ValidatorInterface $validator,
    )
    {
        $this->bookRepository = $bookRepository;
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    public function createBook( $request, User $user): Book
    {
        $book = new Book($request->get('title'), $user);
        $this->validator->validate($book);

        if ($request->files->has('file') && $request->files->get('file') instanceof UploadedFile) {
            $uploadedFile = $request->files->get('file');
            $book->setFile($uploadedFile);
        }

        try {
            $this->bookRepository->save($book);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $book;
    }

    /**
     * @throws Exception
     */
    public function getMyBook($user): ArrayCollection
    {
        return new ArrayCollection($user->getBooks()->toArray());
    }

    /**
     * @throws Exception
     */
    public function bookDownload($filePath):BinaryFileResponse
    {
        // Проверяем существует ли файл
        if (!file_exists($filePath)) {
            throw new FileNotFoundException('File not found at ' . $filePath);
        }

        // Создаем объект
        $file = new File($filePath);

        // ответ для скачивания файла
        $response = new BinaryFileResponse($file);

        // заголовок для скачивания файла
        return $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getFilename()
        );
    }

}
