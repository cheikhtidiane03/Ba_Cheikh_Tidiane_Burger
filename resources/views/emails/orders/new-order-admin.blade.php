<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle commande</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:#f8fafc; color:#1e293b; }
        .wrapper { max-width:520px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
        .header { background:#1e293b; padding:24px; text-align:center; }
        .header h1 { color:#fff; font-size:18px; font-weight:800; }
        .header p  { color:#94a3b8; font-size:12px; margin-top:4px; }
        .alert { background:#fef3c7; border-bottom:2px solid #f59e0b; padding:14px 20px; display:flex; align-items:center; gap:10px; }
        .alert .icon { font-size:22px; }
        .alert p { font-size:13px; font-weight:600; color:#92400e; }
        .body { padding:24px 20px; }
        .info-grid { display:table; width:100%; margin-bottom:20px; }
        .info-col  { display:table-cell; width:50%; vertical-align:top; padding-right:12px; }
        .info-box  { background:#f8fafc; border-radius:10px; padding:12px 14px; margin-bottom:12px; }
        .info-box .label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:4px; }
        .info-box .value { font-size:14px; font-weight:700; color:#1e293b; }
        .items-table { width:100%; border-collapse:collapse; margin-bottom:16px; }
        .items-table th { font-size:10px; font-weight:700; text-transform:uppercase; color:#94a3b8; padding:6px 8px; text-align:left; background:#f8fafc; }
        .items-table td { padding:8px; font-size:12px; color:#475569; border-bottom:1px solid #f1f5f9; }
        .items-table td:last-child { text-align:right; font-weight:600; }
        .notes-box { background:#fef9ec; border:1px solid #fde68a; padding:10px 12px; border-radius:8px; font-size:12px; color:#78350f; margin-bottom:12px; }
        .footer { background:#f8fafc; border-top:1px solid #e2e8f0; padding:16px 20px; text-align:center; }
        .footer p { font-size:11px; color:#94a3b8; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>ISI BURGER — Gestionnaire</h1>
        <p>Notification automatique</p>
    </div>

    <div class="alert">
        <span class="icon">🔔</span>
        <p>Nouvelle commande reçue </p>
    </div>

    <div class="body">
        <div class="info-grid">
            <div class="info-col">
                <div class="info-box">
                    <p class="label">Référence</p>
                    <p class="value" style="font-family:monospace;color:#2563eb">{{ $order->reference }}</p>
                </div>
                <div class="info-box">
                    <p class="label">Client</p>
                    <p class="value">{{ $order->user->name }}</p>
                </div>
            </div>
            <div class="info-col">
                <div class="info-box">
                    <p class="label">Montant total</p>
                    <p class="value" style="color:#16a34a">{{ $order->formatted_total }}</p>
                </div>
                <div class="info-box">
                    <p class="label">Heure</p>
                    <p class="value">{{ $order->created_at->format('H:i') }}</p>
                </div>
            </div>
        </div>

        <p style="font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;">Articles commandés</p>
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
                    <td>{{ $item->product->name }}</td>
                    <td style="text-align:center">× {{ $item->quantity }}</td>
                    <td>{{ $item->formatted_subtotal }}</td>
                </tr>
                @endforeach
                <tr style="background:#eff6ff;">
                    <td colspan="2" style="font-weight:700;color:#2563eb;padding:10px 8px;">Total</td>
                    <td style="font-weight:800;color:#2563eb;text-align:right;padding:10px 8px;">{{ $order->formatted_total }}</td>
                </tr>
            </tbody>
        </table>

        @if($order->notes)
        <div class="notes-box">
            📝 <strong>Notes du client :</strong> {{ $order->notes }}
        </div>
        @endif
    </div>

    <div class="footer">
        <p>ISI BURGER © {{ date('Y') }} — Notification automatique</p>
    </div>

</div>
</body>
</html>