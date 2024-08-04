<?php

namespace App\Modules\Admin\Infrastructure\Helper;

use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\AssetsDto;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EasyAdminFormHelper
{
    public function __construct(
        private ContainerInterface $container,
        private EntityFactory $entityFactory,
    ) { }

    public function getEntityFromContext(AdminContext $context): object
    {
        $crudControllerFqcn = $context->getCrudControllers()->findCrudFqcnByEntityFqcn($context->getEntity()->getFqcn());
        /** @var AbstractCrudController $crudController */
        $crudController = $this->container->get($crudControllerFqcn);

        $this->entityFactory->processFields($context->getEntity(), FieldCollection::new($crudController->configureFields($context->getCrud()->getCurrentPage())));
        $context->getCrud()->setFieldAssets($this->getFieldAssets($context->getEntity()->getFields(), $context));

        $form = $crudController->createEditForm($context->getEntity(), $context->getCrud()->getEditFormOptions(), $context);
        $form->handleRequest($context->getRequest());

        return $context->getEntity()->getInstance();
    }

    private function getFieldAssets(FieldCollection $fieldDtos, ?AdminContext $context): AssetsDto
    {
        $fieldAssetsDto = new AssetsDto();
        $currentPageName = $context?->getCrud()?->getCurrentPage();
        foreach ($fieldDtos as $fieldDto) {
            $fieldAssetsDto = $fieldAssetsDto->mergeWith($fieldDto->getAssets()->loadedOn($currentPageName));
        }

        return $fieldAssetsDto;
    }
}