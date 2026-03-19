document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('descriptionContainer');
    const btn = document.getElementById('toggleDescriptionBtn');
    const gradient = document.getElementById('descriptionGradient');

    if (!container || !btn) return;
    
    if (container.scrollHeight <= 224) {
        btn.style.display = 'none';
        if (gradient) gradient.style.display = 'none';
    }

    btn.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (container.classList.contains('max-h-56')) {
            container.style.maxHeight = '224px';
            container.classList.remove('max-h-56');
            void container.offsetHeight;
            container.style.maxHeight = container.scrollHeight + 'px';
            
            if (gradient) gradient.style.opacity = '0';
            btn.innerHTML = 'Mostrar menos <span class="text-base leading-none transition-transform duration-300 rotate-180">↓</span>';
            setTimeout(() => {
                if (!container.classList.contains('max-h-56')) {
                    container.style.maxHeight = 'none';
                }
            }, 500);

        } else {
            container.style.maxHeight = container.scrollHeight + 'px';
            void container.offsetHeight;
            container.style.maxHeight = '224px';
            
            if (gradient) gradient.style.opacity = '1';
            btn.innerHTML = 'Mostrar mais <span class="text-base leading-none transition-transform duration-300">↓</span>';
            
            setTimeout(() => {
                if (container.style.maxHeight === '224px') {
                    container.style.maxHeight = null;
                    container.classList.add('max-h-56');
                }
            }, 500);
        }
    });
});