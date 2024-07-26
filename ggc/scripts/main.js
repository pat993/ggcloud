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

let bitrate;
let blurStartTime = null; // To keep track of the time when the page was blurred
const blurThreshold = 60000; // 1 minute in milliseconds

// Visibility change handlers
ifvisible.on("blur", function() {
    setStream("524288");

    // Record the time when the page is blurred
    blurStartTime = Date.now();
});

ifvisible.on("wakeup", function() {
    if (blurStartTime) {
        const blurDuration = Date.now() - blurStartTime;
        
        // Check if the blur duration was more than 1 minute
        if (blurDuration >= blurThreshold) {
            // Remove elements with the class name 'device-view'
            removeDeviceViewElements();
            
            // Refresh the page
            location.reload();
        } else {
            // Set stream based on bitrate if blur duration was less than 1 minute
            if (bitrate) {
                setStream(bitrate);
            } else {
                setStream("2524288");
            }
        }
        
        // Reset blurStartTime after processing wakeup event
        blurStartTime = null;
    }
});

function setStream(value) {
    // Your logic to set the stream goes here
    console.log(`Stream set to: ${value}`);
}

function removeDeviceViewElements() {
    // Select all elements with the class 'device-view'
    const deviceViewElements = document.querySelectorAll('.device-view');
    deviceViewElements.forEach(element => {
        element.remove();
        console.log("Device view element removed from the DOM.");
    });
}

function stream_quality() {
    var e = document.getElementById("stream_quality").value;
    if (e == "1") {
        bitrate = "1524288"
        document.getElementById("in_bitrate").value = "1524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "2") {
        bitrate = "3524288"
        document.getElementById("in_bitrate").value = "3524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "3") {
        bitrate = "10524288"
        document.getElementById("in_bitrate").value = "10524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    }
}
