const tabs = document.querySelectorAll(".post-tab");
const panels = document.querySelectorAll(".post-panel");
const postTypeInput = document.getElementById('post_type');

tabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const target = tab.dataset.tab;

        // ativa aba
        tabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");

        // troca painel
        panels.forEach(panel => {
            panel.classList.toggle(
                "active",
                panel.dataset.panel === target
            );
        });
        
        // atualiza input hidden com o tipo de post
        if (postTypeInput) {
            postTypeInput.value = target;
        }
    });
});

// ============================================
// PREVIEW FUNCTIONS
// ============================================

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const placeholder = input.parentElement.querySelector('.media-placeholder');
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            
            if (placeholder) placeholder.style.display = 'none';
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImagePreview() {
    const input = document.getElementById('post-content-image');
    const placeholder = input.parentElement.querySelector('.media-placeholder');
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    input.value = ''; // Clear file input
    previewImg.src = '';
    preview.style.display = 'none';
    if (placeholder) placeholder.style.display = 'flex';
}

function previewVideo(input) {
    if (input.files && input.files[0]) {
        const video = document.getElementById('preview-video');
        const placeholder = input.parentElement.querySelector('.media-placeholder');
        const preview = document.getElementById('video-preview');
        
        const url = URL.createObjectURL(input.files[0]);
        video.src = url;
        
        if (placeholder) placeholder.style.display = 'none';
        preview.style.display = 'block';
    }
}

function removeVideoPreview() {
    const input = document.getElementById('post-content-video');
    const placeholder = input.parentElement.querySelector('.media-placeholder');
    const preview = document.getElementById('video-preview');
    const video = document.getElementById('preview-video');
    
    input.value = ''; // Clear file input
    URL.revokeObjectURL(video.src);
    video.src = '';
    preview.style.display = 'none';
    if (placeholder) placeholder.style.display = 'flex';
}

// ============================================
// ADD EVENT LISTENERS FOR PREVIEW
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('post-content-image');
    const videoInput = document.getElementById('post-content-video');
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            previewImage(this);
        });
    }
    
    if (videoInput) {
        videoInput.addEventListener('change', function() {
            previewVideo(this);
        });
    }
});
