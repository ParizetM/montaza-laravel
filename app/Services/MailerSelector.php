<?php

namespace App\Services;

class MailerSelector
{
    /**
     * Sélectionne le mailer en fonction de l'adresse email de l'utilisateur
     */
    public static function selectMailer(string $email): string
    {
        $domain = strtolower(explode('@', $email)[1] ?? '');

        // Définir les règles de sélection par domaine
        $domainRules = [
            'agfagoofay.fr' => 'smtp',           // Mailer principal
            'example.com' => 'secondary',       // Mailer secondaire
            'third.com' => 'tertiary',          // Mailer tertiaire
        ];

        return $domainRules[$domain] ?? 'smtp'; // Par défaut, utiliser smtp
    }

    /**
     * Retourne tous les mailers disponibles
     */
    public static function getAvailableMailers(): array
    {
        return ['smtp', 'secondary', 'tertiary'];
    }
}
