{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès refusé — GestMatériel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-lock text-red-500 text-3xl"></i>
        </div>
        <h1 class="text-6xl font-bold text-slate-300 mb-2">403</h1>
        <h2 class="text-xl font-semibold text-slate-700 mb-3">Accès refusé</h2>
        <p class="text-slate-500 text-sm mb-6">Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
</body>
</html>