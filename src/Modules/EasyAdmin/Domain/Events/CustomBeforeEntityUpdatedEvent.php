<?php

namespace App\Modules\EasyAdmin\Domain\Events;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Event\AbstractLifecycleEvent;

final class CustomBeforeEntityUpdatedEvent extends AbstractLifecycleEvent
{
    public function __construct($entityInstance,
    private AdminContext $context)
    {
        parent::__construct($entityInstance);
    }
    
    public function getContext(): AdminContext
    {
        return $this->context;
    }
}