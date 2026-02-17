<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-[var(--bg)] text-[var(--ink)]">
    <div class="mx-auto flex min-h-screen max-w-md items-center px-6">
        <div class="w-full rounded-2xl border border-[var(--line)] bg-[var(--card)] p-6 text-center shadow-sm">
            <p class="text-xs uppercase tracking-[0.2em] text-[var(--muted)]">404</p>
            <h1 class="mt-2 text-2xl font-semibold">Page not found</h1>
            <p class="mt-2 text-sm text-[var(--muted)]">The page you’re looking for doesn’t exist.</p>
            <a href="{{ route('booking') }}"
               class="mt-4 inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-2 text-sm text-white">
                Go to Booking
            </a>
        </div>
    </div>
</body>
</html>
