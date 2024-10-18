<?php

namespace App\Shared\Domain\Entity\File;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $filename;

    private string $url;

    private mixed $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): File
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): File
    {
        $this->name = $name;
        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): File
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFile(): mixed
    {
        return $this->file;
    }

    public function setFile(mixed $file): File
    {
        $this->file = $file;
        return $this;
    }

    public function getUrl(): string
    {
        return sprintf('%s/%s/%s', $_ENV['MINIO_HUMAN_ENDPOINT'], $_ENV['MINIO_BUCKET'], $this->filename);
    }
}