<?php
namespace App\Controller\Book;

use App\Service\Book\BookService;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles the registration of a new user
 */
class GetMyBooks extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService
    ) {}

    /**
     * @throws Exception
     */

    public function __invoke(Request $request):ArrayCollection
    {
        return $this->bookService->getMyBook($this->getUser());
    }

}
