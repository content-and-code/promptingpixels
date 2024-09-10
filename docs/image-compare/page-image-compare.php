<?php
/*
Template Name: Image Comparison
*/

get_header();
?>

<div class="container">
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <div class="upload-areas">
        <div class="upload-area" id="dropArea1">
            <input type="file" id="image1" accept="image/*" hidden>
            <p>Drag and drop your first image here</p>
            <p>or</p>
            <button class="upload-btn">Click to upload</button>
        </div>
        <div class="upload-area" id="dropArea2">
            <input type="file" id="image2" accept="image/*" hidden>
            <p>Drag and drop your second image here</p>
            <p>or</p>
            <button class="upload-btn">Click to upload</button>
        </div>
    </div>
    <div id="imageComparison" class="image-comparison">
        <img id="leftImage" src="" alt="Left Image">
        <img id="rightImage" src="" alt="Right Image">
        <div class="slider-handle">
            <div class="slider-line"></div>
            <div class="slider-button">
                <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 20 L5 25 L5 15 L15 20" fill="white"/> <!-- Left arrow pointing left (<) -->
                    <path d="M25 20 L35 25 L35 15 L25 20" fill="white"/> <!-- Right arrow pointing right (>) -->
                </svg>
            </div>
        </div>
        <div class="file-name left-file-name"></div>
        <div class="file-name right-file-name"></div>
        <button id="fullscreenBtn" class="fullscreen-btn">⛶</button>
    </div>
</div>

<style>
    body {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    .container { 
        max-width: 1200px; 
        width: 95%; 
        margin: 0 auto; 
        padding: 20px; 
    }
    .upload-areas { display: flex; justify-content: space-between; margin-bottom: 20px; }
    .upload-area { 
        width: 48%; 
        height: 200px; 
        border: 2px dashed #ccc; 
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .upload-area:hover { background-color: #f0f0f0; }
    .upload-area p { margin: 5px 0; }
    .upload-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    .image-comparison {
        position: relative;
        width: 100%;
        overflow: hidden;
    }
    .image-comparison img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .slider-handle {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 40px;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: ew-resize;
    }
    .slider-line {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 2px;
        background-color: white;
        transform: translateX(-50%);
    }
    .slider-button {
        width: 40px;
        height: 40px;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #rightImage {
        clip-path: inset(0 0 0 50%);
    }
    .file-name {
        position: absolute;
        top: 10px;
        font-size: 12px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 5px;
        border-radius: 3px;
    }
    .left-file-name { left: 10px; }
    .right-file-name { right: 10px; }
    .fullscreen-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 20px;
    }
    .image-comparison, .slider-handle {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea1 = document.getElementById('dropArea1');
    const dropArea2 = document.getElementById('dropArea2');
    const image1Input = document.getElementById('image1');
    const image2Input = document.getElementById('image2');
    const leftImage = document.getElementById('leftImage');
    const rightImage = document.getElementById('rightImage');
    const sliderHandle = document.querySelector('.slider-handle');
    const comparisonContainer = document.getElementById('imageComparison');
    const leftFileName = document.querySelector('.left-file-name');
    const rightFileName = document.querySelector('.right-file-name');
    const fullscreenBtn = document.getElementById('fullscreenBtn');

    function handleDrop(e, input) {
        e.preventDefault();
        e.stopPropagation();
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            input.files = e.dataTransfer.files;
            updateImage(input, file.name);
        }
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function updateImage(input, fileName) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const aspectRatio = img.width / img.height;
                    comparisonContainer.style.height = `${comparisonContainer.offsetWidth / aspectRatio}px`;
                    
                    if (input === image1Input) {
                        leftImage.src = e.target.result;
                        leftFileName.textContent = fileName;
                    } else {
                        rightImage.src = e.target.result;
                        rightFileName.textContent = fileName;
                    }
                    
                    comparisonContainer.style.display = 'block';
                }
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    [dropArea1, dropArea2].forEach((area, index) => {
        const input = index === 0 ? image1Input : image2Input;
        area.addEventListener('click', () => input.click());
        area.addEventListener('dragover', handleDragOver);
        area.addEventListener('drop', (e) => handleDrop(e, input));
        input.addEventListener('change', (e) => {
            if (e.target.files.length) {
                updateImage(input, e.target.files[0].name);
            }
        });
    });

    let isDragging = false;

    sliderHandle.addEventListener('mousedown', (e) => {
        isDragging = true;
        e.preventDefault();
    });

    document.addEventListener('mousemove', (e) => {
        if (isDragging) {
            e.preventDefault();
            const containerRect = comparisonContainer.getBoundingClientRect();
            const containerWidth = containerRect.width;
            const mouseX = e.clientX - containerRect.left;
            const percentage = (mouseX / containerWidth) * 100;
            updateSliderPosition(percentage);
        }
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
    });

    // Prevent default behavior for the entire document
    document.addEventListener('selectstart', (e) => {
        if (isDragging) {
            e.preventDefault();
        }
    });

    function updateSliderPosition(percentage) {
        percentage = Math.max(0, Math.min(100, percentage));
        sliderHandle.style.left = `${percentage}%`;
        rightImage.style.clipPath = `inset(0 0 0 ${percentage}%)`;
    }

    fullscreenBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            comparisonContainer.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable full-screen mode: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', () => {
        if (document.fullscreenElement) {
            comparisonContainer.style.height = '100vh';
        } else {
            const aspectRatio = leftImage.naturalWidth / leftImage.naturalHeight;
            comparisonContainer.style.height = `${comparisonContainer.offsetWidth / aspectRatio}px`;
        }
    });
});
</script>

<?php
get_footer();
?>