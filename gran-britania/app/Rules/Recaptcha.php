<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements Rule
{
    protected float $minScore;
    protected ?string $action;

    /**
     * Create a new rule instance.
     *
     * @param float $minScore Minimum score for reCAPTCHA v3 (0.0 - 1.0). Default 0.5
     * @param string|null $action Expected action name for reCAPTCHA v3 (optional)
     */
    public function __construct(float $minScore = 0.5, ?string $action = null)
    {
        $this->minScore = $minScore;
        $this->action = $action;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $secret = config('services.recaptcha.secret');
        if (empty($secret) || empty($value)) {
            return false;
        }

        try {
            $res = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $res->ok()) {
                return false;
            }

            $body = $res->json();

            if (! (isset($body['success']) && $body['success'] === true)) {
                return false;
            }

            // If Google returned a score (v3), enforce minimum threshold
            if (isset($body['score']) && is_numeric($body['score'])) {
                if ($body['score'] < $this->minScore) {
                    return false;
                }
            }

            // If an expected action was provided, ensure it matches the returned action
            if ($this->action !== null && isset($body['action'])) {
                if ($body['action'] !== $this->action) {
                    return false;
                }
            }

            return true;
        } catch (\Throwable $e) {
            // on any error, fail safe (reject)
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No se pudo verificar el reCAPTCHA. Por favor inténtalo de nuevo.';
    }
}
