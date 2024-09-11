<?php
namespace App\Controller;

use App\Repository\BookRepository;
use App\Service\Book\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class FileDownloadController extends AbstractController
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly BookService $bookService,

    ) {}

    public function __invoke(Request $request): Response
    {
        $bookId = (int)$request->attributes->get('id');

        if (!$book = $this->bookRepository->find($bookId)) {
            throw new NotFoundHttpException('Book not found');
        }

        $filePath = $this->getParameter('kernel.project_dir') . '/src/Resource/' . $book->getFilePath();

        return $this->bookService->bookDownload($filePath);
    }
}
