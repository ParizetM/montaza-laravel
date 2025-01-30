<?php

if (!function_exists('formatNumber')) {
    /**
     * Formats a given number to a string with appropriate formatting.
     *
     * This function takes a number as input and returns it as a formatted string.
     * The formatting includes adding commas as thousand separators and ensuring
     * a fixed number of decimal places.
     *
     * @param mixed $number The number to be formatted. It can be an integer, float, or a string representing a number.
     * @return string The formatted number as a string.
     */
    /**
     * Summary of formatNumber
     * @param mixed $number
     * @return string
     */

     function formatNumber($number): string {
        // Vérifier si le nombre est numérique
        if (!is_numeric($number)) {
            return (string)$number;
        }

        // Convertir en chaîne pour manipuler les décimales
        $nombre = (string)$number;

        // Séparer la partie entière et la partie décimale
        $parties = explode('.', $nombre);
        $partie_entiere = $parties[0];
        $partie_decimale = $parties[1] ?? ''; // Utiliser une chaîne vide si aucune décimale

        // Supprimer les zéros inutiles après la virgule
        $partie_decimale = rtrim($partie_decimale, '0');

        // Formater la partie entière avec des espaces entre les milliers
        $partie_entiere_formattee = number_format((float)$partie_entiere, 0, '.', ' ');

        // Si la partie décimale n'est pas vide, on l'ajoute au résultat final
        if ($partie_decimale !== '') {
            return $partie_entiere_formattee . '.' . $partie_decimale;
        }

        return $partie_entiere_formattee;
    }
    function formatNumberArgent($number) {
        return number_format(formatNumber($number), 2, '.', ' ') . ' €';
    }
}
