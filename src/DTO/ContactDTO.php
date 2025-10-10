<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $mail;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10,max: 1000)]
    private string $message;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}