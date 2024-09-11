<?php
namespace App\Controller\Book;


use App\Entity\Book;
use App\Service\Book\BookService;
use App\Service\User\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles the registration of a new user
 */
class NewBook extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService
    ) {}

    /**
     * @throws Exception
     */

    public function __invoke(Request $request):Book
    {
        return $this->bookService->createBook($request,$this->getUser());
    }

}
