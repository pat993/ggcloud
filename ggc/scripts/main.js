document.addEventListener('DOMContentLoaded', function() {
    // Control panel toggle
    const slideToggle = document.getElementById('slide-toggle');
    const controlWrapper = document.querySelector('.control-wrapper');
    
    slideToggle.addEventListener('click', function() {
        controlWrapper.style.display = controlWrapper.style.display === 'none' ? 'block' : 'none';
        const moreBox = document.querySelector('.more-box');
        if (moreBox && moreBox.style.display !== 'none') {
            document.getElementById('input_show_more').click();
        }
    });

    // Fullscreen toggle
    const fullscreenToggle = document.getElementById('fullscreen-toggle');
    fullscreenToggle.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    });

    // Mobile browser detection
    function isMobileBrowser() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    if (isMobileBrowser()) {
        $('.DraggableDiv').draggableTouch();
    }

    // Stream quality control
    function setStream(br) {
        document.getElementById("in_bitrate").value = br;
        setTimeout(function() {
            const changeVideoBtn = document.getElementById("btn_change_video");
            if (changeVideoBtn) {
                changeVideoBtn.click();
            }
        }, 1000);
    }

    // Visibility change handlers
    ifvisible.on("blur", function() {
        setStream("524288");
    });

    ifvisible.on("wakeup", function() {
        setStream("2524288");
    });
});