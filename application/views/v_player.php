<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" name="viewport" />
    <title>Connecting to Server ...</title>
    <link href="/ggc/main.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/ggc/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/ggc/styles/style.css">
    <link rel="stylesheet" type="text/css" href="/ggc/draggable.css">
    <link rel="stylesheet" type="text/css" href="/ggc/styles/loader.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.0/css/all.min.css" integrity="sha512-ykRBEJhyZ+B/BIJcBuOyUoIxh0OfdICfHPnPfBy7eIiyJv536ojTCsgX8aqrLQ9VJZHGz4tvYyzOM0lkgmQZGw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <link rel="icon" type="image/x-icon" href="https://ggcloud.id/images/ggc_play.png">

    <script>
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
    </script>

    <style>
        html,
        body {
            height: 100%;
        }

        .center2 {
            width: 200px;
            height: 200px;
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -100px;
            margin-left: -100px;
            text-align: center;
            color: white;
        }

        .ping {
            position: absolute;
            bottom: 0;
            right: 10px;
            z-index: 2;
            font-size: 8px;
            color: white;
        }
    </style>
</head>

<body style="background-color: black; position: relative" class="theme-dark" data-highlight="highlight-red" data-gradient="body-default">

    <div class="ping">
        <i class="fas fa-signal"></i>
    </div>

    <!-- <div id="preloader">
        <div class="spinner-border color-highlight-purple" role="status"></div>
    </div> -->

    <main class="inf_loader">
        <svg class="ip" viewBox="0 0 256 128" width="256px" height="128px" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="grad1" x1="0" y1="0" x2="1" y2="0">
                    <stop offset="0%" stop-color="#5ebd3e" />
                    <stop offset="33%" stop-color="#ffb900" />
                    <stop offset="67%" stop-color="#f78200" />
                    <stop offset="100%" stop-color="#e23838" />
                </linearGradient>
                <linearGradient id="grad2" x1="1" y1="0" x2="0" y2="0">
                    <stop offset="0%" stop-color="#e23838" />
                    <stop offset="33%" stop-color="#973999" />
                    <stop offset="67%" stop-color="#009cdf" />
                    <stop offset="100%" stop-color="#5ebd3e" />
                </linearGradient>
            </defs>
            <g fill="none" stroke-linecap="round" stroke-width="16">
                <g class="ip__track" stroke="#ddd">
                    <path d="M8,64s0-56,60-56,60,112,120,112,60-56,60-56" />
                    <path d="M248,64s0-56-60-56-60,112-120,112S8,64,8,64" />
                </g>
                <g stroke-dasharray="180 656">
                    <path class="ip__worm1" stroke="url(#grad1)" stroke-dashoffset="0" d="M8,64s0-56,60-56,60,112,120,112,60-56,60-56" />
                    <path class="ip__worm2" stroke="url(#grad2)" stroke-dashoffset="358" d="M248,64s0-56-60-56-60,112-120,112S8,64,8,64" />
                </g>
            </g>
        </svg>
    </main>

    <div class="DraggableDiv">
        <button class="btn btn-dark rounded-xl" style="width: 32px; height: 32px; font-size: 9px;" type="button" id="fullscreen-toggle"><i class="fas fa-expand"></i></button>
        <br>
        <button class="btn btn-dark rounded-xl mt-1" style="width: 32px; height: 32px; font-size: 9px" type="button" id="slide-toggle"><i class="fas fa-ellipsis-h"></i></button>
    </div>

    <div style="display: none;" class="center2" id="notification">
        <i style="font-size: 30px; padding-bottom: 10px" class="fas fa-exclamation-circle"></i>

        <br>
        <b>Oops</b>, tampaknya terjadi kendala jaringan, silahkan cek koneksi kamu dan refresh kembali.
        <br>
        Jika kendala masih terjadi silahkan hubungi Admin.
    </div>

    <script>
        // Function to show the hidden div after a delay
        function show_notification() {
            // Get the div element
            var notification = document.getElementById('notification');

            // Set a timeout to show the div after 10 seconds (10000 milliseconds)
            notification.style.display = 'block';
        }

        // window.onload = notification();

        //countdown script-----------------------------------

        // Get the current date and time
        var currentDate = new Date();

        // Define date2 string
        var date2 = '<?= $end_date; ?>';

        // Convert date2 string to a Date object
        var endDate = new Date(date2);

        // Calculate the difference in milliseconds
        var difference = endDate - currentDate;

        // Convert milliseconds to seconds
        var secondsDifference = difference / 1000;

        var status = "";
        var br = "";

        // Set up a countdown timer
        var countdown = setInterval(function() {
            // Update the remaining time
            secondsDifference--;

            br = document.getElementById("in_bitrate").value;

            console.log(br);

            if (br == "524288" && status == "wakeup") {
                document.getElementById("in_bitrate").value = "2524288";

                setTimeout(function() {
                    clickButton();
                }, 500);
            }

            // Log the remaining time
            // console.log("Countdown:", secondsDifference, "seconds remaining");

            // Check if the countdown has ended
            if (secondsDifference <= 0) {
                // Using querySelector to select the first element with the class 'device-view'
                var deviceView = document.querySelector('.device-view');

                // Setting the display property to 'none'
                deviceView.remove();

                // Refresh the page
                // clearInterval(countdown);
                location.reload();
            }
        }, 1000); // Update every second
    </script>

    <script src="/ggc/bundle.js"></script>
    <script type="text/javascript" src="/ggc/scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="/ggc/scripts/custom.js"></script>
    <script src="/ggc/draggable.js"></script>
    <script src="/scripts/ifvisible.js"></script>


    <script>
        // Check if the user is using a mobile browser
        function isMobileBrowser() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // Alert the user based on their browser type
        if (isMobileBrowser()) {
            $('.DraggableDiv').draggableTouch();
        }

        function waitForElm(selector) {
            return new Promise(resolve => {
                if (document.querySelector(selector)) {
                    return resolve(document.querySelector(selector));
                }

                const observer = new MutationObserver(mutations => {
                    if (document.querySelector(selector)) {
                        observer.disconnect();
                        resolve(document.querySelector(selector));
                    }
                });

                // If you get "parameter 1 is not of type 'Node'" error, see https://stackoverflow.com/a/77855838/492336
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            });
        }

        var count = 1; // Initialize counter variable
        var counterInterval; // Initialize interval variable

        // Function to update the counter
        function updateCounter() {
            count++;
        }

        // Function to display the count result and reset count
        function displayCountResult() {
            // alert("Count result: " + count);
            //console.log(count);
            if (count > 300) {
                window.location.reload(true); // Reload the page, forcing the cache to be ignored
            }

            count = 1;
        }

        // Update the counter every second
        function startCounter() {
            counterInterval = setInterval(updateCounter, 1000);
        }

        // Stop the counter
        function stopCounter() {
            clearInterval(counterInterval);
        }


        function clickButton() {
            var btn = document.getElementById("btn_change_video");
            if (btn) {
                btn.click();
            }
        }

        function setStream() {
            // br = document.getElementById("in_bitrate").value;

            // if (br == "524288") {
            document.getElementById("in_bitrate").value = "2524288";
            document.getElementById("in_fps").value = "40";

            setTimeout(function() {
                clickButton();
            }, 500);
        }
        // }

        //------------------------------------------------------------------------
        //Fungsi turunkan kualitas jika background process
        var b = "";
        var f = "";
        var w = "";
        var h = "";

        ifvisible.on("wakeup", function() {
            stopCounter();

            if (status == "blur") {
                displayCountResult(); // Display count result and reset count
            }

            document.getElementById("in_bitrate").value = b;
            document.getElementById("in_max_w").value = "1080";
            document.getElementById("in_max_h").value = "1080";
            // document.getElementById("in_fps").value = f;

            setTimeout(function() {
                clickButton();
            }, 500);

            status = "wakeup";

            // console.log("wakeup");
        });

        ifvisible.on("blur", function() {
            startCounter();

            c = document.getElementById("in_bitrate").value;

            if (c != "524288") {
                b = document.getElementById("in_bitrate").value;
                // f = document.getElementById("in_fps").value;
            }

            document.getElementById("in_bitrate").value = "524288";
            document.getElementById("in_max_w").value = "480";
            document.getElementById("in_max_h").value = "480";
            // document.getElementById("in_fps").value = "40";

            setTimeout(function() {
                clickButton();
            }, 500);

            status = "blur";

            // console.log("blur");
        });
        //---------------------------------------------------------------------------------

        // Function to load an image and measure load time
        function ping(url, callback) {
            var startTime = new Date().getTime();
            var img = new Image();

            img.onload = function() {
                var endTime = new Date().getTime();
                var timeDiff = endTime - startTime;
                callback(timeDiff);
            };

            img.src = url + '?' + new Date().getTime(); // Append a timestamp to bypass caching
        }

        var url = "https://hypercube.my.id/poweredby.png"; // Updated URL

        var b2 = "";
        var h2 = "";
        var w2 = "";

        // Function to ping every 2 seconds
        function pingEveryTwoSeconds() {
            ping(url, function(time) {
                var pingElement = document.querySelector('.ping');
                if (pingElement) {
                    pingElement.innerHTML = '<i class="fas fa-signal"></i>';
                    if (time > 500) {
                        pingElement.style.color = 'red';
                    }

                    if (time < 500) {
                        pingElement.style.color = '';
                    }
                }
            });
        }

        // Set up a timer to run the function repeatedly
        setTimeout(function() {
            // Define a function to check the condition and perform actions
            function checkAndPerformActions() {
                if (conn_status == "connected") {
                    document.querySelector('.ping').style.display = 'block';
                    document.getElementsByClassName('inf_loader')[0].style.display = 'none';
                    setTimeout(function() {
                        setStream();
                    }, 1000);
                    // Call the function every 2 seconds
                    setInterval(pingEveryTwoSeconds, 2000);

                    clearInterval(timer); // Stop the loop when condition is met
                }
                if (conn_status == "Disconnected") {
                    document.querySelector('.ping').style.display = 'none';
                    document.getElementsByClassName('inf_loader')[0].style.display = 'none';
                    show_notification();
                    // setTimeout(function() {
                    //     setStream();
                    // }, 500); // 20 seconds

                    clearInterval(timer); // Stop the loop when condition is met
                }
            }

            var timer = setInterval(checkAndPerformActions, 1000); // Run every second (1000 milliseconds)
        }, 1000); // 20 seconds

        // Set up a timer to stop the loop after 20 seconds
        setTimeout(function() {
            clearInterval(timer); // Stop the loop
        }, 20000); // 20 seconds
    </script>

</body>

</html>