document.getElementById('imageUpload').addEventListener('change', function(event) {
    const previewContainer = document.getElementById('preview');
    previewContainer.innerHTML = ''; // Clear previous previews

    const files = event.target.files;

    Array.from(files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('h-32', 'rounded-xl', 'shadow-md', 'object-cover');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});