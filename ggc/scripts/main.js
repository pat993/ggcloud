document.addEventListener('DOMContentLoaded', function() {
    // Control panel toggle
    $(document).ready(function() {
        setTimeout(function() {
            $(".control-wrapper").animate({
                height: "toggle"
            });
        }, 1000);
        
        $("#slide-toggle").click(function() {
            $(".control-wrapper").animate({
                height: "toggle"
            });
            
            // Check if the setting-box is currently visible
            if ($('.more-box').is(':visible')) {
                $('#input_show_more').trigger('click');
            }
        });

        $("#fullscreen-toggle").click(function() {
            document.body.requestFullscreen();
        });
    });

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

function stream_quality() {
    var e = document.getElementById("stream_quality").value;
    if (e == "1") {
        document.getElementById("in_bitrate").value = "1524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "2") {
        document.getElementById("in_bitrate").value = "2524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "3") {
        document.getElementById("in_bitrate").value = "5524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    }
}
