<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Lagu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Data Lagu</h2>
    <button class="btn btn-primary mb-3" onclick="showAddSongForm()">Tambah Lagu</button>
    <table id="songTable" class="table table-bordered">
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
            <?php foreach ($songs as $song): ?>
            <tr>
                <td><?= $song->id ?></td>
                <td><?= $song->JudulLagu ?></td>
                <td><?= $song->Penyanyi ?></td>
                <td><?= $song->Pencipta ?></td>
                <td><img src="<?= base_url('uploads/'.$song->Gambar) ?>" width="100"></td>
                <td>
                    <button class="btn btn-warning" onclick="showEditSongForm(<?= $song->id ?>)">Edit</button>
                    <button class="btn btn-danger" onclick="confirmDelete(<?= $song->id ?>)">Hapus</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Add/Edit Song -->
<div class="modal fade" id="songModal" tabindex="-1" aria-labelledby="songModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="songModalLabel">Form Lagu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="songForm" action="<?= base_url('song/insert_song') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="songId" name="id">
                    <div class="form-group mb-2">
                        <label for="JudulLagu">Judul Lagu</label>
                        <input type="text" class="form-control" id="JudulLagu" name="JudulLagu" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="Penyanyi">Penyanyi</label>
                        <input type="text" class="form-control" id="Penyanyi" name="Penyanyi" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="Pencipta">Pencipta</label>
                        <input type="text" class="form-control" id="Pencipta" name="Pencipta" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="Gambar">Gambar</label>
                        <input type="file" class="form-control" id="Gambar" name="Gambar">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTables & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#songTable').DataTable();
    });

    function showAddSongForm() {
        $('#songModalLabel').text('Tambah Lagu');
        $('#songForm').attr('action', '<?= base_url('song/insert_song') ?>');
        $('#songForm')[0].reset();
        $('#songId').val('');
        $('#songModal').modal('show');
    }

    function showEditSongForm(id) {
        $.ajax({
            url: '<?= base_url('song/edit_song/') ?>' + id,
            method: 'GET',
            success: function(response) {
                let song = JSON.parse(response);
                $('#songId').val(song.id);
                $('#JudulLagu').val(song.JudulLagu);
                $('#Penyanyi').val(song.Penyanyi);
                $('#Pencipta').val(song.Pencipta);
                $('#songForm').attr('action', '<?= base_url('song/update_song/') ?>' + id);
                $('#songModalLabel').text('Edit Lagu');
                $('#songModal').modal('show');
            }
        });
    }

    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            window.location.href = '<?= base_url('song/delete_song/') ?>' + id;
        }
    }
</script>

</body>
</html>
