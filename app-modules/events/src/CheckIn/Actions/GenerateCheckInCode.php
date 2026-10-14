<?php

declare(strict_types=1);

namespace He4rt\Events\CheckIn\Actions;

use He4rt\Events\CheckIn\DTOs\GenerateCheckInCodeDTO;
use He4rt\Events\CheckIn\Models\CheckInCode;

final readonly class GenerateCheckInCode
{
    public function handle(GenerateCheckInCodeDTO $dto): CheckInCode
    {
        return CheckInCode::query()->create([
            'event_id' => $dto->eventId,
            'event_date' => $dto->eventDate,
            'code' => $this->generateNumericCode($dto->digits),
            'starts_at' => $dto->startsAt,
            'expires_at' => $dto->expiresAt,
            'max_uses' => $dto->maxUses,
        ]);
    }

    private function generateNumericCode(int $digits): string
    {
        $min = 10 ** ($digits - 1);
        $max = 10 ** $digits - 1;

        return (string) random_int($min, $max);
    }
}
