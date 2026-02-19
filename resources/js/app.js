import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.showToast = function (message) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = message;
  toast.classList.remove('hidden');
  setTimeout(() => toast.classList.add('hidden'), 3000);
};


Alpine.start();
