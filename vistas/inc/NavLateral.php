<section class="full-box nav-lateral">
	<div class="full-box nav-lateral-bg show-nav-lateral"></div>
	<div class="full-box nav-lateral-content">
		<figure class="full-box nav-lateral-avatar">
			<i class="far fa-times-circle show-nav-lateral"></i>
			<img src="<?php echo SERVERURL; ?>vistas/assets/avatar/cck.png" class="img-fluid" alt="Avatar">
			<figcaption class="roboto-medium text-center">
				<?php echo $_SESSION['nombre_spm'] . " " . $_SESSION['apellido_spm']; ?> <br><small class="roboto-condensed-light"> <?php echo $_SESSION['usuario_spm']; ?> </small>
			</figcaption>
		</figure>
		<div class="full-box nav-lateral-bar"></div>
		<nav class="full-box nav-lateral-menu">
			<ul>
				<li>
					<a href="<?php echo SERVERURL; ?>home/"><i class="fab fa-dashcube fa-fw"></i> &nbsp; Dashboard</a>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp; Responsables <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Responsable</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de Responsables</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar Responsable</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fa fa-cube"></i> &nbsp; Items <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar item</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de items</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar item</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fa fa-sticky-note" aria-hidden="true"></i> &nbsp; Remitos <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo Remito</a>
						</li>
						<!--li>
							<a href="<?php echo SERVERURL; ?>reservation-reservation/"><i class="far fa-calendar-alt fa-fw"></i> &nbsp; Reservaciones</a>
						</li-->
						<li>
							<a href="<?php echo SERVERURL; ?>reservation-pending/"><i class="fa fa-sticky-note" aria-hidden="true"></i> &nbsp; Remitos</a>
						</li>
						<!--li>
							<a href="<?php echo SERVERURL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Finalizados</a>
						</li-->
						<li>
							<a href="<?php echo SERVERURL; ?>reservation-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar por fecha</a>
						</li>
					</ul>
				</li>
				<?php if ($_SESSION['privilegio_spm'] == 1) {?>
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fa fa-user"></i> &nbsp; Usuarios <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo usuario</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de usuarios</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar usuario</a>
						</li>
					</ul>
				</li>
				<?php
				}
				if ($_SESSION['privilegio_spm'] == 1 || $_SESSION['privilegio_spm'] == 2) {
				?>
				<li>
					<a href="<?php echo SERVERURL; ?>company/"><i class="fa fa-university"></i> &nbsp; Organismo</a>
				</li>
				<?php } ?>
			</ul>
		</nav>
	</div>
</section>