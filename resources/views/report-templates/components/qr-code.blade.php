<div id="{{ $id }}" class="d-flex flex-column align-items-center mb-3">
    <img
        id="qr-code"
        src="https://api.qrserver.com/v1/create-qr-code/?data={{ $content }}"
        alt="{{ $alt ?? $content }}"
        title="{{ $title ?? $content }}"
        width="{{ $size ?? 64 }}"
        height="{{ $size ?? 64 }}"
    >
</div>
