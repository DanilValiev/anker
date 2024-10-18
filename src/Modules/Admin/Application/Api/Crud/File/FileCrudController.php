<?php

namespace App\Modules\Admin\Application\Api\Crud\File;

use App\Modules\File\Infrastructure\FileUploader;
use App\Shared\Domain\Entity\File\File;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use League\Flysystem\FilesystemException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class FileCrudController extends AbstractCrudController
{
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DELETE);
    }

    public static function getEntityFqcn(): string
    {
        return File::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Имя файла');

        yield Field::new('file', 'Загрузить файл')
            ->setFormType(FileType::class)
            ->onlyOnForms();

        yield UrlField::new('url', 'Ссылка')
            ->onlyOnIndex();
    }

    /**
     * @throws FilesystemException
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $file = $entityInstance->getFile();

        if ($file) {
            $filename = uniqid().'.'.$file->guessExtension();
            $this->fileUploader->upload($file, $filename);
            $entityInstance->setFilename($filename);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * @throws FilesystemException
     */
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->fileUploader->delete($entityInstance->getFilename());
        parent::deleteEntity($entityManager, $entityInstance);
    }
}