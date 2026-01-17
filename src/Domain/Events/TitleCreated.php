<?php

namespace Am2tec\Financial\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Am2tec\Financial\Domain\Entities\Title;

class TitleCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Title $title
    ) {}
}
