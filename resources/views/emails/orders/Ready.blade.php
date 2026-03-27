<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f8fafc; color:#1e293b; }
        .wrapper { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
        .header { background:linear-gradient(135deg, #059669, #047857); padding:32px 24px; text-align:center; }
        .header .logo { font-size:40px; margin-bottom:8px; }
        .header h1 { color:#fff; font-size:22px; font-weight:800; }
        .header p  { color:#a7f3d0; font-size:13px; margin-top:4px; }
        .body { padding:28px 24px; }
        .ready-badge { background:#f0fdf4; border:2px solid #86efac; border-radius:14px; padding:18px 20px; text-align:center; margin-bottom:24px; }
        .ready-badge .icon { font-size:32px; margin-bottom:8px; }
        .ready-badge h2 { font-size:17px; font-weight:800; color:#15803d; }
        .ready-badge p { font-size:13px; color:#4ade80; margin-top:4px; }
        .ref { font-family:monospace; font-size:15px; font-weight:700; color:#2563eb; background:#eff6ff; border:1.5px solid #bfdbfe; padding:6px 14px; border-radius:8px; display:inline-block; margin-bottom:20px; }
        .items-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        .items-table th { font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;padding:8px 10px;text-align:left;background:#f8fafc;border-bottom:1px solid #e2e8f0; }
        .items-table td { padding:10px;font-size:13px;color:#475569;border-bottom:1px solid #f1f5f9; }
        .items-table td:last-child { text-align:right;font-weight:600;color:#1e293b; }
        .total-row td { font-weight:800;color:#059669;font-size:15px;border:none;background:#f0fdf4;padding:12px 10px; }
        .pdf-note { background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:10px;padding:14px 16px;margin-bottom:16px;font-size:13px;color:#2563eb; }
        .footer { background:#f8fafc;border-top:1px solid #e2e8f0;padding:20px;text-align:center; }
        .footer p { font-size:12px;color:#94a3b8; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">🍔</div>
        <h1>ISI BURGER</h1>
        <p>Votre commande est prête !</p>
    </div>

    <div class="body">
        <div class="ready-badge">
            <div class="icon">✅</div>
            <h2>Commande prête !</h2>
            <p>Votre commande a été préparée avec soin</p>
        </div>

        <p style="font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin-bottom:6px;">Référence</p>
        <div class="ref">{{ $order->reference }}</div>

        <p style="font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;">Votre commande</p>
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
                    <td colspan="2">Total à payer</td>
                    <td>{{ $order->formatted_total }}</td>
                </tr>
            </tbody>
        </table>

        <div class="pdf-note">
            📎 Votre <strong>facture PDF</strong> est jointe à cet email.
        </div>
    </div>

    <div class="footer">
        <p>Merci pour votre confiance ! 🙏<br>
        <strong>ISI BURGER</strong> — Burgers artisanaux</p>
    </div>
</div>
</body>
</html>