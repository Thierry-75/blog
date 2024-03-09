
import './js/deletePhoto.js';
import './js/modal.js'

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';
import './styles/style.css';


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything


// or you can include specific pieces
require('bootstrap');
require('bootstrap-icons/font/bootstrap-icons.css');
require('bootstrap/js/dist/tooltip');
require('bootstrap/js/dist/popover');

$(document).ready(function () {

window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');

  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

});

