<div class="form-inline container-fluid">
	<img src="{!! asset('images/logo2.png') !!}" width="80px">
	<h2> &nbsp; Club Atlético Oro Verde</h2>

    <!--para que solo se muestre en el menú por ej-->
		@if (Route::has('menu'))
			<div class="btn-group ml-auto">    <!-- Boton del header -->
				<button type="button" class="btn btn-light dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Opciones
				</button>
				  <div class="dropdown-menu">
				    <a class="dropdown-item" href="#">Backup</a>
				    <a class="dropdown-item" href="#">Mi Perfil</a>
			     <div class="dropdown-divider"></div>
				     <a class="dropdown-item" href="#">Salir</a>
				   </div>
			</div>
			&nbsp; &nbsp; &nbsp; &nbsp;		<!-- Para realizar espacios en blanco -->
		@endif
</div>
