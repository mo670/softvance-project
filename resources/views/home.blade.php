<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">

    <!-- ✅ Sidebar -->
     @auth
    <div class="bg-primary text-white p-3" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4">{{ config('app.name', 'Laravel') }}</h4>

        <ul class="nav flex-column">

            <li class="nav-item mb-2">
                <a href="/" class="nav-link text-white">🏠 Home</a>
            </li>

            @auth
            <li class="nav-item mb-2">
                <a href="{{ route('posts.list') }}" class="nav-link text-white">📊 Post</a>
            </li>

            @if(auth()->user()->hasRole('admin'))
            <li class="nav-item mb-2">
                <a href="{{ route('contacts.inbox') }}" class="nav-link text-white">📥 Contact inbox</a>
            </li>
            @endif
            @endauth

            @guest
            <li class="nav-item mb-2">
                <a href="{{ route('login') }}" class="nav-link text-white">🔐 Login</a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('register') }}" class="nav-link text-white">📝 Register</a>
            </li>
            @endguest

        </ul>
    </div>
     @endauth

    <!-- ✅ Main Content -->
    <div class="flex-grow-1">

        <!-- Topbar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-primary px-4">

    <span class="navbar-brand text-white fw-bold">
        Welcome
    </span>

    <div class="ms-auto d-flex align-items-center gap-2">

        @guest
            <a href="{{ route('login') }}" class="btn btn-light btn-sm">
                Login
            </a>

            <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">
                 Register
            </a>

             
        @endguest

        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-light btn-sm">
                    Logout
                </button>
            </form>
        @endauth

    </div>

</nav>

        <!-- Content -->
        <div class="container py-4">


            <div class="row">
                
                <!-- Contact form (JSON → POST /api/contact-message) -->
               @guest
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header fw-semibold">Contact Us</div>
                        <div class="card-body">
                            <div id="contactApiAlert" class="alert d-none" role="alert"></div>

                            <form id="contactApiForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="first_name">First name</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control" required maxlength="255">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="last_name">Last name</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" required maxlength="255">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" required maxlength="255">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="phone">Phone</label>
                                    <input type="text" id="phone" name="phone" class="form-control" required maxlength="20">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="company">Company <span class="text-muted">(optional)</span></label>
                                    <input type="text" id="company" name="company" class="form-control" maxlength="255">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="message">Message</label>
                                    <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary" id="contactSubmitBtn">Send message</button>
                            </form>
                        </div>
                    </div>
                </div>
               @endguest
                <!-- Right Panel -->
              
            </div>
        </div>
    </div>
</div>

@guest
<script>
(function () {
    const form = document.getElementById('contactApiForm');
    if (!form) return;

    const alertBox = document.getElementById('contactApiAlert');
    const submitBtn = document.getElementById('contactSubmitBtn');

    function showAlert(type, text) {
        alertBox.className = 'alert alert-' + type;
        alertBox.textContent = text;
        alertBox.classList.remove('d-none');
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        alertBox.classList.add('d-none');

        const companyVal = document.getElementById('company').value.trim();
        const payload = {
            first_name: document.getElementById('first_name').value.trim(),
            last_name: document.getElementById('last_name').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            message: document.getElementById('message').value.trim(),
        };
        if (companyVal !== '') {
            payload.company = companyVal;
        }

        submitBtn.disabled = true;

        try {
            const res = await fetch('{{ url('/api/contact-message') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json().catch(function () { return {}; });

            if (res.ok && data.success) {
                showAlert('success', data.message || 'Message sent successfully.');
                form.reset();
            } else if (res.status === 422 && data.errors) {
                const lines = Object.values(data.errors).flat().join(' ');
                showAlert('danger', lines || 'Validation failed.');
            } else {
                showAlert('danger', data.message || 'Something went wrong. Please try again.');
            }
        } catch (err) {
            showAlert('danger', 'Network error. Please try again.');
        } finally {
            submitBtn.disabled = false;
        }
    });
})();
</script>
@endguest

</body>
</html>