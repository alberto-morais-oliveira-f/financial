<?php

namespace Am2tec\Financial\Domain\Traits;

trait EnumTrait
{
    public static function asSelectArray(): array
    {
        return collect(self::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->getLabel()];
        })->all();
    }

    public function getLabel(): string
    {
        return match ($this) {
            default => str_replace('_', ' ', \Illuminate\Support\Str::title($this->value)),
        };
    }
}
