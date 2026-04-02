<?php

namespace App\Services;

use App\Mail\NewOrderAdminMail;
use App\Mail\OrderConfirmedMail;
use App\Mail\OrderReadyMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * Email de confirmation commande → client
     */
    public static function sendOrderConfirmed(Order $order): bool
    {
        return self::send(
            to: $order->user->email,
            mailable: new OrderConfirmedMail($order),
            context: "confirmation commande {$order->reference} → {$order->user->email}"
        );
    }

    /**
     * Notification nouvelle commande → tous les gestionnaires
     */
    public static function sendNewOrderToAdmins(Order $order): void
    {
        $gestionnaires = User::role('gestionnaire')->get();

        if ($gestionnaires->isEmpty()) {
            Log::warning("Aucun gestionnaire trouvé pour la notification commande {$order->reference}");
            return;
        }

        foreach ($gestionnaires as $gestionnaire) {
            self::send(
                to: $gestionnaire->email,
                mailable: new NewOrderAdminMail($order),
                context: "notification admin {$order->reference} → {$gestionnaire->email}"
            );
        }
    }

    /**
     * Email commande prête + facture PDF → client
     */
    public static function sendOrderReady(Order $order): bool
    {
        return self::send(
            to: $order->user->email,
            mailable: new OrderReadyMail($order),
            context: "commande prête {$order->reference} + PDF → {$order->user->email}"
        );
    }

    /**
     * Méthode générique d'envoi avec log
     */
    private static function send(string $to, object $mailable, string $context): bool
    {
        try {
            Mail::to($to)->send($mailable);

            Log::info("✅ Email envoyé [{$context}]", [
                'env'  => config('app.env'),
                'host' => config('mail.mailers.smtp.host'),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("❌ Échec email [{$context}] : " . $e->getMessage(), [
                'env'  => config('app.env'),
                'host' => config('mail.mailers.smtp.host'),
                'to'   => $to,
            ]);

            return false;
        }
    }
}