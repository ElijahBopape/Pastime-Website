function previewImage(input, targetId) {
    const target = document.getElementById(targetId);
    if (!target || !input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        target.src = e.target.result;
        target.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}

function confirmAction(message) {
    return confirm(message || 'Are you sure?');
}
