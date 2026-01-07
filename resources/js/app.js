import './bootstrap';

document.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-confirm]");
  if (!btn) return;

  const msg = btn.getAttribute("data-confirm") || "Yakin?";
  if (!confirm(msg)) {
    e.preventDefault();
    e.stopPropagation();
  }
});