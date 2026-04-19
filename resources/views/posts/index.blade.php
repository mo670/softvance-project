<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Posts CRUD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Posts CRUD (AJAX + DataTable)</h4>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button class="btn btn-outline-danger btn-sm">Logout</button>
        </form>
    </div>

    <div class="card mb-4">
        <div class="card-header">Create / Edit Post</div>
        <div class="card-body">
            <form id="postForm">
                <input type="hidden" id="post_id">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="title" placeholder="Title" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="body" placeholder="Body">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" id="saveBtn" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="postsTable" class="display w-100">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    const table = $('#postsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('posts.datatable') }}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'body', name: 'body'},
            {data: 'author', name: 'author', orderable: false, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ]
    });

    $('#postForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#post_id').val();
        const payload = {
            title: $('#title').val(),
            body: $('#body').val()
        };

        let url = '{{ route('posts.store') }}';
        let method = 'POST';

        if (id) {
            url = '/posts/' + id;
            method = 'PUT';
        }

        $.ajax({
            url: url,
            method: method,
            data: payload,
            success: function () {
                $('#postForm')[0].reset();
                $('#post_id').val('');
                table.ajax.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        $('#post_id').val($(this).data('id'));
        $('#title').val($(this).data('title'));
        $('#body').val($(this).data('body'));
    });

    $(document).on('click', '.delete-btn', function () {
        if (!confirm('Are you sure?')) {
            return;
        }

        const id = $(this).data('id');
        $.ajax({
            url: '/posts/' + id,
            method: 'DELETE',
            success: function () {
                table.ajax.reload();
            }
        });
    });
</script>
</body>
</html>
