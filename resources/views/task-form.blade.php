<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <title>Softflew</title>
    <style>
        body{
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div class="conatiner mt-4">
        <div class="row">
            <div class="col-3">
                
            </div>
            <div class="col-6">
                <div class="form-group">        
                    <div class="d-grid gap-2 col-6 mx-auto">
                      <button type="submit" class="btn btn-info text-center" id="showButton" onclick="showButton()">Enter</button>
                    </div>
                </div>

                <div id="hiddenFields" style="display:none;">
                    <table id="data-table" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Check Box</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <!-- Table body for data -->
                        <tbody></tbody>
                    </table>
                    
                </div>
            </div>
            <div class="col-3">
                
            </div>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function showButton(){
        $.ajax({
            type: 'GET',
            url: '{{ route('getTasks') }}',
            success: function (data) {
                $('#data-table tbody').empty();

                $.each(data, function (index, item) {
                    var isChecked = item.is_marked == 1 ? 'checked' : '';
                    var isDisabled = isChecked ? 'disabled' : '';
                    var completionStatus = isChecked ? 'Completed' : 'Mark as Completed';

                    if (!isChecked) {
                        $('#data-table tbody').append(
                            '<tr id="row-' + item.id + '">' +
                                '<td>' + item.id + '</td>' +
                                '<td>' + item.name + '</td>' +
                                '<td>' + item.description + '</td>' +
                                // '<td><input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '></td>' +
                                '<td>' +
                                    (isChecked ?
                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>' + '<label for="checkbox-' + item.id + '">' + completionStatus + '</label>'
                                        :
                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>'
                                    ) +
                                '</td>' +
                                '<td><button class="btn btn-danger delete-btn" data-task-id="' + item.id + '">Delete</button></td>' +
                            '</tr>'
                        );
                    }
                });

                $.each(data, function (index, item) {
                    var isChecked = item.is_marked == 1 ? 'checked' : '';
                    var isDisabled = isChecked ? 'disabled' : '';
                    var completionStatus = isChecked ? 'Completed' : 'Mark as Completed';

                    if (isChecked) {
                        $('#data-table tbody').append(
                            '<tr id="row-' + item.id + '">' +
                                '<td>' + item.id + '</td>' +
                                '<td>' + item.name + '</td>' +
                                '<td>' + item.description + '</td>' +
                                // '<td><input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '></td>' +
                                '<td>' +
                                    (isChecked ?
                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>' + '<label for="checkbox-' + item.id + '">' + completionStatus + '</label>'
                                        :
                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>'
                                    ) +
                                '</td>' +
                                '<td><button class="btn btn-danger delete-btn" data-task-id="' + item.id + '">Delete</button></td>' +
                            '</tr>'
                        );
                    }
                });

                $("#hiddenFields").toggle();
                $("#showButton").hide();
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        $(document).on('click', '.delete-btn', function () {
            var taskId = $(this).data('task-id');
            var type = "delete";
            Swal.fire({
                title: 'Are you sure to delete this data?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('getUpdateTasks') }}",
                        type: 'GET',
                        data: { 
                            taskId: taskId,
                            type: type, 
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === true) { 
                                toastr.success(response.message);
                                $('#row-' + taskId).remove();
                            } else {
                                toastr.error(response.error);
                            }
                        },
                    });
                }
            });
        });

        $(document).on('change', '.task-checkbox', function () {
            var taskId = $(this).attr('id').replace('checkbox-', '');
            var isMarked = $(this).is(':checked') ? 1 : 0;
            var type = "checkbox";

            Swal.fire({
                title: 'Are you sure your task has been completed?',
                text: 'You won\'t be able to revert this!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('getUpdateTasks') }}",
                        type: 'GET',
                        data: { 
                            taskId: taskId,
                            isMarked: isMarked,
                            type: type, 
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === true) { 
                                toastr.success(response.message);
                                $('#row-' + taskId).remove();

                                $('#data-table tbody').empty();

                                $.each(response.data, function (index, item) {
                                    var isChecked = item.is_marked == 1 ? 'checked' : '';
                                    var isDisabled = isChecked ? 'disabled' : '';
                                    var completionStatus = isChecked ? 'Completed' : 'Mark as Completed';

                                    if (!isChecked) {
                                        $('#data-table tbody').append(
                                            '<tr id="row-' + item.id + '">' +
                                                '<td>' + item.id + '</td>' +
                                                '<td>' + item.name + '</td>' +
                                                '<td>' + item.description + '</td>' +
                                                // '<td><input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '></td>' +
                                                '<td>' +
                                                    (isChecked ?
                                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>' + '<label for="checkbox-' + item.id + '">' + completionStatus + '</label>'
                                                        :
                                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>'
                                                    ) +
                                                '</td>' +
                                                '<td><button class="btn btn-danger delete-btn" data-task-id="' + item.id + '">Delete</button></td>' +
                                            '</tr>'
                                        );
                                    }
                                });

                                $.each(response.data, function (index, item) {
                                    var isChecked = item.is_marked == 1 ? 'checked' : '';
                                    var isDisabled = isChecked ? 'disabled' : '';
                                    var completionStatus = isChecked ? 'Completed' : 'Mark as Completed';

                                    if (isChecked) {
                                        $('#data-table tbody').append(
                                            '<tr id="row-' + item.id + '">' +
                                                '<td>' + item.id + '</td>' +
                                                '<td>' + item.name + '</td>' +
                                                '<td>' + item.description + '</td>' +
                                                // '<td><input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '></td>' +
                                                '<td>' +
                                                    (isChecked ?
                                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>' + '<label for="checkbox-' + item.id + '">' + completionStatus + '</label>'
                                                        :
                                                        '<input type="checkbox" class="task-checkbox" id="checkbox-' + item.id + '" ' + isChecked + ' ' + isDisabled + '>'
                                                    ) +
                                                '</td>' +
                                                '<td><button class="btn btn-danger delete-btn" data-task-id="' + item.id + '">Delete</button></td>' +
                                            '</tr>'
                                        );
                                    }
                                });
                            } else {
                                toastr.error(response.error);
                            }
                        },
                    });
                }
            });
        });
    });
</script>