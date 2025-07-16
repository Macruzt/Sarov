<!doctype html>
<html lang="en">
<head>
    <title>Laravel + Firebase</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <strong>Nuevo usuario</strong>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="addUser" class="" method="POST" action="">
                        <div class="form-group">
                            <label for="first_name" class="col-md-12 col-form-label">Nombre</label>

                            <div class="col-md-12">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-md-12 col-form-label">Apellidos</label>

                            <div class="col-md-12">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-md-offset-3">
                                <button type="button" class="btn btn-primary btn-block desabled" id="submitUser">
                                    Crear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <strong>Usuarios</strong>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidosme</th>
                            <th width="180" class="text-center">Acción</th>
                        </tr>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<form action="" method="POST" class="users-remove-record-model">
    <div id="remove-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:55%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="custom-width-modalLabel">Borrar usuario</h4>
                    <button type="button" class="close remove-data-from-delete-form" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h4>¿Seguro que desea borrar el usuario?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light deleteMatchRecord">Borrar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Update Model -->
<form action="" method="POST" class="users-update-record-model form-horizontal">
    <div id="update-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:55%;">
            <div class="modal-content" style="overflow: hidden;">
                <div class="modal-header">
                    <h4 class="modal-title" id="custom-width-modalLabel">Actualizar usuario</h4>
                    <button type="button" class="close update-data-from-delete-form" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" id="updateBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect update-data-from-delete-form" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light updateUserRecord">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var config = {
        apiKey: "{{ config('services.firebase.api_key') }}",
authDomain: "{{ config('services.firebase.auth_domain') }}",
databaseURL: "{{ config('services.firebase.database_url') }}",
projectId: "{{ config('services.firebase.project_id') }}",
storageBucket: "{{ config('services.firebase.storage_bucket') }}",
messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
appId: "{{ config('services.firebase.app_id') }}"
    };

    firebase.initializeApp(config);
    var database = firebase.database();
    var lastIndex = 0;
</script>
<script src="./js/fb_crud.js"></script>
</body>
</html>