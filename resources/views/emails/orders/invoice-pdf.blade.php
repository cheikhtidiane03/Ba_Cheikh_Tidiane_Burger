<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; color:#1e293b; font-size:12px; }

        .page { padding:40px; }

        /* Header */
        .header { display:table; width:100%; margin-bottom:36px; }
        .header-left  { display:table-cell; vertical-align:top; width:50%; }
        .header-right { display:table-cell; vertical-align:top; text-align:right; width:50%; }
        .logo-block .logo-icon { font-size:28px; }
        .logo-block .logo-text { font-size:22px; font-weight:900; color:#2563eb; margin-top:2px; }
        .logo-block .logo-sub  { font-size:10px; color:#94a3b8; margin-top:2px; }
        .invoice-title { font-size:28px; font-weight:900; color:#1e293b; }
        .invoice-ref   { font-size:13px; color:#2563eb; font-weight:700; font-family:monospace; margin-top:4px; }
        .invoice-date  { font-size:10px; color:#94a3b8; margin-top:4px; }

        /* Divider */
        .divider { height:2px; background:#e2e8f0; margin:20px 0; }

        /* Info boxes */
        .info-row { display:table; width:100%; margin-bottom:28px; }
        .info-box  { display:table-cell; width:50%; vertical-align:top; }
        .info-box.right { text-align:right; }
        .info-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#94a3b8; margin-bottom:5px; }
        .info-name  { font-size:14px; font-weight:800; color:#1e293b; }
        .info-sub   { font-size:11px; color:#64748b; margin-top:2px; }

        /* Table */
        .items-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        .items-table thead tr { background:#1e293b; }
        .items-table thead th { color:#fff; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; padding:10px 12px; text-align:left; }
        .items-table thead th:last-child { text-align:right; }
        .items-table tbody tr:nth-child(even) { background:#f8fafc; }
        .items-table tbody td { padding:10px 12px; font-size:12px; color:#475569; border-bottom:1px solid #f1f5f9; }
        .items-table tbody td:last-child { text-align:right; font-weight:600; color:#1e293b; }

        /* Totaux */
        .totals { float:right; width:220px; margin-top:4px; }
        .total-row-sub { display:table; width:100%; padding:5px 0; border-bottom:1px solid #f1f5f9; }
        .total-label { display:table-cell; font-size:11px; color:#64748b; }
        .total-value { display:table-cell; font-size:11px; color:#1e293b; text-align:right; font-weight:600; }
        .total-row-main { display:table; width:100%; background:#eff6ff; border-radius:8px; padding:10px 12px; margin-top:8px; }
        .total-main-label { display:table-cell; font-size:14px; font-weight:800; color:#2563eb; }
        .total-main-value { display:table-cell; font-size:14px; font-weight:800; color:#2563eb; text-align:right; }

        /* Footer */
        .footer { position:fixed; bottom:20px; left:40px; right:40px; border-top:1px solid #e2e8f0; padding-top:10px; }
        .footer p { font-size:9px; color:#94a3b8; text-align:center; }

        /* Status badge */
        .status-badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:10px; font-weight:700; }
        .status-paid { background:#f0fdf4; color:#15803d; border:1px solid #86efac; }
        .status-ready { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <div class="logo-block">
                <div class="logo-icon"></div>
                <div class="logo-text">ISI BURGER</div>
                <div class="logo-sub">Restaurant de burgers artisanaux</div>
            </div>
        </div>
        <div class="header-right">
            <div class="invoice-title">FACTURE</div>
            <div class="invoice-ref">{{ $order->reference }}</div>
            <div class="invoice-date">Émise le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</div>
            <div style="margin-top:8px">
                <span class="status-badge {{ $order->isPaid() ? 'status-paid' : 'status-ready' }}">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Infos client --}}
    <div class="info-row">
        <div class="info-box">
            <div class="info-label">Facturé à</div>
            <div class="info-name">{{ $order->user->name }}</div>
            <div class="info-sub">{{ $order->user->email }}</div>
        </div>
        <div class="info-box right">
            <div class="info-label">Date de commande</div>
            <div class="info-name">{{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY') }}</div>
            <div class="info-sub">{{ $order->created_at->format('H:i') }}</div>
        </div>
    </div>

    {{-- Articles --}}
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th style="text-align:center">Quantité</th>
                <th style="text-align:right">Prix unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td style="color:#94a3b8;font-size:11px">{{ $i + 1 }}</td>
                <td style="font-weight:600;color:#1e293b">{{ $item->product->name }}</td>
                <td style="text-align:center">{{ $item->quantity }}</td>
                <td style="text-align:right">{{ $item->formatted_unit_price }}</td>
                <td>{{ $item->formatted_subtotal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totaux --}}
    <div class="totals">
        <div class="total-row-main">
            <span class="total-main-label">Total</span>
            <span class="total-main-value">{{ $order->formatted_total }}</span>
        </div>
    </div>

    {{-- Notes --}}
    @if($order->notes)
    <div style="clear:both; margin-top:32px; background:#fef9ec; border:1px solid #fde68a; border-radius:8px; padding:12px 14px;">
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;color:#92400e;margin-bottom:4px;">Notes</p>
        <p style="font-size:11px;color:#78350f;">{{ $order->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>ISI BURGER — Burgers artisanaux | Merci pour votre confiance .</p>
    </div>

</div>
</body>
</html>