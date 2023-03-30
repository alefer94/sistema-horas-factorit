<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/modal_filtro_usuarios_vicepresidencias.php
 * Autor: Cristian Lobios
 */
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/options_vicepresidencias.php';

function modal_filtro_usuarios_vicepresidencias(){ ?>
	<form onsubmit="system.users.alConfirmarFiltro(event)" for="btn-filtrar_vicepresidencias">
        <div class="form-group">
            <label for="slt-filtro_vicepresidencias">Término de búsqueda</label>
            <select name="vprID" placeholder="Déjese en blanco para no realizar filtro..." class="full-width" id="slt-filtro_vicepresidencias">
                <option></option>
            <?php options_vicepresidencias(new InterfazPDO(), 100, 1); ?>
            </select>
        </div>
        <div class="flex-container full-width m-t-10" style="height: 2rem">
            <button id="btn-modal_confirmar" type="submit" class="btn btn-complete m-r-auto full-height">Confirmar</button>
            <button id="btn-modal_cancelar" type="button" class="btn btn-default m-l-auto full-height" data-dismiss="modal">Cancelar</button>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() { $("#slt-filtro_vicepresidencias").select2(); }); 
    </script><?php      
}