<?php

namespace App\Models\Concerns;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

trait HasEncodedRouteKey
{
    private ?string $encodedRouteKey = null;

    public function getRouteKey(): string
    {
        if ($this->encodedRouteKey !== null) {
            return $this->encodedRouteKey;
        }

        $encryptedKey = Crypt::encryptString((string) $this->getKey());

        return $this->encodedRouteKey = rtrim(strtr($encryptedKey, '+/', '-_'), '=');
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field !== null && $field !== $this->getRouteKeyName()) {
            return parent::resolveRouteBinding($value, $field);
        }

        $decodedKey = $this->decodeRouteKey($value);

        if ($decodedKey === null) {
            return null;
        }

        return $this->resolveRouteBindingQuery($this, $decodedKey, $this->getRouteKeyName())->first();
    }

    private function decodeRouteKey(mixed $value): ?int
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        $encryptedKey = strtr($value, '-_', '+/');
        $encryptedKey .= str_repeat('=', (4 - strlen($encryptedKey) % 4) % 4);

        try {
            $decryptedKey = Crypt::decryptString($encryptedKey);
        } catch (DecryptException) {
            return null;
        }

        if (! ctype_digit($decryptedKey) || (int) $decryptedKey < 1) {
            return null;
        }

        return (int) $decryptedKey;
    }
}
