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
import { HyperFormula } from 'hyperformula';

// Enregistrement du français
registerLanguageDictionary(frFR);
import 'chartjs-adapter-date-fns';
import Chart from 'chart.js/auto';
import './form-navigation';

// Exposition des librairies dans la fenêtre globale
window.Chart = Chart;
window.Handsontable = Handsontable;
window.frFR = frFR;
window.HyperFormula = HyperFormula;
window.numbro = numbro;
window.Alpine = Alpine;

// Démarrage d'Alpine
Alpine.start();
