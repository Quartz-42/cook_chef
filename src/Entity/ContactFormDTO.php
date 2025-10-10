<?php

namespace App\Entity;

class ContactFormDTO
{
    private string $nom;
     private string $mail;
     private string $message;

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
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