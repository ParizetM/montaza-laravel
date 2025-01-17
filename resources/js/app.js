import './bootstrap';

// Import principal de Handsontable
import Handsontable from 'handsontable';
import 'handsontable/styles/handsontable.min.css';
import 'handsontable/styles/ht-theme-main.min.css';

import { registerLanguageDictionary, frFR } from 'handsontable/i18n';

// Import de numbro
import numbro from 'numbro';

// Import Alpine.js
import Alpine from 'alpinejs';

// Enregistrement du français
registerLanguageDictionary(frFR);

window.Handsontable = Handsontable;
window.frFR = frFR;
window.numbro = numbro;
window.Alpine = Alpine;

// Démarrage d'Alpine
Alpine.start();
