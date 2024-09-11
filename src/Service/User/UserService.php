<?php

declare(strict_types=1);

namespace App\Service\User;

use ApiPlatform\Validator\ValidatorInterface;
use App\DTO\User\RegisterUserInput;
use App\Entity\User;
use App\Exception\NotFoundItemException;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    /**
     * UserService constructor.
     */
    public function __construct(
         UserRepository $userRepository,
         UserPasswordHasherInterface $passwordHasher,
         ValidatorInterface $validator,
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    public function calculateAge($birthdate): int
    {
        $currentDate = Carbon::now();

        return $currentDate->diffInYears(Carbon::create($birthdate));
    }

    /**
     * @throws Exception
     */
    public function createUser( $request, $api): User
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $confirmPassword = $request->get('confirmPassword');
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $dateOfBirth = $request->get('dateOfBirth');
        $phone = $request->get('phone');

        $input = new RegisterUserInput(
            $email,
            $password,
            $confirmPassword,
            $firstName,
            $lastName,
            $dateOfBirth,
            $phone
        );

        if ($dateOfBirth !== null) {
            try {
                $dateOfBirth = Carbon::createFromFormat('d/m/Y',$dateOfBirth)->format('Y-m-d');
            } catch (Exception $e) {
                $dateOfBirth = $request->get('dateOfBirth');
            }
        }

        if ($password !== $confirmPassword) {
            throw new Exception($this->translator->trans('user.password.not_same'));
        }

        $age = $this->calculateAge($dateOfBirth);

        $user = new User(
            $input->getEmail(),
            $input->getPassword(),
            $input->getFirstName(),
            $input->getLastName(),
            $input->getDateOfBirth(),
            $input->getPhone(),
            ['ROLE_USER']
        );
        if($firstName && $lastName){
            $user->setFullName($firstName . ' ' . $lastName);
        }
        $user->setAge($age);
        $user->setApi($api[0]);
        $user->setPassword($this->passwordHasher->hashPassword($user,  $password));

        $this->validator->validate($user);

        try {
            $this->userRepository->save($user);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $user;
    }
}
