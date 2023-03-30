<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/modal_filtro_proyectos_subgerencias.php
 * Autor: Cristian Lobos 
 */
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/options_subgerencias.php';

function modal_filtro_proyectos_subgerencias(){ ?>
	<form onsubmit="system.projects.alConfirmarFiltro(event)" for="btn-filtrar_subgerencias">
        <div class="form-group">
            <label for="slt-filtro_subgerencias">Término de búsqueda</label>
            <select name="sgrID" placeholder="Déjese en blanco para no realizar filtro..." class="full-width" id="slt-filtro_subgerencias">
                <option></option>
            <?php options_subgerencias(new InterfazPDO(), 100, 1); ?>
            </select>
        </div>
        <div class="flex-container full-width m-t-10" style="height: 2rem">
            <button id="btn-modal_confirmar" type="submit" class="btn btn-complete m-r-auto full-height">Confirmar</button>
            <button id="btn-modal_cancelar" type="button" class="btn btn-default m-l-auto full-height" data-dismiss="modal">Cancelar</button>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() { $("#slt-filtro_subgerencias").select2(); }); 
    </script><?php      
}