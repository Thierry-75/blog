import './bootstrap.js';
require('bootstrap-icons/font/bootstrap-icons.css');



// or you can include specific pieces
require('bootstrap/js/src/tooltip.js');
require('bootstrap/js/dist/popover');
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';
import './styles/style.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
