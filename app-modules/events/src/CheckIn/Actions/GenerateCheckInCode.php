<?php

declare(strict_types=1);

namespace He4rt\Events\CheckIn\Actions;

use He4rt\Events\CheckIn\DTOs\GenerateCheckInCodeDTO;
use He4rt\Events\CheckIn\Models\CheckInCode;
use Illuminate\Database\UniqueConstraintViolationException;

final readonly class GenerateCheckInCode
{
    // idx_check_in_codes_unique é (event_id, event_date, code) — 5 tentativas
    // cobre a chance remota de colisão num evento/dia com muitos códigos já gerados.
    private const int MAX_ATTEMPTS = 5;

    public function handle(GenerateCheckInCodeDTO $dto): CheckInCode
    {
        $attempts = 0;

        while (true) {
            $attempts++;

            try {
                return CheckInCode::query()->create([
                    'event_id' => $dto->eventId,
                    'event_date' => $dto->eventDate,
                    'code' => $this->generateNumericCode($dto->digits),
                    'starts_at' => $dto->startsAt,
                    'expires_at' => $dto->expiresAt,
                    'max_uses' => $dto->maxUses,
                ]);
            } catch (UniqueConstraintViolationException $uniqueConstraintViolationException) {
                throw_if($attempts >= self::MAX_ATTEMPTS, $uniqueConstraintViolationException);
            }
        }
    }

    private function generateNumericCode(int $digits): string
    {
        $min = 10 ** ($digits - 1);
        $max = 10 ** $digits - 1;

        return (string) random_int($min, $max);
    }
}
