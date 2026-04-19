<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Contact messages</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Home</a>
            <a href="{{ route('posts.list') }}" class="btn btn-outline-primary btn-sm">Posts</a>
            <form action="{{ route('logout') }}" method="post" class="d-inline">
                @csrf
                <button class="btn btn-outline-danger btn-sm" type="submit">Logout</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="contactsTable" class="display w-100">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Full name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Message</th>
                    <th>Received</th>
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

    $('#contactsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('contacts.datatable') }}',
        order: [[0, 'desc']],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'full_name', name: 'full_name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'company', name: 'company'},
            {data: 'message', name: 'message', orderable: false, searchable: true},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ]
    });

    $(document).on('click', '.btn-delete-contact', function () {
        if (!confirm('Delete this message?')) {
            return;
        }

        const url = $(this).data('url');

        $.ajax({
            url: url,
            method: 'DELETE',
            success: function () {
                $('#contactsTable').DataTable().ajax.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Delete failed');
            }
        });
    });
</script>
</body>
</html>
