<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Posts List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Posts List (Separate Page)</h4>
        <div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Home</a>
            @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('contacts.inbox') }}" class="btn btn-outline-secondary btn-sm">Contact inbox</a>
            @endif
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Go To Create Form</a>
            <form action="{{ route('logout') }}" method="post" class="d-inline">
                @csrf
                <button class="btn btn-outline-danger btn-sm">Logout</button>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const title = prompt('Update title', $(this).data('title'));
        if (title === null) {
            return;
        }

        const body = prompt('Update body', $(this).data('body') || '');
        if (body === null) {
            return;
        }

        $.ajax({
            url: '/posts/' + id,
            method: 'PUT',
            data: {title: title, body: body},
            success: function () {
                table.ajax.reload();
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        if (!confirm('Are you sure?')) {
            return;
        }

        $.ajax({
            url: '/posts/' + $(this).data('id'),
            method: 'DELETE',
            success: function () {
                table.ajax.reload();
            }
        });
    });
</script>
</body>
</html>
