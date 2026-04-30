"use strict";

if (typeof BASE_URL === "undefined") {
  window.BASE_URL = window.location.origin + "/techhub";
}

document.addEventListener("DOMContentLoaded", function () {
  bindAgregarCarrito();
  iniciarBuscadorNav();

  // Cerrar resultados al hacer clic fuera
  document.addEventListener("click", function (e) {
    const resultados = document.getElementById("resultadosBusqueda");
    const buscador = document.getElementById("buscadorNav");
    if (
      resultados &&
      buscador &&
      !buscador.contains(e.target) &&
      !resultados.contains(e.target)
    ) {
      resultados.style.display = "none";
    }
  });
});

/* ── Vincular botones del carrito ────────────────────────── */
function bindAgregarCarrito() {
  document.querySelectorAll(".btn-agregar-carrito").forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = this.dataset.id;
      agregarAlCarrito(id, 1, this);
    });
  });
}

/* ── Agregar al carrito via AJAX ─────────────────────────── */
function agregarAlCarrito(productoId, cantidad, boton) {
  if (boton) {
    boton.disabled = true;
    boton.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
  }

  fetch(BASE_URL + "/index.php?controller=carrito&action=agregar", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "producto_id=" + productoId + "&cantidad=" + cantidad,
  })
    .then(function (r) {
      return r.json();
    })
    .then(function (data) {
      if (boton) {
        boton.disabled = false;
        boton.innerHTML = '<i class="bi bi-cart-plus"></i> Agregar';
      }
      if (data.login) {
        window.location.href =
          BASE_URL + "/index.php?controller=auth&action=loginForm";
        return;
      }
      mostrarToast(data.mensaje, data.exito ? "bg-dark" : "bg-danger");
      if (data.exito && data.total_items !== undefined) {
        actualizarBadgeCarrito(data.total_items);
      }
    })
    .catch(function () {
      if (boton) {
        boton.disabled = false;
        boton.innerHTML = '<i class="bi bi-cart-plus"></i> Agregar';
      }
      mostrarToast("Error de conexión. Intenta de nuevo.", "bg-danger");
    });
}

/* ── Mostrar toast ───────────────────────────────────────── */
function mostrarToast(mensaje, clase) {
  clase = clase || "bg-dark";
  var toastEl = document.getElementById("toastCarrito");
  var mensajeEl = document.getElementById("toastMensaje");
  if (!toastEl || !mensajeEl) return;
  toastEl.className = "toast align-items-center text-white border-0 " + clase;
  mensajeEl.textContent = mensaje;
  var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
  toast.show();
}

/* ── Actualizar badge del carrito ────────────────────────── */
function actualizarBadgeCarrito(cantidad) {
  var badge = document.getElementById("carritoBadge");
  if (!badge) return;
  badge.textContent = cantidad;
  badge.style.display = cantidad > 0 ? "inline-block" : "none";
}

/* ── Buscador navbar (live AJAX) ─────────────────────────── */
function iniciarBuscadorNav() {
  var input = document.getElementById("buscadorNav");
  var resultados = document.getElementById("resultadosBusqueda");
  var inner = document.getElementById("resultadosInner");
  if (!input || !resultados || !inner) return;

  var timeout;

  input.addEventListener("input", function () {
    clearTimeout(timeout);
    var termino = this.value.trim();

    if (termino.length < 2) {
      resultados.style.display = "none";
      return;
    }

    timeout = setTimeout(function () {
      inner.innerHTML =
        '<p class="text-muted small p-2 mb-0"><span class="spinner-border spinner-border-sm me-1"></span> Buscando...</p>';
      resultados.style.display = "block";

      fetch(
        BASE_URL +
          "/index.php?controller=producto&action=buscar&q=" +
          encodeURIComponent(termino),
      )
        .then(function (r) {
          return r.json();
        })
        .then(function (data) {
          if (!data.productos || data.productos.length === 0) {
            inner.innerHTML =
              '<p class="text-muted small p-2 mb-0">No se encontraron resultados para "<strong>' +
              termino +
              '</strong>"</p>';
            return;
          }

          var html = "";
          var lista = data.productos.slice(0, 5);
          lista.forEach(function (p) {
            html +=
              '<a href="' +
              BASE_URL +
              "/index.php?controller=producto&action=detalle&id=" +
              p.id +
              '" ' +
              'class="d-flex align-items-center gap-2 p-2 rounded text-decoration-none text-dark" ' +
              'style="transition:.15s" ' +
              "onmouseover=\"this.style.background='#f8f9fa'\" " +
              "onmouseout=\"this.style.background=''\">" +
              '<img src="' +
              BASE_URL +
              "/assets/img/" +
              p.imagen +
              '" width="40" height="40" ' +
              'class="object-fit-contain bg-light rounded p-1" ' +
              "onerror=\"this.src='" +
              BASE_URL +
              "/assets/img/default.jpg'\">" +
              '<div class="flex-grow-1">' +
              '<div class="fw-semibold small">' +
              p.nombre +
              "</div>" +
              '<div class="text-muted" style="font-size:.75rem">' +
              p.precio_fmt +
              " · " +
              p.categoria +
              "</div>" +
              "</div>" +
              '<i class="bi bi-chevron-right text-muted small"></i>' +
              "</a>";
          });

          if (data.total > 5) {
            html +=
              '<div class="border-top mt-1 pt-1">' +
              '<a href="' +
              BASE_URL +
              '/index.php?controller=producto&action=catalogo" ' +
              'class="d-block text-center text-muted small py-1 rounded" ' +
              "onmouseover=\"this.style.background='#f8f9fa'\" onmouseout=\"this.style.background=''\">" +
              "Ver todos los resultados (" +
              data.total +
              ")" +
              "</a></div>";
          }

          inner.innerHTML = html;
        })
        .catch(function () {
          inner.innerHTML =
            '<p class="text-danger small p-2 mb-0">Error al buscar. Intenta de nuevo.</p>';
        });
    }, 350);
  });

  // Cerrar con Escape
  input.addEventListener("keydown", function (e) {
    if (e.key === "Escape") resultados.style.display = "none";
  });

  // Enter → ir al catálogo con búsqueda
  input.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      var termino = this.value.trim();
      if (termino) {
        window.location.href =
          BASE_URL +
          "/index.php?controller=producto&action=catalogo&q=" +
          encodeURIComponent(termino);
      }
    }
  });
}
