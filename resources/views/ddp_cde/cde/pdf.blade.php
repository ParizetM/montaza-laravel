<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cde->code }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            margin-bottom: 60px;
            /* Espace pour le footer */
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .title {
            margin-bottom: 10px;
        }

        .details {
            margin-bottom: 20px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table td {
            padding: 5px;
        }

        .details table td:first-child {
            width: 20%;
        }

        .main-content {
            margin-bottom: 20px;
        }

        .main-content table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
            border: 1px solid #f0f0f0;

        }

        .main-content table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .main-content table th,
        .main-content table td {
            border-left: 1px solid #f0f0f0;
            border-right: 1px solid #f0f0f0;
            text-align: right;
            padding-right: 10px;
        }

        .main-content table th {
            background-color: #f0f0f0;
            text-align: center;
        }

        #footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: auto;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #f0f0f0;
            padding-top: 10px;
            background: white;
            padding-bottom: 30px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            /* margin-left: 50px; */
            text-align: left;
        }

        .company-info {
            display: inline-block;
            vertical-align: top;
            width: 48%;
            box-sizing: border-box;
            font-size: 12px;
            line-height: 1.5;
        }

        .left {
            text-align: left;
        }

        .right {

            text-align: left;
            float: right;
        }

        .company_info {
            border: 2px solid #000;
            padding: 5px;
            margin-bottom: 10px;
        }

        .poste {
            writing-mode: vertical-rl;
            /* Texte vertical de droite à gauche */
            white-space: nowrap;
            /* Empêche le texte de passer à la ligne */
            transform: rotate(-90deg);
            /* Rotation de 180 degrés */
            height: 35px;
            padding-top: 10px;
            margin-bottom: -5px;
        }

        .poste_case {
            width: 1px;
            padding: 0;
            text-align: center !important;
        }

        .reference-container {
            padding-left: 10px;
            display: flex;
            flex-direction: column;
        }

        .reference-item {
            display: flex;
            justify-content: space-between;
            text-align: left;
        }

        .reference-label {
            font-size: smaller;
            text-align: left;

        }

        .reference-value {
            text-align: left;
        }

        .hidden {
            display: none;
        }

        .footer {
            border-top: 1px solid #f0f0f0;
            margin: 0 10px;
        }

        .table_recap {
            width: 100%;
            border-collapse: collapse;
            position: absolute;
            bottom: 200px;
            left: 0;
            right: 0;
        }

        .table_recap th {
            background-color: #f0f0f0;
            text-align: center;
            padding: 5px;
            font-size: larger;
        }

        .table_recap td {
            padding: 5px;
            vertical-align: top;
        }

        .entreprise_nom {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="footer" class="footer">
        {{ strtoupper($entite->name) }} - {{ $entite->adresse }} - {{ $entite->code_postal }} - {{ $entite->ville }} -
        FRANCE <br>
        Téléphone : {{ $entite->tel }}
    </div>
    <div class="container">
        <!-- Header -->
        <div class="">
            <img src="{{ public_path($entite->logo) }}" alt="Logo" style="width: 30%; margin-bottom: 20px;">
            <div style="float: right; text-align: left; width: 48%;">
                <h2 style="margin-bottom: 0px;">Commande d'achat {{ $cde->code }} </h2>
                <p style="margin-top: 0px;">Le {{ Carbon\Carbon::parse($cde->created_at)->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="header">
            <div class="company-info left">
                <strong>{{ strtoupper($entite->name) }}</strong><br>
                {{ $entite->adresse }}<br>
                {{ $entite->code_postal }} - {{ $entite->ville }}<br>
                FRANCE
            </div>
            <div class="company-info right">
                <div class="company_info">
                    <strong class="entreprise_nom">{{ $etablissement->societe->raison_sociale }}</strong><br>
                    {{ $etablissement->adresse }}<br>
                    {{ $etablissement->code_postal }} &nbsp;{{ $etablissement->ville }}<br>
                    {{ $etablissement->pays->nom }}<br>
                </div>
                @if ($afficher_destinataire)
                    À l'attention de : {{ $contact->nom }}<br>
                    {{ $contact->email }}
                @endif
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            @if ($cde->affaire_numero != null)
                <strong>Affaire n°</strong> : {{ $cde->affaire_numero }}
            @else
                <br>
            @endif
            @if ($cde->affaire_nom != null)
                <br><strong>Affaire</strong> : {{ $cde->affaire_nom }}
            @else
                <br>
            @endif
            @if ($cde->devis_numero != null)
                <br><strong>Devis n°</strong> : {{ $cde->devis_numero }}
            @else
                <br>
            @endif
            @if ($cde->affaire_suivi_par_id != null && $cde->affaire_suivi_par_id != 0)
                <br><strong>Affaire suivie par</strong> : {{ $cde->affaireSuiviPar->first_name }}
                {{ $cde->affaireSuiviPar->last_name }}
            @else
                <br>
            @endif
            @if ($cde->acheteur_id != null && $cde->acheteur_id != 0)
                <br><strong>Acheteur</strong> : {{ $cde->acheteur->first_name }} {{ $cde->acheteur->last_name }}
            @else
                <br>
            @endif
        </div>

        <!-- Main Content -->
        <div class="main-content">


            <table>
                <thead>
                    <tr>
                        <th style="width: 5px; padding: 0%;padding-top:5px;">
                            <div class="poste">Poste</div>
                        </th>
                        @if ($showRefFournisseur)
                            <th>Références</th>
                        @else
                            <th>Référence</th>
                        @endif
                        <th>Désignation</th>
                        <th>Qté</th>
                        <th>PU HT</th>
                        <th>Total HT</th>
                        <th>Pour le</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lignes as $index => $ligne)
                        <tr style="page-break-inside: avoid; {{-- {{ $index % 2 == 1 ? 'background-color: #f5f5f5;' : '' }} --}}">
                            <td class="poste_case" style="padding: 0%;">{{ $ligne->poste }}</td>
                            <td class="text-left ml-1 p-2">
                                <div class="reference-container {{ $showRefFournisseur ? '' : 'hidden' }}"
                                    id="refs-{{ $ligne->matiere_id }}">
                                    <div class="reference-item">
                                        <span class="reference-label">Réf. Interne</span><br>
                                        <span class="reference-value">{{ $ligne->ref_interne ?? '' }}</span>
                                    </div>
                                    @if ($ligne->ref_fournisseur != null && $ligne->ref_fournisseur != '')
                                        <div class="reference-item">
                                            <span class="reference-label">Réf. Fournisseur</span><br>
                                            <span class="reference-value">{{ $ligne->ref_fournisseur ?? '' }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="reference-container {{ $showRefFournisseur ? 'hidden' : '' }}"
                                    id="ref-{{ $ligne->matiere_id }}">
                                    <div class="reference-item">
                                        <span class="reference-label">Réf. Interne</span><br>
                                        <span class="reference-value">{{ $ligne->ref_interne ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: left; padding-left: 10px;">{{ $ligne->matiere->designation }}</td>
                            <td>{{ formatNumber($ligne->quantite) }} {{ $ligne->unite->short }}</td>
                            <td>{{ formatNumberArgent($ligne->prix_unitaire) }} </td>
                            <td>{{ formatNumberArgent($ligne->prix) }} </td>
                            <td>{{ \Carbon\Carbon::parse($ligne->date_livraison)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <table class="table_recap">
                <thead>
                    <tr>
                        <th style="border-right: 2px solid #f0f0f0 ;">{{ $cde->typeExpedition->nom }}</th>
                        <th colspan="3" style="border-left: 2px solid #f0f0f0 ;">Récapitulatif Financier</th>
                    </tr>
                </thead>
                <tbody>
                    <td style="padding: 10px; border-right: 2px solid #f0f0f0 ;">
                        @if ($cde->typeExpedition->id == 1)
                            @php
                                $adresse = json_decode($cde->adresse_livraison);
                            @endphp
                            <span>{{ $adresse->adresse }}</span>
                            <br><span>{{ $adresse->code_postal }} - {{ $adresse->ville }}</span>
                            <br><span>{{ $adresse->pays }}</span>
                            <br><span>horaires : {{ $adresse->horaires }}</span>
                        @endif
                    </td>
                    <td style=" border-right: 2px solid #f0f0f0 ;">
                        <strong>Condition de paiement :</strong>
                        <br>{{ $cde->conditionPaiement->nom }}
                        @if ($cde->frais_de_port != null && $cde->frais_de_port != 0 && $cde->frais_de_port != '')
                            <br><strong>Frais de port :</strong>
                            <br>{{ formatNumberArgent($cde->frais_de_port) }}
                        @endif
                        @if ($cde->frais_divers != null && $cde->frais_divers != 0 && $cde->frais_divers != '')
                            <br><strong>Frais divers :</strong>
                            @if ($cde->frais_divers_texte != null && $cde->frais_divers_texte != '')
                                <br><strong>- {{ $cde->frais_divers_texte }} :</strong>
                            @endif
                            <br> &nbsp;{{ formatNumberArgent($cde->frais_divers) }}
                        @endif
                    </td>
                    <td style="text-align: left;">
                        <p><strong>Montant net HT :</strong></p>
                        <p><strong>TVA ({{ $cde->tva }}%) :</strong></p>
                        <p><strong>Montant Total TTC :</strong></p>
                    </td>
                    <td style="text-align: right;">
                        <p><strong>{{ formatNumberArgent($total_ht) }} </strong></p>
                        <p><strong>{{ formatNumberArgent($cde->total_ttc - $total_ht) }} </strong></p>
                        <p><strong>{{ formatNumberArgent($cde->total_ttc) }} </strong></p>
                    </td>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->

</body>

</html>
