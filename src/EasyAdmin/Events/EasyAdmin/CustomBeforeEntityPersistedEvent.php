<?php

namespace App\EasyAdmin\Events\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Event\AbstractLifecycleEvent;

final class CustomBeforeEntityPersistedEvent extends AbstractLifecycleEvent
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