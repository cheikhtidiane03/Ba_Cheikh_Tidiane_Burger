<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:DejaVu Sans,sans-serif; color:#1e293b; font-size:12px; }
        .page { padding:40px; }

        /* Header */
        .header { margin-bottom:30px; }
        .header-left  { float:left; }
        .header-right { float:right; text-align:right; }
        .clearfix::after { content:''; display:table; clear:both; }
        .logo-text { font-size:22px; font-weight:900; color:#2563eb; }
        .logo-sub  { font-size:10px; color:#94a3b8; margin-top:2px; }
        .invoice-title { font-size:28px; font-weight:900; color:#1e293b; }
        .invoice-ref   { font-size:13px; color:#2563eb; font-weight:700; font-family:monospace; margin-top:4px; }
        .invoice-date  { font-size:10px; color:#94a3b8; margin-top:4px; }

        .divider { height:2px; background:#e2e8f0; margin:20px 0; clear:both; }

        /* Infos */
        .info-section { margin-bottom:24px; }
        .info-section::after { content:''; display:table; clear:both; }
        .info-left  { float:left;  width:50%; }
        .info-right { float:right; width:50%; text-align:right; }
        .info-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:4px; }
        .info-name  { font-size:14px; font-weight:800; color:#1e293b; }
        .info-sub   { font-size:11px; color:#64748b; margin-top:2px; }

        /* Table */
        .items-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        .items-table thead tr { background:#1e293b; }
        .items-table thead th { color:#fff; font-size:10px; font-weight:700; text-transform:uppercase;
                                 letter-spacing:.06em; padding:10px 12px; text-align:left; }
        .items-table thead th:last-child { text-align:right; }
        .items-table tbody tr:nth-child(even) { background:#f8fafc; }
        .items-table tbody td { padding:10px 12px; font-size:12px; color:#475569; border-bottom:1px solid #f1f5f9; }
        .items-table tbody td:last-child { text-align:right; font-weight:600; color:#1e293b; }

        /* Total */
        .total-section { float:right; width:220px; margin-top:4px; }
        .total-main { background:#eff6ff; border-radius:8px; padding:10px 12px; }
        .total-main-label { font-size:14px; font-weight:800; color:#2563eb; float:left; }
        .total-main-value { font-size:14px; font-weight:800; color:#2563eb; float:right; }
        .total-main::after { content:''; display:table; clear:both; }

        /* Notes */
        .notes-section { clear:both; margin-top:32px; background:#fef9ec;
                         border:1px solid #fde68a; border-radius:8px; padding:12px 14px; }
        .notes-title { font-size:10px; font-weight:700; text-transform:uppercase; color:#92400e; margin-bottom:4px; }
        .notes-text  { font-size:11px; color:#78350f; }

        /* Footer */
        .footer { margin-top:40px; border-top:1px solid #e2e8f0; padding-top:12px; text-align:center; }
        .footer p { font-size:9px; color:#94a3b8; }

        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; }
        .badge-ready { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
        .badge-paid  { background:#f0fdf4; color:#15803d; border:1px solid #86efac; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header clearfix">
        <div class="header-left">
            <div style="font-size:28px;margin-bottom:4px;">🍔</div>
            <div class="logo-text">ISI BURGER</div>
            <div class="logo-sub">Restaurant de burgers artisanaux</div>
        </div>
        <div class="header-right">
            <div class="invoice-title">FACTURE</div>
            <div class="invoice-ref">{{ $order->reference }}</div>
            <div class="invoice-date">Émise le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</div>
            <div style="margin-top:8px">
                <span class="badge {{ $order->isPaid() ? 'badge-paid' : 'badge-ready' }}">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Infos client --}}
    <div class="info-section">
        <div class="info-left">
            <div class="info-label">Facturé à</div>
            <div class="info-name">{{ $order->user->name }}</div>
            <div class="info-sub">{{ $order->user->email }}</div>
        </div>
        <div class="info-right">
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
                <th style="text-align:right">Prix unit.</th>
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

    {{-- Total --}}
    <div class="total-section">
        <div class="total-main">
            <span class="total-main-label">Total</span>
            <span class="total-main-value">{{ $order->formatted_total }}</span>
        </div>
    </div>

    {{-- Notes --}}
    @if($order->notes)
    <div class="notes-section">
        <p class="notes-title">Notes</p>
        <p class="notes-text">{{ $order->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>ISI BURGER — Burgers artisanaux | Merci pour votre confiance ! | Facture générée automatiquement.</p>
    </div>

</div>
</body>
</html>