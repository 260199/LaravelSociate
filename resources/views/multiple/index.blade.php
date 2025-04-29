<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Multiple Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Upload Multiple Images</h2>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload Gambar</button>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('multiple.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="image-inputs">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="file" name="images[0][image_path]" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="images[0][desc]" class="form-control" placeholder="Deskripsi" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-more" class="btn btn-sm btn-secondary">+ Tambah</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let index = 1;
    $('#add-more').click(function () {
        $('#image-inputs').append(`
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="file" name="images[${index}][image_path]" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="images[${index}][desc]" class="form-control" placeholder="Deskripsi" required>
                </div>
            </div>
        `);
        index++;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
