<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\Data;

use App\Shared\Domain\Entity\Mocker\Endpoint\Data\ResponseVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use Setono\EasyadminEditorjsBundle\Field\EditorJSField;

class ResponseVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResponseVariant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            CodeEditorField::new('data')
                ->setLabel('Ответ')
                ->setLanguage('js')
                ->setCustomOptions(['lineNumbers' => true])
                ->setHtmlAttribute('style', 'height: auto; min-height: 200px')
                ->setHelp('Ответ в формате json'),
            BooleanField::new('active')->setLabel('Активен'),
        ];
    }
}
