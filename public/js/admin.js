(() => {
    const modalOpeners = document.querySelectorAll('[data-modal-open]');
    const createModal = document.querySelector('[data-create-modal]');
    const detailModal = document.querySelector('[data-detail-modal]');
    const openModal = (modal) => { if (!modal) return; modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); document.body.style.overflow = 'hidden'; };
    const closeModal = (modal) => { if (!modal) return; modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); document.body.style.overflow = ''; };
    modalOpeners.forEach(button => button.addEventListener('click', () => openModal(createModal)));
    document.querySelectorAll('[data-modal-close]').forEach(button => button.addEventListener('click', () => closeModal(button.closest('.modal-overlay'))));
    [createModal, detailModal].forEach(modal => modal?.addEventListener('click', event => { if (event.target === modal) closeModal(modal); }));
    document.addEventListener('keydown', event => { if (event.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(closeModal); });

    const copyText = async (text, button) => {
        try {
            await navigator.clipboard.writeText(text);
            const previous = button?.textContent;
            if (button) { button.textContent = '✓ Copied'; setTimeout(() => button.textContent = previous, 1200); }
        } catch { window.prompt('Copy serial code:', text); }
    };
    document.querySelectorAll('[data-copy]').forEach(button => button.addEventListener('click', () => copyText(button.dataset.copy, button)));
    document.querySelector('[data-copy-list]')?.addEventListener('click', event => copyText(event.currentTarget.dataset.copyList.split(',').join('\n'), event.currentTarget));
    document.querySelectorAll('[data-serial-detail]').forEach(button => button.addEventListener('click', () => {
        detailModal.querySelector('[data-detail-code]').textContent = button.dataset.serialDetail;
        detailModal.querySelector('[data-detail-status]').textContent = button.dataset.status === 'used' ? 'Digunakan' : 'Belum Digunakan';
        detailModal.querySelector('[data-detail-created]').textContent = button.dataset.created;
        detailModal.querySelector('[data-detail-used]').textContent = button.dataset.used;
        detailModal.querySelector('[data-detail-note]').textContent = button.dataset.note || '—';
        detailModal.querySelector('[data-copy-detail]').dataset.copy = button.dataset.serialDetail;
        openModal(detailModal);
    }));
    detailModal?.querySelector('[data-copy-detail]')?.addEventListener('click', event => copyText(event.currentTarget.dataset.copy, event.currentTarget));
    document.querySelector('[data-password-toggle]')?.addEventListener('click', event => {
        const input = document.querySelector('#password');
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
        event.currentTarget.textContent = input.type === 'password' ? '◉' : '◎';
    });
    document.querySelector('[data-menu-toggle]')?.addEventListener('click', () => document.querySelector('#sidebar')?.classList.toggle('open'));
    document.querySelector('[data-global-search]')?.addEventListener('keydown', event => {
        if (event.key === 'Enter' && event.currentTarget.value.trim()) {
            const url = new URL('/serial-codes', window.location.origin);
            url.searchParams.set('q', event.currentTarget.value.trim());
            window.location.href = url.toString();
        }
    });
    document.addEventListener('keydown', event => {
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') { event.preventDefault(); document.querySelector('[data-global-search]')?.focus(); }
    });
})();
