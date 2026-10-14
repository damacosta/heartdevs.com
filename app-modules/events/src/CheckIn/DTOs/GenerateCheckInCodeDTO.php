<?php

declare(strict_types=1);

namespace He4rt\Events\CheckIn\DTOs;

use Carbon\CarbonInterface;

final readonly class GenerateCheckInCodeDTO
{
    public function __construct(
        public string $eventId,
        public CarbonInterface $eventDate,
        public CarbonInterface $startsAt,
        public CarbonInterface $expiresAt,
        public ?int $maxUses = null,
        public int $digits = 6,
    ) {}
}
