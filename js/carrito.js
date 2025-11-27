/**
 * Convierte el carrito de cliente/carta.php en AJAX (sin recargar la página).
 *
 * Progressive enhancement: todos los enlaces/formularios mantienen su
 * href/action original. Si el navegador no tiene `fetch`, o una petición
 * falla por red, se cae al comportamiento de siempre (navegación normal),
 * en vez de dejar un botón que no hace nada.
 */
(function () {
  'use strict';

  if (typeof window.fetch !== 'function') {
    return;
  }

  var form = document.getElementById('form-carrito');
  var tbody = document.getElementById('carrito-tbody');
  var totalEl = document.getElementById('carrito-total');
  var vacioEl = document.getElementById('carrito-vacio');
  var contenidoEl = document.getElementById('carrito-contenido');

  if (!form || !tbody || !totalEl || !vacioEl || !contenidoEl) {
    return;
  }

  function setBusy(el, busy) {
    if (!el) return;
    if (el.tagName === 'BUTTON') {
      el.disabled = busy;
    } else {
      el.style.pointerEvents = busy ? 'none' : '';
    }
    el.style.opacity = busy ? '0.6' : '';
  }

  function flash(el) {
    el.classList.add('flash');
    clearTimeout(el._flashTimer);
    el._flashTimer = setTimeout(function () {
      el.classList.remove('flash');
    }, 600);
  }

  function salirFila(fila) {
    return new Promise(function (resolve) {
      if (!fila || !fila.isConnected) {
        resolve();
        return;
      }

      var resuelto = false;
      function terminar() {
        if (resuelto) return;
        resuelto = true;
        fila.remove();
        resolve();
      }

      fila.addEventListener('transitionend', terminar, { once: true });
      // red de seguridad: si por lo que sea la transición no llega a
      // disparar transitionend (p. ej. el contenedor se oculta a mitad de
      // camino), no nos quedamos con la fila fantasma para siempre.
      setTimeout(terminar, 300);

      if (!fila.hasAttribute('data-leaving')) {
        fila.setAttribute('data-leaving', '');
      }
    });
  }

  function entrarFila(fila) {
    fila.setAttribute('data-entering', '');
    tbody.appendChild(fila);
    // dos rAF para forzar que el navegador pinte el estado "entering"
    // antes de quitarlo y disparar así la transición (equivalente JS de @starting-style)
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        fila.removeAttribute('data-entering');
      });
    });
  }

  function crearFila(item) {
    var tr = document.createElement('tr');
    tr.className = 'carrito-row';
    tr.setAttribute('data-id', item.id);
    tr.innerHTML =
      '<td class="carrito-nombre"></td>' +
      '<td class="text-center carrito-precio"></td>' +
      '<td class="text-center" style="max-width:120px;">' +
      '<input type="number" name="cantidades[' + item.id + ']" min="1" max="' + item.stockMax + '" class="form-control text-center carrito-cantidad-input" data-id="' + item.id + '">' +
      '<small class="small text-muted text-start">Máx: ' + item.stockMax + '</small>' +
      '</td>' +
      '<td><textarea name="comentario[' + item.id + ']" class="form-control text-center carrito-comentario" maxlength="15" placeholder="Comentario... (Opcional)"></textarea></td>' +
      '<td class="text-center carrito-subtotal" data-id="' + item.id + '"></td>' +
      '<td class="text-center"><a class="btn btn-sm btn-outline-danger carrito-remove" data-id="' + item.id + '" href="carta.php?remove=' + item.id + '">Eliminar</a></td>';

    tr.querySelector('.carrito-nombre').textContent = item.nombre;
    tr.querySelector('.carrito-precio').textContent = item.precioFmt;
    tr.querySelector('.carrito-cantidad-input').value = item.cantidad;
    tr.querySelector('.carrito-subtotal').textContent = item.subtotalFmt;

    return tr;
  }

  function aplicarRespuesta(data, contexto) {
    // Si hay algo que mostrar, el contenedor tiene que estar visible YA,
    // antes de insertar filas: si insertamos con el contenedor en d-none
    // (display:none), la transición de entrada nunca llega a pintarse.
    if (data.carrito.length > 0) {
      vacioEl.classList.add('d-none');
      contenidoEl.classList.remove('d-none');
    }

    data.carrito.forEach(function (item) {
      var fila = tbody.querySelector('tr[data-id="' + item.id + '"]');

      if (!fila) {
        entrarFila(crearFila(item));
        return;
      }

      var subtotalEl = fila.querySelector('.carrito-subtotal');
      if (subtotalEl && subtotalEl.textContent !== item.subtotalFmt) {
        subtotalEl.textContent = item.subtotalFmt;
        flash(subtotalEl);
      }

      var afectaEstaFila = (contexto.accion === 'add' || contexto.accion === 'remove') && String(contexto.id) === String(item.id);
      if (contexto.accion === 'updateCantidad' || afectaEstaFila) {
        var input = fila.querySelector('.carrito-cantidad-input');
        // no pisamos el input si el usuario está escribiendo en él ahora mismo
        if (input && document.activeElement !== input) {
          input.value = item.cantidad;
        }
      }
    });

    var salidas = [];
    Array.prototype.forEach.call(tbody.querySelectorAll('tr[data-id]'), function (fila) {
      var id = fila.getAttribute('data-id');
      var sigueExistiendo = data.carrito.some(function (item) {
        return String(item.id) === String(id);
      });
      if (!sigueExistiendo) {
        salidas.push(salirFila(fila));
      }
    });

    totalEl.textContent = data.totalFmt;
    flash(totalEl);

    // Solo ocultamos el contenedor (y mostramos el estado vacío) DESPUÉS de
    // que todas las salidas hayan terminado de verdad — nunca a mitad de
    // la transición, o esta se corta en seco y la fila se queda fantasma.
    Promise.all(salidas).then(function () {
      if (tbody.children.length === 0) {
        vacioEl.classList.remove('d-none');
        contenidoEl.classList.add('d-none');
      }
    });
  }

  function mensajeExito(accion) {
    switch (accion) {
      case 'add': return 'Producto añadido';
      case 'remove': return 'Producto eliminado';
      case 'updateCantidad': return 'Cantidades actualizadas';
      case 'clear': return 'Carrito vaciado';
      default: return 'Hecho';
    }
  }

  function ejecutar(accion, params, triggerEl, contexto) {
    var body = new URLSearchParams();
    body.set('action', accion);

    Object.keys(params).forEach(function (key) {
      var valor = params[key];
      if (valor && typeof valor === 'object') {
        Object.keys(valor).forEach(function (subKey) {
          body.set(key + '[' + subKey + ']', valor[subKey]);
        });
      } else {
        body.set(key, valor);
      }
    });

    setBusy(triggerEl, true);

    return fetch('api/carrito.php', {
      method: 'POST',
      headers: { Accept: 'application/json' },
      body: body
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        setBusy(triggerEl, false);

        if (!data.ok) {
          window.toast(data.error || 'No se ha podido completar la acción.', 'danger');
          return;
        }

        aplicarRespuesta(data, contexto);
        // la cantidad se autoguarda mientras se escribe; el flash del subtotal
        // ya es la confirmación, un toast por cada tecleo sería ruido.
        if (contexto.accion !== 'updateCantidad') {
          window.toast(mensajeExito(contexto.accion), 'success');
        }
      })
      .catch(function () {
        // sin red/servidor disponible: recae en el comportamiento normal (navegación completa)
        setBusy(triggerEl, false);
        window.toast('No se pudo conectar. Recargando la página...', 'danger');
        window.location.href = (triggerEl && triggerEl.tagName === 'A') ? triggerEl.href : 'carta.php';
      });
  }

  document.addEventListener('click', function (e) {
    var addBtn = e.target.closest('.carrito-add');
    if (addBtn) {
      e.preventDefault();
      var addId = addBtn.getAttribute('data-id');
      ejecutar('add', { id: addId }, addBtn, { accion: 'add', id: addId });
      return;
    }

    var removeBtn = e.target.closest('.carrito-remove');
    if (removeBtn) {
      e.preventDefault();
      var removeId = removeBtn.getAttribute('data-id');
      salirFila(removeBtn.closest('tr'));
      ejecutar('remove', { id: removeId }, removeBtn, { accion: 'remove', id: removeId });
      return;
    }

    var clearBtn = e.target.closest('.carrito-clear');
    if (clearBtn) {
      e.preventDefault();
      Array.prototype.forEach.call(tbody.querySelectorAll('tr[data-id]'), salirFila);
      ejecutar('clear', {}, clearBtn, { accion: 'clear' });
    }
  });

  // La cantidad se guarda sola al escribir (no hay botón "Actualizar" en la SPA):
  // cada input tiene su propio debounce para no lanzar una petición por tecla.
  var debounceTimers = new WeakMap();

  form.addEventListener('input', function (e) {
    var input = e.target.closest('.carrito-cantidad-input');
    if (!input) return;

    clearTimeout(debounceTimers.get(input));
    debounceTimers.set(input, setTimeout(function () {
      var id = input.getAttribute('data-id');
      var cantidades = {};
      cantidades[id] = input.value;
      ejecutar('updateCantidad', { cantidades: cantidades }, input, { accion: 'updateCantidad', id: id });
    }, 500));
  });
})();
