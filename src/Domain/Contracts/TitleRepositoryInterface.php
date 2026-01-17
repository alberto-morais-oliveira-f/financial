<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\Title;

interface TitleRepositoryInterface
{
    public function save(Title $title): Title;
    public function findById(string $uuid): ?Title;
    public function findPendingDueUntil(\DateTimeInterface $date): array;
}
