<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Article List</title>
    <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<body class="bg-light p-4">
    <div class="container">
        <div class="card shadow-lg rounded-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Article List</h4>
                <div>
                    <a id="openModalBtn" class="btn btn-primary btn-sm">+ Add Article</a>
                    <a href="logout" class="btn btn-danger btn-sm">Logout</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Row -->
                            @foreach($articles as $list)
                            <tr>
                                <td>{{ $list['id'] ?? '' }}</td>
                                <td>{{ $list['title'] ?? '' }}</td>
                                <td>{{ $list['content'] ?? '' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="view(<?= $list['id']; ?>)">View</button>
                                    <button class="btn btn-sm btn-success" onclick="edit(<?= $list['id']; ?>)">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteArticle(<?= $list['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- Modal -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="articleForm">
                <input name="id" hidden />
                <div class="modal-header">
                    <h5 class="modal-title" id="articleModalLabel">Add Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="articleTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="articleTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="articleContent" class="form-label">Content</label>
                        <textarea class="form-control" id="articleContent" name="content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="submitArticle()" class="btn btn-primary">Save Article</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var modal = new bootstrap.Modal(document.getElementById('articleModal'));

        $('#openModalBtn').click(function() {
            $('#articleForm')[0].reset();
            $('#articleModalLabel').text('Add Article');
            $('#articleModal .modal-footer').show();
            modal.show();
        });

    });

    function view(id) {
        var modal = new bootstrap.Modal(document.getElementById('articleModal'));
        $('#articleForm')[0].reset();
        $('#articleModalLabel').text('View Article');
        getArticle(id);
        $('#articleModal .modal-footer').hide();
        modal.show();
    }

    function edit(id) {
        var modal = new bootstrap.Modal(document.getElementById('articleModal'));
        $('#articleForm')[0].reset();
        $('#articleModalLabel').text('Edit Article');
        getArticle(id);
        $('#articleModal .modal-footer').show();
        modal.show();
    }

    function submitArticle() {
        let id = $('input[name=id]').val();
        let path = '/articles' + ((id !== '' && id !== undefined) ? '/' + id : '');
        let formdata = $('#articleForm').serializeArray();
        $.ajax({
            url: window.location.origin + path,
            type: (id !== '' && id !== undefined) ? 'put' : 'post',
            data: formdata,
            datatype: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(resp) {
                let response = JSON.parse(resp);
                alert(response.msg)
                if (response.code == 'success') {
                    window.location.reload();
                }
            },
            error: function() {
                alert('Somrthing Went Wrong!')
            }
        });
    }

    function getArticle(id) {
        $.ajax({
            url: window.location.origin + '/article/' + id,
            type: 'get',
            success: function(resp) {
                let response = JSON.parse(resp);
                if (response.code == 'success') {
                    $('input[name=title]').val(response.data.title);
                    $('textarea[name=content]').val(response.data.content);
                    $('input[name=id]').val(response.data.id);
                }
            },
            error: function(e) {
                console.log(e)
                alert('Somrthing Went Wrong!')
            }
        });
    }

    function deleteArticle(id) {
        if (confirm('Are you sure want to delete!')) {
            $.ajax({
                url: window.location.origin + '/articles/' + id,
                type: 'delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(resp) {
                    let response = JSON.parse(resp);
                    alert(response.msg)
                    if (response.code == 'success') {
                        window.location.reload();
                    }
                },
                error: function() {
                    alert('Somrthing Went Wrong!')
                }
            });
        }
    }
</script>



</html>