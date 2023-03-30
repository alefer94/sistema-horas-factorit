<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/modal_filtro_proyectos_nombre.php
 * Autor: Benjamin La Madrid
 */
function modal_filtro_feriados_anio(){ ?>
	<form onsubmit="system.feriado.alConfirmarFiltro(event)" for="btn-filtrar_anio">
        <div class="form-group">
            <label for="ipt-filtro_anio">Término de búsqueda</label>
            <input class="form-control" name="frdAnio" placeholder="Déjese en blanco para no realizar filtro..." type="text" minlength="1" id="ipt-filtro_anio" />
        </div>
        <div class="flex-container full-width m-t-10" style="height: 2rem">
            <button id="btn-modal_confirmar" type="submit" class="btn btn-complete m-r-auto full-height">Confirmar</button>
            <button id="btn-modal_cancelar" type="button" class="btn btn-default m-l-auto full-height" data-dismiss="modal">Cancelar</button>
        </div>
    </form><?php      
}