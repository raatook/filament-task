import './bootstrap';
document.addEventListener('livewire:init', () => {
    Livewire.on('language-changed', () => {
        window.location.reload();
    });
});
