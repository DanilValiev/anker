<?php


namespace App\Shared\Infrastructure\RevisionManager\EventSubscriber;


use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineFilterSubscriber
 *
 * @package App\Doctrine\EventSubscriber
 */
final class RevisionLoggableEntityDeletionFilterSubscriber
{
    public const SOFT_DELETABLE_FILTER_NAME = 'soft_deletable';

    /**
     * Entity manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RevisionLoggableEntityDeletionFilterSubscriber constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * On kernel request event handler
     */
    public function onKernelRequest(): void
    {
        $this->em->getFilters()->enable(self::SOFT_DELETABLE_FILTER_NAME);
    }
}
