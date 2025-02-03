
$(document).ready(function() {
    var table = $('#gameTable').DataTable();

    $('#addGame').click(function() {
        $('#form')[0].reset();
        $('#id').val('');
        $('#gameForm').dialog('open');
    });

    $('#gameForm').dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Save": function() {
                var formData = new FormData($('#form')[0]);
                var id = $('#id').val();
                var url = id ? 'game/update/' + id : 'game/create';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#gameForm').dialog('close');
                            table.ajax.reload();
                        } else {
                            alert(res.message);
                        }
                    }
                });
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        }
    });

    $('#gameTable').on('click', '.editGame', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'game/edit/' + id,
            type: 'GET',
            success: function(response) {
                var game = JSON.parse(response);
                $('#id').val(game.id);
                $('#JudulGame').val(game.JudulGame);
                $('#NegaraAsal').val(game.NegaraAsal);
                $('#Deskripsi').val(game.Deskripsi);
                $('#gameForm').dialog('open');
            }
        });
    });

    $('#gameTable').on('click', '.deleteGame', function() {
        var id = $(this).data('id');
        $('#deleteConfirm').data('id', id).dialog('open');
    });

    $('#deleteConfirm').dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Yes": function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'game/delete/' + id,
                    type: 'POST',
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#deleteConfirm').dialog('close');
                            table.ajax.reload();
                        } else {
                            alert(res.message);
                        }
                    }
                });
            },
            "No": function() {
                $(this).dialog('close');
            }
        }
    });
});