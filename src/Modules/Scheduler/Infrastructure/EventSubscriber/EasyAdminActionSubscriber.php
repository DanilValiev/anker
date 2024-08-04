<?php

namespace App\Modules\Scheduler\Infrastructure\EventSubscriber;

use App\Modules\Admin\Application\Api\System\CustomUiExceptionController;
use App\Modules\Admin\Infrastructure\Helper\EasyAdminFormHelper;
use App\Modules\Scheduler\Domain\Scheduler;
use DateTime;
use DateTimeZone;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EasyAdminActionSubscriber implements EventSubscriberInterface
{
    public const SCHEDULE_BUTTON_NAME = 'scheduleUpdate';

    public const SCHEDULE_TIME = 'scheduleTime';

    public function __construct(
        private readonly EasyAdminFormHelper         $easyAdminFormHelper,
        private readonly Scheduler                   $schedulerService,
        private readonly CustomUiExceptionController $exceptionController,
        private readonly AdminUrlGenerator $urlGenerator
    ) { }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeCrudActionEvent::class => ['catchAction']
        ];
    }

    /**
     * @throws Exception
     */
    public function catchAction(BeforeCrudActionEvent $event): void
    {
        $context = $event->getAdminContext();
        $requestArray = $context->getRequest()->request->all();
        $entityName = $context->getEntity()->getName();

        if (!isset($requestArray['ea']['newForm']['btn']) ||
            $requestArray['ea']['newForm']['btn'] != self::SCHEDULE_BUTTON_NAME) {
            return ;
        }

        if (!isset($requestArray[$entityName]['scheduleTime']) ||
            count(date_parse($requestArray[$entityName]['scheduleTime'])['errors']) != 0) {
            $event->setResponse($this->exceptionController->schedulerEmptyTimeException(
                "Необходимо указать дату запланированного изменения \n 
                Для этого установите время в поле \"Время исполнения (МСК)\"",
                $context->getReferrer()
            ));

            return ;
        }

        $executionTime = new DateTime($requestArray[$entityName]['scheduleTime'], new DateTimeZone('Europe/Moscow'));
        $executionTime->setTimezone(new DateTimeZone('UTC'));
        $executionTime->format('Y-m-d H:i:s T');

        $entity = $this->easyAdminFormHelper->getEntityFromContext($context);
        $this->schedulerService->scheduleEntity($entity, $context->getCrud()->getEntityFqcn(), $executionTime);

        $referrer = $context->getReferrer();

        if (empty($referrer)) {
            $referrer = $this->urlGenerator
                ->setController($context->getCrud()->getControllerFqcn())
                ->setAction(Action::INDEX)
                ->generateUrl()
            ;
        }

        $event->setResponse(new RedirectResponse($referrer));
    }
}