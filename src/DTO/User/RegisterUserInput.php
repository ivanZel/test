<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserInput
{
    public function __construct(
        #[Assert\Email(
            message: 'user.email.validation'
        )]
        private ?string $email,
        #[Assert\Regex(
            pattern: '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*()\-_=+{}|?>.<,:;~`’]{8,}$/',
            message: 'user.password.validation'
        )]
        private ?string $password,
        #[Assert\Regex(
            pattern: '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*()\-_=+{}|?>.<,:;~`’]{8,}$/',
            message: 'user.password.validation'
        )]
        private ?string $confirmPassword,
        private ?string $firstName,
        private ?string $lastName,
        private ?string $dateOfBirth,
        #[Assert\Regex(pattern: '/^[0-9+]{0,1}+[0-9]{11,16}$/', message: 'Bad phone number.')]
        private ?string $phone

    )
    {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(?string $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }


}
