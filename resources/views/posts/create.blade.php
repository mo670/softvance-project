<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Create Post (Separate Form)</h4>
        <div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Home</a>
            @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('contacts.inbox') }}" class="btn btn-outline-secondary btn-sm">Contact inbox</a>
            @endif
            <a href="{{ route('posts.list') }}" class="btn btn-outline-primary btn-sm">Go To List</a>
            <form action="{{ route('logout') }}" method="post" class="d-inline">
                @csrf
                <button class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="createPostForm">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Body</label>
                    <textarea class="form-control" id="body" rows="4"></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    $('#createPostForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route('posts.store') }}',
            method: 'POST',
            data: {
                title: $('#title').val(),
                body: $('#body').val()
            },
            success: function () {
                $('#createPostForm')[0].reset();
                alert('Post created');
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Create failed');
            }
        });
    });
</script>
</body>
</html>
