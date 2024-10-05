<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\Parameters;

use App\Shared\Domain\Entity\Mocker\Endpoint\Parameters\EndpointParam;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EndpointParametersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EndpointParam::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Основное'),
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel('Имя')->setHelp('Ключ параметра, например в параметрах ?key=value "key" = это ключ параметра')->setColumns(7),
            ChoiceField::new('type')->setChoices(['Любой' => 'mixed', 'Строка' => 'string', 'Число' => 'int', 'Булевый' => 'bool'])->setLabel('Тип значения')->setHelp('Валидация на тип значения')->setColumns(7)->setEmptyData('mixed'),
            BooleanField::new('active')->setLabel('Активен')->setHelp('Активен ли параметр')->setColumns(7)->setEmptyData(true),

            FormField::addTab('Валидация'),
            BooleanField::new('required')->setLabel('Обязательный')->setHelp('Обязательный параметр')->setColumns(7)->setEmptyData(true),
            CodeEditorField::new('regex')->setLabel('Регулярное выражение')->setHelp('Валидация по регулярному выражению')->setColumns(7)->hideOnIndex(),
            ArrayField::new('whitelist')->setLabel('Белый список')->setHelp('Белый список значений')->setColumns(7)->hideOnIndex(),
            ArrayField::new('errorMessage')->setLabel('Маппинг ошибок')->setHelp('Маппинг кодов ошибок с возвращаемыми ошибками')->setColumns(7)->hideOnIndex(),
        ];
    }
}
