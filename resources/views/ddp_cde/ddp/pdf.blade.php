<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            margin-bottom: 60px; /* Espace pour le footer */
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
        }

        .main-content table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .main-content table th,
        .main-content table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .main-content table th {
            background-color: #f0f0f0;
        }

        #footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            background: white;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            margin-left: 50px;
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

    </style>
</head>

<body>
    <div id="footer">
        ATLANTIS MONTAZA - 1 Ter Rue de la Cité Nouvelle - ZI Altitude - 44570 - TRIGNAC - FRANCE <br>
         Téléphone : 02 40 17 65 62
    </div>
    <div class="container">
        <!-- Header -->
        <img src="{{ public_path('img/logo-long.png') }}" alt="Logo" style="width: 30%; margin-bottom: 20px; margin-left:-2%;">
        <div class="header">
            <div class="company-info left">
                <strong>ATLANTIS MONTAZA</strong><br>
                1 Ter Rue de la Cité Nouvelle<br>
                ZI Altitude<br>
                44570 TRIGNAC<br>
                FRANCE
            </div>
            <div class="company-info right">
                <strong>{{ $etablissement->societe->raison_sociale }}</strong><br>
                {{ $etablissement->adresse }}<br>
                {{ $etablissement->code_postal }} {{ $etablissement->ville }}<br>
                {{ $etablissement->pays->nom }}<br>
                @if ($afficher_destinataire)
                {{ $destinataire }}
                @endif
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            @if ($ddp->dossier_suivi_par_id != 0 && $ddp->dossier_suivi_par_id != null)
                <strong>Dossier suivi par :</strong> {{ $ddp->dossierSuiviPar->first_name }}
                {{ $ddp->dossierSuiviPar->last_name }}<br>
            @endif
            <strong>Ref : </strong> {{ $ddp->code }}<br>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <p>
                Madame, Monsieur,<br>
                Veuillez nous faire parvenir votre offre de prix concernant les éléments indiqués ci-dessous.
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Référence interne</th>
                        <th>Désignation</th>
                        <th>Qté</th>
                        <th>Unité</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->matiere->ref_interne }}</td>
                            <td>{{ $ligne->matiere->designation }}</td>
                            <td>{{ $ligne->quantite }}</td>
                            <td>{{ $ligne->matiere->unite->full }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->

</body>

</html>
