<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:#f8fafc; color:#1e293b; }
        .wrapper { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
        .header { background:linear-gradient(135deg,#2563eb,#1d4ed8); padding:32px 24px; text-align:center; }
        .header .logo { font-size:36px; margin-bottom:8px; }
        .header h1  { color:#fff; font-size:22px; font-weight:800; margin-bottom:4px; }
        .header p   { color:#bfdbfe; font-size:13px; }
        .body { padding:28px 24px; }
        .greeting { font-size:15px; margin-bottom:20px; color:#475569; line-height:1.6; }
        .greeting strong { color:#1e293b; }
        .ref-badge { display:inline-block; background:#eff6ff; border:1.5px solid #bfdbfe; color:#2563eb;
                     font-family:monospace; font-size:15px; font-weight:700; padding:8px 16px;
                     border-radius:10px; margin-bottom:20px; }
        .section-title { font-size:11px; font-weight:700; text-transform:uppercase;
                         letter-spacing:.08em; color:#94a3b8; margin-bottom:10px; }
        .items-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        .items-table th { font-size:11px; font-weight:700; text-transform:uppercase; color:#94a3b8;
                          padding:8px 10px; text-align:left; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
        .items-table td { padding:10px; font-size:13px; border-bottom:1px solid #f1f5f9; color:#475569; }
        .items-table td:last-child { text-align:right; font-weight:600; color:#1e293b; }
        .total-row { background:#eff6ff; }
        .total-row td { font-weight:800; color:#2563eb; font-size:15px; border:none; padding:12px 10px; }
        .status-box { background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px;
                      padding:14px 16px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; }
        .status-box p { font-size:13px; color:#15803d; font-weight:500; line-height:1.5; }
        .notes-box { font-size:13px; color:#64748b; background:#f8fafc; padding:10px 14px;
                     border-radius:8px; margin-bottom:16px; line-height:1.5; }
        .footer { background:#f8fafc; border-top:1px solid #e2e8f0; padding:20px; text-align:center; }
        .footer p { font-size:12px; color:#94a3b8; line-height:1.6; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">🍔</div>
        <h1>ISI BURGER</h1>
        <p>Confirmation de votre commande</p>
    </div>

    <div class="body">
        <p class="greeting">
            Bonjour <strong>{{ $order->user->name }}</strong>,<br>
            Votre commande a bien été reçue et est en cours de traitement. Merci pour votre confiance !
        </p>

        <p class="section-title">Référence de commande</p>
        <div class="ref-badge">{{ $order->reference }}</div>

        <p class="section-title">Détail de la commande</p>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th style="text-align:center">Qté</th>
                    <th style="text-align:right">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>🍔 {{ $item->product->name }}</td>
                    <td style="text-align:center">× {{ $item->quantity }}</td>
                    <td>{{ $item->formatted_subtotal }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td>{{ $order->formatted_total }}</td>
                </tr>
            </tbody>
        </table>

        <div class="status-box">
            <span style="font-size:22px;flex-shrink:0">⏳</span>
            <p>Votre commande est <strong>en attente de préparation</strong>.<br>
            Vous recevrez un email avec votre facture PDF lorsqu'elle sera prête.</p>
        </div>

        @if($order->notes)
        <p class="section-title">Vos notes</p>
        <p class="notes-box">{{ $order->notes }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Merci pour votre confiance ! 🙏<br>
        <strong>ISI BURGER</strong> — Burgers artisanaux<br>
        Cet email a été envoyé automatiquement, merci de ne pas répondre.</p>
    </div>

</div>
</body>
</html>