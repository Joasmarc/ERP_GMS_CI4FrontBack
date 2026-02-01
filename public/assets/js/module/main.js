/* GLOBAL */
/* STATES */
/* INIT */
$(function() {

    // Datatables
    $(document).ready(function () {
        $("#basic-datatables").DataTable({});
    });

});

/* EVENTS */

function toggleLoader(isLoading) {  
  // 1.4 Si isLoading es true, mostrar loader y bloquear
  if (isLoading === true) {
    // 1.5 Agregar clases al loader para mostrarlo centrado
    $('#cont_loading').removeClass('d-none');
    
    
    // 1.6 Bloquear la pantalla
    // wrapper.style.pointerEvents = 'none';
    // wrapper.style.opacity = '0.5';
    
    // 1.7 Prevenir scroll
    // document.body.style.overflow = 'hidden';
  } else if (isLoading === false) {
    // 1.9 Remover clases del loader con transición suave
    $('#cont_loading').addClass('d-none');
    
    // 1.10 Usar timeout para animar la desaparición
    // setTimeout(() => {
    //   // 1.11 Desbloquear la pantalla
    //   wrapper.style.pointerEvents = 'auto';
    //   wrapper.style.opacity = '1';
      
    //   // 1.12 Permitir scroll nuevamente
    //   document.body.style.overflow = 'auto';
    // }, 300); // Esperar la transición CSS
  }
}

function executeWithLoader(duration = 2000) {
  toggleLoader(true);
    
  setTimeout(() => {
    toggleLoader(false);
  }, duration);
}
