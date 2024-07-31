<?php

namespace App\Shared\RevisionManager\Filters;

interface RevisionLoggableEntityInterface
{
    /**
     * Get revTo property
     */
    public function getRevTo(): ?int;

    /**
     * Get revFrom property
     */
    public function getRevFrom(): int;

    /**
     * Get revFrom property
     */
    public function setRevFrom(int $revFrom): self;

    /**
     * Set revTo property
     */
    public function setRevTo(int $revTo): self;
}