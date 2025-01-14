<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            display: grid;
            margin-bottom: 30px;
        }


        .address-box {
            border: 1px solid #999;
            padding: 10px;
            margin-bottom: 10px;
            max-width: 250px;
        }

        .consultation-info {
            display: flex;
            margin: 20px 0;
        }

        .consultation-box {
            border: 1px solid #000;
            padding: 5px 15px;
            display: inline-block;
        }

        .attention-box {
            border: 1px solid #999;
            padding: 5px 15px;
            float: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #666;
            color: white;
            padding: 5px;
            text-align: left;
        }

        td {
            border: 1px solid #999;
            padding: 5px;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #666;
        }

    </style>
</head>
<body>
    <div class="header">
        <div>
            <img src="{{ public_path('img/Logo-long.png') }}" alt="Logo">


            <div class="address-box">
                1 Ter Rue de la Cité Nouvelle<br>
                ZI Altitude<br>
                44570 TRIGNAC<br>
                FRANCE
            </div>
        </div>
        <div>
            <div class="logo">ATLANTIC ROBINETTERIE</div>
            <div class="address-box">
                71 RUE HENRI GAUTIER<br>
                BATIMENT G<br>
                44550 - MONTOIR DE BRETAGNE<br>
                FRANCE
            </div>
        </div>
    </div>

    <div>À l'attention de : VELIA LAURIANE</div>

    <div class="consultation-info">
        <div>
            Consultation n° <span class="consultation-box">1</span>
            Envoi <span class="consultation-box">1</span>
        </div>
        <div>Ref : </div>
        <div>du : 13/01/2025</div>
    </div>

    <div class="attention-box">
        Attention : votre réponse est demandée pour, au plus tard, le
    </div>

    <div style="clear: both; margin: 20px 0;">
        Madame, Monsieur,<br><br>
        Veuillez nous faire parvenir votre offre de prix concernant les éléments indiqués ci-dessous.
    </div>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Désignation</th>
                <th>Nb lot</th>
                <th>Qté</th>
                <th>Lg</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>SA000122</td>
                <td>3 pieces Male Inox 25 25</td>
                <td>1</td>
                <td>5</td>
                <td>1 U</td>
                <td>5 U</td>
            </tr>
            <tr>
                <td>SA000385</td>
                <td>ROBINET TOURNANT<br>SPHERIQUE ACIER 15<br>EP2.5 2A10301S</td>
                <td>1</td>
                <td>8</td>
                <td>1 U</td>
                <td>8 U</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Powered by TopSolid'Erp(c) - ATLANTIS MONTAZA • 1 Ter Rue de la Cité Nouvelle - ZI Altitude - 44570 - TRIGNAC - FRANCE - Page 1 sur 1
    </div>
</body>
</html>
