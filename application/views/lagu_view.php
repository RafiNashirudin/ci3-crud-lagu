<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Lagu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>CRUD Lagu</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Tambah Lagu</button>
        <table id="laguTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Lagu</th>
                    <th>Penyanyi</th>
                    <th>Pencipta</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lagu as $item): ?>
                <tr>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo $item->JudulLagu; ?></td>
                    <td><?php echo $item->Penyanyi; ?></td>
                    <td><?php echo $item->Pencipta; ?></td>
                    <td><img src="<?php echo base_url('uploads/' . $item->Gambar); ?>" width="100"></td>
                    <td>
                        <button class="btn btn-warning btn-edit" data-id="<?php echo $item->id; ?>">Edit</button>
                        <button class="btn btn-danger btn-delete" data-id="<?php echo $item->id; ?>">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lagu</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="JudulLagu">Judul Lagu</label>
                            <input type="text" class="form-control" id="JudulLagu" name="JudulLagu" required>
                        </div>
                        <div class="form-group">
                            <label for="Penyanyi">Penyanyi</label>
                            <input type="text" class="form-control" id="Penyanyi" name="Penyanyi" required>
                        </div>
                        <div class="form-group">
                            <label for="Pencipta">Pencipta</label>
                            <input type="text" class="form-control" id="Pencipta" name="Pencipta" required>
                        </div>
                        <div class="form-group">
                            <label for="Gambar">Gambar</label>
                            <input type="file" class="form-control" id="Gambar" name="Gambar" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lagu</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="editForm" enctype="multipart/form-data">
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editJudulLagu">Judul Lagu</label>
                            <input type="text" class="form-control" id="editJudulLagu" name="JudulLagu" required>
                        </div>
                        <div class="form-group">
                            <label for="editPenyanyi">Penyanyi</label>
                            <input type="text" class="form-control" id="editPenyanyi" name="Penyanyi" required>
                        </div>
                        <div class="form-group">
                            <label for="editPencipta">Pencipta</label>
                            <input type="text" class="form-control" id="editPencipta" name="Pencipta" required>
                        </div>
                        <div class="form-group">
                            <label for="editGambar">Gambar</label>
                            <input type="file" class="form-control" id="editGambar" name="Gambar">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#laguTable').DataTable();

            // Tambah Lagu
            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: '<?php echo site_url('lagu/add'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#addModal').modal('hide');
                            location.reload();
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });

            // Edit Lagu
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '<?php echo site_url('lagu/edit/'); ?>' + id,
                    type: 'GET',
                    success: function(response) {
                        var lagu = JSON.parse(response);
                        $('#editId').val(lagu.id);
                        $('#editJudulLagu').val(lagu.JudulLagu);
                        $('#editPenyanyi').val(lagu.Penyanyi);
                        $('#editPencipta').val(lagu.Pencipta);
                        $('#editModal').modal('show');
                    }
                });
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = $('#editId').val();
                $.ajax({
                    url: '<?php echo site_url('lagu/update/'); ?>' + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#editModal').modal('hide');
                            location.reload();
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });

            // Hapus Lagu
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus lagu ini?')) {
                    $.ajax({
                        url: '<?php echo site_url('lagu/delete/'); ?>' + id,
                        type: 'DELETE',
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status === 'success') {
                                alert('Lagu berhasil dihapus');
                                location.reload();
                            } else {
                                alert(res.message);
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat menghapus data');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
