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
    function formatNumber($number) {
        // Vérifier si le nombre est numérique
        if (!is_numeric($number)) {
            return '0';
        }
        // Supprimer les zéros inutiles à la fin et le point décimal si nécessaire
        return rtrim(rtrim((string)$number, '0'), '.');
    }
}
