<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\Data;

use App\Shared\Domain\Entity\Mocker\Endpoint\Data\EndpointData;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EndpointDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EndpointData::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel('Название')->setHelp('Название ответа (ни на что не влияет)')->setColumns(7),
            TextField::new('expression')->setLabel('Условие')->setHelp('Условие где && -> это и || -> это или =/!= -> соответственно. Например: (key1 = true || key2 = false) && key3 = true')->setColumns(7)->hideOnIndex(),
            IntegerField::new('statusCode')->setLabel('Статус код')->setHelp('HTTP код ответа')->setColumns(7)->setEmptyData(200),
            BooleanField::new('active')->setLabel('Активен')->setHelp('Активен ли вариант ответа')->setColumns(7)->setEmptyData(true),
            CollectionField::new('responseVariants')
                ->allowAdd(true)
                ->allowDelete(true)
                ->useEntryCrudForm(EndpointDataResponseVariantCrudController::class)
                ->setLabel('Варианты ответов')
                ->setHelp('Добавляйте или удаляйте варианты ответов. Только один вариант может быть активен.')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
                ->setColumns(13),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleActiveVariants($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleActiveVariants($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handleActiveVariants(EndpointData $endpointData): void
    {
        $activeVariant = null;
        foreach ($endpointData->getResponseVariants() as $variant) {
            if ($variant->isActive()) {
                if ($activeVariant) {
                    $variant->setActive(false);
                } else {
                    $activeVariant = $variant;
                }
            }
        }
    }
}
