<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis {{ $devis->reference_projet ?? $devis->id }}</title>
    <style>
        @page { margin: 0cm; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 2cm; }
        .header { width: 100%; margin-bottom: 30px; }
        .logo { width: 200px; float: left; }
        .company-info { float: right; text-align: right; }
        .client-info { margin-top: 20px; border: 1px solid #ddd; padding: 10px; width: 45%; float: right; }
        .devis-info { margin-top: 20px; width: 50%; float: left; }
        .clear { clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total-section { margin-top: 20px; float: right; width: 40%; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; height: 50px; text-align: center; font-size: 10px; border-top: 1px solid #ddd; padding-top: 10px; }
        .section-title { font-weight: bold; background-color: #e9e9e9; }
    </style>
</head>
<body>
    <div class="header">
        <div class="" style="margin-bottom: 20px;">
            @if(isset($entite) && $entite->logo)
                <img src="{{ public_path($entite->logo) }}" alt="Logo" style="width: 200px;">
            @else
                <div class="logo">LOGO</div>
            @endif
        </div>

        <div class="devis-info">
            <h1>DEVIS</h1>
            <p><strong>Référence :</strong> {{ $devis->reference_projet ?? 'N/A' }}</p>
            <p><strong>Date :</strong> {{ $devis->date_emission ? \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') : date('d/m/Y') }}</p>
            <p><strong>Validité :</strong> {{ $devis->duree_validite }} jours</p>
            <p><strong>Lieu :</strong> {{ $devis->lieu_intervention ?? 'N/A' }}</p>
        </div>
        <div class="client-info">
            <strong>CLIENT</strong><br>
            {{ $devis->client_nom }}<br>
            @if($devis->client_contact) Attn: {{ $devis->client_contact }}<br> @endif
            {!! nl2br(e($devis->client_adresse)) !!}
        </div>
        <div class="clear"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix Unitaire</th>
                <th class="text-right">Total HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devis->sections as $section)
                <tr class="section-title">
                    <td colspan="4">{{ $section->titre }}</td>
                </tr>
                @foreach($section->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->designation }}</td>
                        <td class="text-right">{{ $ligne->quantite }} {{ $ligne->unite }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} €</td>
                        <td class="text-right">{{ number_format($ligne->total_ht, 2, ',', ' ') }} €</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><strong>Sous-total {{ $section->titre }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($section->lignes->sum('total_ht'), 2, ',', ' ') }} €</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td><strong>Total HT</strong></td>
                <td class="text-right">{{ number_format($devis->sections->flatMap->lignes->sum('total_ht'), 2, ',', ' ') }} €</td>
            </tr>
            {{-- Ajoutez ici TVA et TTC si nécessaire --}}
        </table>
    </div>

    <div class="clear"></div>

    <div style="margin-top: 40px;">
        <p><strong>Conditions de paiement :</strong> {{ $devis->conditions_paiement ?? 'Non spécifié' }}</p>
        <p><strong>Délais d'exécution :</strong> {{ $devis->delais_execution ?? 'Non spécifié' }}</p>
    </div>

    <div class="footer">
        @if(isset($entite))
            {{ strtoupper($entite->name) }} - {{ $entite->adresse }} - {{ $entite->code_postal }} - {{ $entite->ville }} - FRANCE <br>
            Téléphone : {{ $entite->tel }} <br>
        @endif
        Document généré le {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
