@extends('layout')

    @section('content')
    <script>
        $(document).ready(function (){


            // Date range picker
            $('#start_date').datepicker({
                todayBtn:'linked',
                format: "yyyy-mm-dd",
                autoclose: true,
                changeYear: true,

            });

            $('#end_date').datepicker({
                todayBtn:'linked',
                format: "yyyy-mm-dd",
                autoclose: true,
                changeYear: true,
            });

            //

            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table= $('#example').DataTable({
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData[0]);
                },
                'serverSide': 'true',
                'processing': 'true',
                'paging': 'true',
                'order': [],
                'ajax': {
                    'url': '{{route('admin.calculate')}}',
                    'type': 'GET',
                    'data' :function(d){
                        return $.extend( {}, d, {
                            "start_date": $("#start_date").val(),
                            "end_date": $("#end_date").val(),
                        });
                    },
                },
                'columns': [
                    {
                        "className":         'dt-control',
                        "oderable":        false,
                        "data":             null,
                        "id":              'id',
                        "defaultContent":   ''

                    },
                    {name: 'full_name', data:'full_name'},
                    {name: 'hourly_rate', data: 'hourly_rate'},
                    {name: 'total_hours', data:'total_hours'},
                    {name: 'total_payment', data:'total_payment'},
                ]
            });

            $('#filter-date').click(function(){
                var start_date=$('#start_date').val();
                var end_date=$('#end_date').val();
                if(!start_date|| !end_date){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'U have to enter both dates!'
                    })
                }
                $('#example').DataTable().draw();
            })

            var user_id;//variabel global
            // Datatables for months
            $('#example tbody').on('click', 'td.dt-control', function(){
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var rowData = row.data();
                user_id= rowData.id; //gets the id of each row
                var months=rowData.months;
                    //console.log(months);
                function array_values(input) {
                    const tmpArr = []
                    let key = ''
                    for (key in input) {
                        tmpArr[tmpArr.length] = input[key]
                    }
                    return tmpArr//E kthen ne forme array te sakte
                }
                var months_array=array_values(months);
                //console.log(months_array);

                if (row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child(
                        '<table class="table month_table" id = "month_details' + user_id + '" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; float:center;">'+
                        '<thead style="background-color:#DFD7D5;"><tr><th>Month</th><th>Month Hours</th><th>Month payment</th></tr></thead><tbody>' +
                        '</tbody></table>').show();


                    var month_table = $('#month_details' + user_id).DataTable({//tek kjo hiqet serverside sepse nuk marrim data me ajax
                        "fnCreatedRow": function(nRow, aData, iDataIndex) {
                            $(nRow).attr('id', aData[0]);
                        },
                        'processing': 'true',
                        'paging': 'true',
                        'order': [],
                        'data': months_array,
                        'columns': [
                            {data: 'month', className:'month-index'},
                            {data: 'month_hours'},
                            {data: 'total_month_payment'},
                        ]
                    });
                    tr.addClass('shown');

                }
            })

            // Add event listener for opening and closing details
            $('#example tbody').on('click', 'td.month-index', function(){
                var tr1 = $(this).closest('tr');
                var temp=$('#month_details'+ user_id).DataTable();
                var row1=temp.row(tr1);
                var rowData1 = row1.data();
                var month_id= rowData1.month; //create the month_id
                var dates= rowData1.days; //merr ditet per muajin e clikcuar

                console.log(rowData1);


                if (row1.child.isShown() ) {
                    // This row is already open - close it
                    row1.child.hide();
                    tr1.removeClass('shown');
                }
                else {
                    // Open this row
                    row1.child(
                        '<table class="table table-striped child_table" id = "child_details' + month_id + '" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; float:center;">'+
                        '<thead style="background-color:#DFD7D5;"><tr><th>Date</th><th>Hours</th><th>Daily payment</th></tr></thead><tbody>' +
                        '</tbody></table>').show();


                    var childTable = $('#child_details' + month_id).DataTable({//tek kjo hiqet serverside sepse nuk marrim data me ajax
                        "fnCreatedRow": function(nRow, aData, iDataIndex) {
                            $(nRow).attr('id', aData[0]);
                        },
                        'processing': 'true',
                        'paging': 'true',
                        'order': [],
                        'data': dates,
                        'columns': [
                            {data: 'date'},
                            {data: 'day_hours'},
                            {data: 'day_pay'},
                        ]
                    });
                    tr1.addClass('shown');

                }

            })

        });
    </script>


    <!--  -->
    <div class="container-fluid">
        <h2 class="text-center">Welcome to Payment Page</h2>
        <p class="datatable design text-center">Payment Datatable</p>
        <div class="row">
            <div class="table-responsive">
                <br />
                <div class="row">
                    <div class="input-daterange">
                        <div class="col-md-2">
                            <input type="text" name="start_date" id="start_date" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="end_date" id="end_date" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="button" name="search" id="filter-date" value="Filter Date" class="btn btn-info" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">

                <div class="row">
                    <!-- <div class="col-md-2"></div> -->
                    <div class="col-md-12">
                        <table id="example" class="table">
                            <thead style="background-color:#DFD7D5;">
                            <th></th>
                            <th>Full Name</th>
                            <th>Horly Rate</th>
                            <th>Total Hours</th>
                            <th>Total Payment</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
    </div>


    @endsection
