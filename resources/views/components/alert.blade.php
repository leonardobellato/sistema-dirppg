<div class="alert {{ $type === 'success' ? 'alert-sucesso' : 'alert-falha' }}">
    <span>{{ $message }}</span>
    <button class="alert-close" onclick="this.parentElement.remove()">×</button>
</div>