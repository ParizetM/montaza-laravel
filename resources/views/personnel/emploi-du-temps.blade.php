<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Emploi du temps - {{ $personnel->prenom }} {{ $personnel->nom }}
            </h2>
            <a href="{{ route('personnel.show', $personnel) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                Retour au profil
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Affaires assignées</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['affaires'] }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tâches totales</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['taches_totales'] }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tâches en cours</div>
                    <div class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $stats['taches_en_cours'] }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tâches terminées</div>
                    <div class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ $stats['taches_terminees'] }}</div>
                </div>
            </div>

            <!-- Navigation hebdomadaire -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- En-tête avec navigation -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-300 dark:border-gray-600">
                        <div class="flex items-center gap-4">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                                Semaine du {{ $startOfWeek->locale('fr')->isoFormat('D MMMM') }} au {{ $endOfWeek->locale('fr')->isoFormat('D MMMM YYYY') }}
                            </h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('personnel.emploi_du_temps', ['personnel' => $personnel, 'week' => 0]) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium">
                                Cette semaine
                            </a>
                            <a href="{{ route('personnel.emploi_du_temps', ['personnel' => $personnel, 'week' => $weekOffset - 1]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                ←
                            </a>
                            <a href="{{ route('personnel.emploi_du_temps', ['personnel' => $personnel, 'week' => $weekOffset + 1]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                →
                            </a>
                        </div>
                    </div>

                    <!-- Emploi du temps -->
                    <div class="border-2 border-gray-400 dark:border-gray-600">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr>
                                    <th class="bg-teal-700 text-white px-3 py-3 text-center text-xs font-bold uppercase border-r-2 border-teal-600 w-20">
                                        Heure
                                    </th>
                                    @foreach($weekDays as $day)
                                        <th class="bg-teal-700 text-white px-3 py-3 text-center text-xs font-bold uppercase border-r-2 border-teal-600 last:border-r-0">
                                            <div>{{ $day->locale('fr')->isoFormat('ddd') }}</div>
                                            <div class="text-sm font-normal mt-1">{{ $day->format('d/m') }}</div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hours as $hour)
                                    <tr class="border-t-2 border-gray-400 dark:border-gray-600">
                                        <td class="bg-gray-100 dark:bg-gray-900 text-center font-semibold text-sm text-gray-700 dark:text-gray-300 border-r-2 border-gray-400 dark:border-gray-600 py-2">
                                            {{ sprintf('%02d:00', $hour) }}
                                        </td>
                                        @foreach($weekDays as $day)
                                            @php
                                                $dateKey = $day->format('Y-m-d');
                                                $timeKey = $dateKey . '-' . $hour;
                                                $events = $evenementsByDateTime[$timeKey] ?? [];
                                                $isToday = $day->isToday();
                                            @endphp
                                            <td class="border-r-2 border-gray-400 dark:border-gray-600 last:border-r-0 p-1 {{ $isToday ? 'bg-blue-50 dark:bg-blue-950' : 'bg-white dark:bg-gray-800' }} min-h-[60px] align-top relative">
                                                @foreach($events as $event)
                                                    @if($event['type'] === 'absence')
                                                        @php
                                                            $absenceStyles = [
                                                                'absence' => ['bg' => '#f97316', 'border' => '#ea580c'],
                                                                'maladie' => ['bg' => '#a855f7', 'border' => '#9333ea'],
                                                                'retard'  => ['bg' => '#eab308', 'border' => '#ca8a04'],
                                                            ];
                                                            $absStyle = $absenceStyles[$event['absence_type']] ?? $absenceStyles['absence'];
                                                            $heightInPx = ($event['duree'] * 60) - 8;
                                                            $statutIcon = match($event['statut']) {
                                                                'justifie'     => '✓',
                                                                'non_justifie' => '✗',
                                                                default        => '?',
                                                            };
                                                            $tooltip = $event['titre'];
                                                            if ($event['absence_type'] === 'retard' && isset($event['minutes_retard'])) {
                                                                $h = intdiv((int)$event['minutes_retard'], 60);
                                                                $m = (int)$event['minutes_retard'] % 60;
                                                                $tooltip .= ' ' . ($h > 0 ? $h.'h' : '') . $m.'min';
                                                            }
                                                            if ($event['motif']) $tooltip .= ' - ' . $event['motif'];
                                                        @endphp
                                                        <div class="absolute left-1 right-1 text-white rounded p-1 text-xs shadow-lg z-10 cursor-default"
                                                             style="background-color: {{ $absStyle['bg'] }}; border-left: 4px solid {{ $absStyle['border'] }}; height: {{ $heightInPx }}px; top: 4px;"
                                                             title="{{ $tooltip }}">
                                                            <div class="flex items-center justify-center h-full gap-1">
                                                                <span class="font-bold">{{ $statutIcon }}</span>
                                                                <span class="font-semibold truncate">{{ $event['titre'] }}</span>
                                                            </div>
                                                        </div>
                                                    @elseif($event['type'] === 'conge')
                                                        @php
                                                            // Couleurs pour les congés selon le type
                                                            $congeColors = [
                                                                'conge_paye' => ['bg' => '#94a3b8', 'border' => '#64748b', 'label' => '🏖️'],
                                                                'conge_maladie' => ['bg' => '#f87171', 'border' => '#dc2626', 'label' => '🏥'],
                                                                'conge_sans_solde' => ['bg' => '#a1a1aa', 'border' => '#71717a', 'label' => '📅'],
                                                                'autre' => ['bg' => '#a78bfa', 'border' => '#7c3aed', 'label' => '📝'],
                                                            ];

                                                            $congeColor = $congeColors[$event['conge_type']] ?? $congeColors['autre'];
                                                            $heightInPx = ($event['duree'] * 60) - 8;
                                                            $heureDebutFormatee = sprintf('%02d:00', $event['heure_debut']);
                                                            $heureFinFormatee = sprintf('%02d:00', $event['heure_fin']);
                                                        @endphp
                                                        <div class="absolute left-1 right-1 text-white rounded p-2 text-xs shadow-lg z-10 cursor-default"
                                                             style="background: repeating-linear-gradient(45deg, {{ $congeColor['bg'] }}, {{ $congeColor['bg'] }} 10px, {{ $congeColor['border'] }} 10px, {{ $congeColor['border'] }} 20px); border-left: 4px solid {{ $congeColor['border'] }}; height: {{ $heightInPx }}px; top: 4px;"
                                                             title="{{ $event['titre'] }} ({{ $heureDebutFormatee }} - {{ $heureFinFormatee }}){{ $event['motif'] ? ' - ' . $event['motif'] : '' }}">
                                                            <div class="flex items-center justify-center h-full">
                                                                <div class="font-bold">{{ $event['titre'] }}</div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @php
                                                            // Palette de couleurs vives (couleurs hex)
                                                            $colors = [
                                                                ['bg' => '#2563eb', 'border' => '#1d4ed8'], // blue
                                                                ['bg' => '#9333ea', 'border' => '#7e22ce'], // purple
                                                                ['bg' => '#db2777', 'border' => '#be185d'], // pink
                                                                ['bg' => '#4f46e5', 'border' => '#4338ca'], // indigo
                                                                ['bg' => '#0891b2', 'border' => '#0e7490'], // cyan
                                                                ['bg' => '#0d9488', 'border' => '#0f766e'], // teal
                                                                ['bg' => '#059669', 'border' => '#047857'], // emerald
                                                                ['bg' => '#ea580c', 'border' => '#c2410c'], // orange
                                                                ['bg' => '#d97706', 'border' => '#b45309'], // amber
                                                                ['bg' => '#e11d48', 'border' => '#be123c'], // rose
                                                                ['bg' => '#7c3aed', 'border' => '#6d28d9'], // violet
                                                                ['bg' => '#c026d3', 'border' => '#a21caf'], // fuchsia
                                                            ];

                                                            // Utiliser l'ID de l'affaire pour choisir une couleur cohérente
                                                            $colorIndex = $event['affaire_id'] % count($colors);
                                                            $color = $colors[$colorIndex];

                                                            $bgColorHex = $color['bg'];
                                                            $borderColorHex = $color['border'];
                                                            $icon = '◻';

                                                            if ($event['statut'] == 'termine') {
                                                                $icon = '✓';
                                                            } elseif ($event['statut'] == 'en_cours') {
                                                                $icon = '◐';
                                                            }

                                                            if ($event['priorite'] == 'haute') {
                                                                $icon = '⚠';
                                                                $borderColorHex = '#ef4444'; // red-500
                                                            }

                                                            // Calculer la hauteur en fonction de la durée
                                                            // 60px par heure - 8px pour l'espace entre cellules
                                                            $heightInPx = ($event['duree'] * 60) - 8;

                                                            // Formater les heures pour l'affichage
                                                            $heureDebutFormatee = sprintf('%02d:00', $event['heure_debut']);
                                                            $heureFinFormatee = sprintf('%02d:00', $event['heure_fin']);
                                                        @endphp
                                                        <a href="{{ $event['url'] }}"
                                                           class="absolute left-1 right-1 text-white rounded p-2 text-xs hover:opacity-90 shadow-lg z-10"
                                                           style="background-color: {{ $bgColorHex }}; border-left: 4px solid {{ $borderColorHex }}; height: {{ $heightInPx }}px; top: 4px;"
                                                           title="{{ $event['titre'] }} - {{ $event['affaire_titre'] }} ({{ $heureDebutFormatee }} - {{ $heureFinFormatee }})">

                                                            <div class="flex items-start gap-1 h-full">
                                                                <span class="font-bold flex-shrink-0">{{ $icon }}</span>
                                                                <div class="flex-1 min-w-0 overflow-hidden">
                                                                    <div class="font-semibold truncate">{{ $event['titre'] }}</div>
                                                                    <div class="text-xs opacity-90 truncate">{{ $event['affaire_code'] }}</div>
                                                                    <div class="text-xs opacity-75 mt-1">{{ $heureDebutFormatee }}-{{ $heureFinFormatee }}</div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Légende -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-900 rounded p-4">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Légende</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Statut des tâches :</div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-bold text-gray-700 dark:text-gray-300">✓</span>
                                        <span class="text-gray-700 dark:text-gray-300">Tâche terminée</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-bold text-gray-700 dark:text-gray-300">◐</span>
                                        <span class="text-gray-700 dark:text-gray-300">Tâche en cours</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-bold text-gray-700 dark:text-gray-300">◻</span>
                                        <span class="text-gray-700 dark:text-gray-300">Tâche à faire</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Priorité :</div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-xs">
                                        <div class="w-4 h-4 rounded" style="background-color: #2563eb; border-left: 4px solid #ef4444;"></div>
                                        <span class="text-gray-700 dark:text-gray-300">⚠ Priorité haute (bordure rouge)</span>
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-3">
                                        Chaque affaire a sa propre couleur pour faciliter l'identification
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Absences :</div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-xs">
                                        <div class="w-4 h-4 rounded" style="background-color: #f97316; border-left: 4px solid #ea580c;"></div>
                                        <span class="text-gray-700 dark:text-gray-300">Absence</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <div class="w-4 h-4 rounded" style="background-color: #a855f7; border-left: 4px solid #9333ea;"></div>
                                        <span class="text-gray-700 dark:text-gray-300">Maladie</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <div class="w-4 h-4 rounded" style="background-color: #eab308; border-left: 4px solid #ca8a04;"></div>
                                        <span class="text-gray-700 dark:text-gray-300">Retard</span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">✓ justifiée &nbsp; ✗ non justifiée &nbsp; ? en attente</div>
                                </div>
                            </div>
                        </div>
                    </div>                </div>
            </div>
        </div>
    </div>
</x-app-layout>
