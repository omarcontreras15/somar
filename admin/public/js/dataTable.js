
if (document.querySelectorAll("#example").length == 1) {
    $(document).ready(function () {
        // Setup - add a text input to each footer cell

        // DataTable
        var table = $('#example').DataTable(
            {
                "language": {
                    "lengthMenu": "Mostrando _MENU_ resultados por pagina",
                    "zeroRecords": "No se encuentran productos",
                    "info": "Mostrando pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "",
                    "infoFiltered": "(filtrado de _MAX_ registros)",
                    "search": "Buscar Producto: ",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        
        //remuevo el pie del datatable
        $('#example tfoot th').remove();
        table.columns().every(function () {
            var that = this;

            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
        });
    });
}
