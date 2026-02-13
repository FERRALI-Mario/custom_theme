<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Paiement\PaymentController;

/**
 * Gère le module de paiement.
 * Pour l'activer, décommentez-le dans app/Core/Theme.php
 */
class PaiementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Ce provider ne charge le contrôleur que si la classe existe.
        // Cela évite une erreur fatale si les fichiers du module sont supprimés sans mettre à jour la liste des providers.
        $paymentControllerPath = get_template_directory() . '/app/Paiement/PaymentController.php';
        if (file_exists($paymentControllerPath)) {
            require_once $paymentControllerPath;
        }
    }

    public function routes(): array
    {
        // Ne déclare les routes que si le contrôleur a bien été chargé.
        return class_exists(PaymentController::class) ? [
            'paiement' => [PaymentController::class, 'viewPayment'],
            'success'  => [PaymentController::class, 'viewSuccess'],
        ] : [];
    }
}
