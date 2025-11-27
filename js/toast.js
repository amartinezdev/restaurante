/**
 * Toast vanilla inspirado en los principios de Sonner (sin dependencias):
 * un único contenedor, transiciones (no keyframes) para que los avisos
 * se puedan lanzar varios seguidos sin saltos, auto-dismiss + clic para cerrar,
 * temporizador en pausa mientras el puntero está encima.
 */
(function () {
  'use strict';

  var ICONS = {
    success: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M8 12.5l2.5 2.5L16 9.5"></path></svg>',
    danger: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="7.5" x2="12" y2="13"></line><circle cx="12" cy="16.3" r="0.9" fill="currentColor" stroke="none"></circle></svg>',
    default: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="10.5" x2="12" y2="16"></line><circle cx="12" cy="7.7" r="0.9" fill="currentColor" stroke="none"></circle></svg>'
  };

  function ensureContainer() {
    var el = document.getElementById('toast-container');
    if (!el) {
      el = document.createElement('div');
      el.id = 'toast-container';
      el.setAttribute('aria-live', 'polite');
      el.setAttribute('aria-atomic', 'true');
      document.body.appendChild(el);
    }
    return el;
  }

  function dismiss(item) {
    if (item.hasAttribute('data-leaving')) return;
    item.setAttribute('data-leaving', '');

    var resuelto = false;
    function terminar() {
      if (resuelto) return;
      resuelto = true;
      item.remove();
    }
    item.addEventListener('transitionend', terminar, { once: true });
    setTimeout(terminar, 300); // red de seguridad, ver js/carrito.js
  }

  function toast(mensaje, tipo, duracion) {
    duracion = duracion || 3500;

    var container = ensureContainer();

    var item = document.createElement('div');
    item.className = 'toast-item' + (tipo === 'success' ? ' toast-success' : tipo === 'danger' ? ' toast-danger' : '');
    item.setAttribute('role', 'status');
    item.innerHTML =
      '<span class="toast-icon">' + (ICONS[tipo] || ICONS.default) + '</span>' +
      '<span class="toast-message"></span>' +
      '<button type="button" class="toast-close" aria-label="Cerrar aviso">&times;</button>' +
      '<span class="toast-progress"></span>';

    item.querySelector('.toast-message').textContent = mensaje;
    container.appendChild(item);

    var progressEl = item.querySelector('.toast-progress');
    var progressAnim = (typeof progressEl.animate === 'function')
      ? progressEl.animate(
        [{ transform: 'scaleX(1)' }, { transform: 'scaleX(0)' }],
        { duration: duracion, easing: 'linear', fill: 'forwards' }
      )
      : null;

    var timer;
    function programar(ms) {
      clearTimeout(timer);
      timer = setTimeout(function () { dismiss(item); }, ms);
    }
    programar(duracion);

    item.addEventListener('click', function () {
      clearTimeout(timer);
      dismiss(item);
    });

    item.querySelector('.toast-close').addEventListener('click', function (e) {
      e.stopPropagation();
      clearTimeout(timer);
      dismiss(item);
    });

    // pausa exacta del temporizador y de la barra de progreso mientras el
    // usuario tiene el puntero encima; al salir, retoma justo donde iba
    item.addEventListener('mouseenter', function () {
      clearTimeout(timer);
      if (progressAnim) progressAnim.pause();
    });
    item.addEventListener('mouseleave', function () {
      if (progressAnim) {
        progressAnim.play();
        programar(duracion - progressAnim.currentTime);
      } else {
        programar(1200);
      }
    });
  }

  window.toast = toast;
})();
