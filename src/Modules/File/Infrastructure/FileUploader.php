<?php

namespace App\Modules\File\Infrastructure;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

class FileUploader
{
    private FilesystemOperator $filesystem;

    public function __construct(FilesystemOperator $minioStorage)
    {
        $this->filesystem = $minioStorage;
    }

    /**
     * @throws FilesystemException
     */
    public function upload($file, $filename): void
    {
        $stream = fopen($file->getRealPath(), 'r+');
        $this->filesystem->writeStream($filename, $stream);
        fclose($stream);
    }

    /**
     * @throws FilesystemException
     */
    public function delete($filename): void
    {
        $this->filesystem->delete($filename);
    }

    public function getUrl($filename): string
    {
        // Если Minio настроен на публичный доступ
        return sprintf('%s/%s/%s', $_ENV['MINIO_HUMAN_ENDPOINT'], $_ENV['MINIO_BUCKET'], $filename);
    }
}