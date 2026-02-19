<div id="toast"
    class="fixed left-1/2 top-6 z-50 hidden -translate-x-1/2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow">
</div>
<script>
    function showToast(message) {
        const toast = document.getElementById('toast');
        if (!toast) return;
        toast.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    @if (session('message'))
        showToast(@json(session('message')));
    @endif
</script>
