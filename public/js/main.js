document.addEventListener('DOMContentLoaded', function () {
	// Selecciona todos los formularios con la clase "formEliminar"
	const formsEliminar = document.querySelectorAll('.formEliminar');

	formsEliminar.forEach(form => {
		form.addEventListener('submit', function (event) {
			// Previene el envío del formulario por defecto
			event.preventDefault();

			// Muestra un cuadro de diálogo de confirmación
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					this.submit();
				}
			})
		});
	});
});