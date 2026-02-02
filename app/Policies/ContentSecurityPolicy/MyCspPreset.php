<?php

namespace App\Policies\ContentSecurityPolicy;

use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Directive;

class MyCspPreset implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy->add(Directive::SCRIPT, [
            "'self'",
            'ajax.googleapis.com',
            "'unsafe-eval'",   // Livewire 4
            'cdn.jsdelivr.net',
            'unpkg.com',
            'challenges.cloudflare.com', // Turnstile
        ]);

        $policy->addNonce(Directive::SCRIPT);

        // (opsional tapi bagus)
        $policy->add(Directive::CONNECT, [
            "'self'",
            'challenges.cloudflare.com',
            'unpkg.com',
            'cdn.jsdelivr.net',
        ]);

        $policy->add(Directive::FRAME, [
            'challenges.cloudflare.com', // 🔥 penting untuk Turnstile iframe
        ]);

        $policy->add(Directive::FONT, [
            "'self'",
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'use.fontawesome.com',
            'cdn.jsdelivr.net'
        ]);

        $policy->addNonce(Directive::SCRIPT);

        // STYLE  🔥 WAJIB untuk Turnstile
        $policy->add(Directive::STYLE, [
            "'self'",
            'unpkg.com',
            'cdn.jsdelivr.net',
            'fonts.googleapis.com',
            "'unsafe-inline'",
            'use.fontawesome.com',
        ]);

        // IMG 🔥 WAJIB untuk Turnstile
        $policy->add(Directive::IMG, [
            "'self'",
            'data:',
            'challenges.cloudflare.com',
        ]);
    }
}
