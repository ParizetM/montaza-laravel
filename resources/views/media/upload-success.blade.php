<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléchargement réussi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .success-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .success-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h4 class="mb-3">Téléchargement réussi !</h4>
        <p>Vos documents ont été correctement ajoutés à
            <strong>{{ class_basename($entity) }} #{{ $entity->id }}</strong>
            @if(isset($entity->reference))
            : {{ $entity->reference }}
            @endif
        </p>
        <p class="mt-4 mb-0">Vous pouvez maintenant fermer cette fenêtre.</p>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
