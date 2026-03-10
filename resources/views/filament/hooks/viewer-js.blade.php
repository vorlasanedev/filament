@php
    // Include Viewer JS Hook
@endphp

@push('scripts')
    <script src="https://unpkg.com/viewerjs@1.11.6/dist/viewer.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/viewerjs@1.11.6/dist/viewer.min.css">
    
    <style>
        /* Allow clicking thumbnails even when the form or field is disabled */
        .fi-fo-file-upload,
        .fi-fo-file-upload[disabled],
        fieldset[disabled] .fi-fo-file-upload {
            pointer-events: auto !important;
        }
        
        .filepond--root[data-disabled="true"] .filepond--item {
            cursor: pointer;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('click', function (e) {
                if (e.target.closest('.filepond--action-remove-item')) {
                    return; // Ignore clicks directed at delete button
                }

                // Determine if what we clicked is something that should open a lightbox
                let fileContainer = e.target.closest('.filepond--item');
                if (!fileContainer) return;
                
                let isClickable = e.target.closest('.filepond--open-icon') || 
                                  e.target.closest('.filepond--image-preview') || 
                                  e.target.tagName.toLowerCase() === 'canvas' ||
                                  e.target.tagName.toLowerCase() === 'img';
                                  
                if (!isClickable) return;

                const group = fileContainer.closest('.fi-fo-file-upload');
                if (!group) return;

                e.preventDefault();
                e.stopPropagation();

                let urls = [];
                let currentIndex = 0;

                // Attempt to fetch URLs robustly from Alpine state
                if (window.Alpine && group.hasAttribute('x-data')) {
                    const alpineData = Alpine.$data(group);
                    if (alpineData && alpineData.fileKeyIndex) {
                        urls = Object.values(alpineData.fileKeyIndex).map(f => f.url).filter(Boolean);
                    }
                }

                // Fallback to DOM parsing if Alpine state is missing or empty
                if (urls.length === 0) {
                    const icons = group.querySelectorAll('.filepond--open-icon, a[target="_blank"]');
                    urls = Array.from(icons).map(i => i.href).filter(Boolean);
                }

                if (urls.length === 0) return; // No images found

                // Find index of clicked item
                const clickedIcon = fileContainer.querySelector('.filepond--open-icon') || fileContainer.querySelector('a[target="_blank"]');
                if (clickedIcon && clickedIcon.href) {
                    currentIndex = urls.indexOf(clickedIcon.href);
                } else {
                    // Estimate index by counting sibling .filepond--item elements preceding it
                    let sibling = fileContainer;
                    let count = 0;
                    while ((sibling = sibling.previousElementSibling) != null) {
                        if (sibling.classList.contains('filepond--item')) {
                            count++;
                        }
                    }
                    if (count < urls.length) currentIndex = count;
                }

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
