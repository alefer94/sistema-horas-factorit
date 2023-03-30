<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/modal_filtro_proyectos_cliente.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/options_estados_usuario.php';

function modal_filtro_usuarios_estados(){ ?>
	<form onsubmit="system.users.alConfirmarFiltro(event)" for="btn-filtrar_estados">
        <div class="form-group">
            <label for="slt-filtro_estados">Término de búsqueda</label>
            <select name="usrEstID" placeholder="Déjese en blanco para no realizar filtro..." class="full-width" id="slt-filtro_estados">
                <option></option>
            <?php options_estados_usuario(new InterfazPDO(), 100, 1); ?>
            </select>
        </div>
        <div class="flex-container full-width m-t-10" style="height: 2rem">
            <button id="btn-modal_confirmar" type="submit" class="btn btn-complete m-r-auto full-height">Confirmar</button>
            <button id="btn-modal_cancelar" type="button" class="btn btn-default m-l-auto full-height" data-dismiss="modal">Cancelar</button>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() { $("#slt-filtro_estados").select2(); }); 
    </script><?php      
}