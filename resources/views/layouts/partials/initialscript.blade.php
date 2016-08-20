<script>  
        var table;    
       $(document).ready(function(){
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            
            jQuery.datetimepicker.setLocale('es');
            jQuery('.datetimepicker').datetimepicker({format:'Y-m-d H:i:s',mask:true, allowBlank : true});
            
            jQuery('.datetimepicker:not(.pre)').val("{{Carbon\Carbon::now()->addHours(24)->format('Y-m-d H:i:s')}}");

            $('.datatable tfoot th').each( function () {
                    var title = $(this).text();
                    $(this).html( '<input type="search" style="width: 100px;" class="form-control input-sm"/>' );
                } );
            
            table =  $('.datatable').DataTable({
                "language": {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "decimal": ",",
                    "thousands": ".",
                    buttons: {
                    'copy' : 'Copiar', 'excel' :'Exportar a Excel', 'pdf': 'Exportar a Pdf' ,'print' :'Imprimir', 'colvis':'Ver'
                    }
                },
                responsive: true,
                ordering: false,
                colReorder: true,
                dom: 'rBtip',
                buttons: [
                    {
                        extend:    'copyHtml5',
                        text:      '<i class="fa fa-files-o text-info"></i>',
                        titleAttr: 'Copiar',
                        className: "btn btn-default",
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend:    'excelHtml5',
                        text:      '<i class="fa fa-file-excel-o text-success"></i>',
                        titleAttr: 'Excel',
                        className: "btn btn-default",
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend:    'print',
                        text:      '<i class="fa fa-print text-warning"></i>',
                        titleAttr: 'Imprimir',
                        className: "btn btn-default",                        
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend:    'pdfHtml5',
                        text:      '<i class="fa fa-file-pdf-o text-danger"></i>',
                        titleAttr: 'PDF',
                        className: "btn btn-default",
                        exportOptions: {
                            columns: ':visible'
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend:    'colvis',
                        text:      '<i class="fa fa-eye text-primary"></i>',
                        className: "btn btn-default",
                        titleAttr: 'Mostrar/Ocultar Columnas'
                    }
                ]
            });
            table.columns().every( function () {
                var that = this;
                $( 'input', this.footer() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );


            $(".chosen").chosen({
                allow_single_deselect: true
            });

            if($('#textarea').length >0)
                CKEDITOR.replace( 'textarea' );
       });
</script>