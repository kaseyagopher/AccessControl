<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;

class NotificationService
{
    /** Notifie tous les agents de sécurité */
    public static function notifierSecurite(string $message, ?string $type = null): void
    {
        User::where('role', 'agent-de-security')->each(function (User $user) use ($message, $type) {
            UserNotification::envoyer($user->id, $message, $type);
        });
    }

    /** Notifie un superviseur */
    public static function notifierSuperviseur(int $superviseurId, string $message, ?string $type = null): void
    {
        UserNotification::envoyer($superviseurId, $message, $type);
    }
}
