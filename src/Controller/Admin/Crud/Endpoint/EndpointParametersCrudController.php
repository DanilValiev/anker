<?php

namespace App\Controller\Admin\Crud\Endpoint;

use App\Shared\Doctrine\Entity\Mocker\Endpoint;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EndpointParametersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Endpoint::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel('Имя')->setHelp('Ключ параметра, например в параметрах ?key=value "key" = это ключ параметра'),
            BooleanField::new('active')->setLabel('Активен')->setHelp('Активен ли параметр'),
            BooleanField::new('required')->setLabel('Обязательный')->setHelp('Обязательный параметр'),
            ChoiceField::new('type')->setChoices(['Любой' => 'mixed', 'Строка' => 'string', 'Число' => 'int', 'Булевый' => 'bool'])->setLabel('Тип значения')->setHelp('Валидация на тип значения'),
            CodeEditorField::new('regex')->setLabel('Регулярное выражение')->setHelp('Валидация по регулярному выражению'),
            ArrayField::new('whitelist')->setLabel('Белый список')->setHelp('Белый список значений'),
            ArrayField::new('errorMessage')->setLabel('Маппинг ошибок')->setHelp('Маппинг кодов ошибок с возвращаемыми ошибками'),
        ];
    }
}
