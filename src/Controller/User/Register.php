<?php
namespace App\Controller\User;

use App\Entity\User;
use App\Service\User\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles the registration of a new user
 */
class Register extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * @throws Exception
     */

    public function __invoke(Request $request):User
    {
        return $this->userService->createUser($request, $request->getClientIps());
    }
}
