@php
    // Include Viewer JS Hook
@endphp

@push('scripts')
    <script src="https://unpkg.com/viewerjs@1.11.6/dist/viewer.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/viewerjs@1.11.6/dist/viewer.min.css">
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('click', function (e) {
                if (e.target.closest('.filepond--action-remove-item')) {
                    return; // Ignore clicks directed at delete button
                }

                // Determine if what we clicked is something that should open a lightbox
                let isClickable = e.target.closest('.filepond--open-icon') || 
                                  e.target.closest('.filepond--image-preview') || 
                                  e.target.tagName.toLowerCase() === 'canvas';
                                  
                if (!isClickable) return;

                let fileContainer = e.target.closest('.filepond--item');
                if (!fileContainer) return;

                let openIcon = fileContainer.querySelector('.filepond--open-icon') || fileContainer.querySelector('a[target="_blank"]');
                if (!openIcon || !openIcon.href) return;

                e.preventDefault();
                e.stopPropagation();

                const group = fileContainer.closest('.fi-fo-file-upload');
                const icons = group ? group.querySelectorAll('.filepond--open-icon') : [openIcon];
                
                const urls = Array.from(icons).map(i => i.href);
                const currentIndex = urls.indexOf(openIcon.href);
                
                const ul = document.createElement('ul');
                urls.forEach(url => {
                    const li = document.createElement('li');
                    const img = document.createElement('img');
                    img.src = url;
                    li.appendChild(img);
                    ul.appendChild(li);
                });
                
                if (window.Viewer) {
                    const viewer = new window.Viewer(ul, {
                        initialViewIndex: currentIndex > -1 ? currentIndex : 0,
                        hidden: function () {
                            viewer.destroy();
                        },
                        toolbar: {
                            zoomIn: 4,
                            zoomOut: 4,
                            oneToOne: 4,
                            reset: 4,
                            prev: 4,
                            play: {
                                show: 4,
                                size: 'large',
                            },
                            next: 4,
                            rotateLeft: 4,
                            rotateRight: 4,
                            flipHorizontal: 4,
                            flipVertical: 4,
                        },
                    });
                    viewer.show();
                }
            }, true);
        });
    </script>
@endpush
