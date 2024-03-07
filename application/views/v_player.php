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

    <link rel="icon" type="image/x-icon" href="https://ggcloud.id//images/ggc_play.png">

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
            top: 40%;
            left: 50%;
            margin-top: -100px;
            margin-left: -100px;
            text-align: center;
            color: white;
        }
    </style>
</head>

<body style="background-color: black; position: relative" class="theme-dark" data-highlight="highlight-red" data-gradient="body-default">

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
        <button class="btn btn-dark rounded-xl mt-1" style="width: 32px; height: 32px; font-size: 9px" type="button" id="slide-toggle"><i class="fas fa-ellipsis-h"></i>
    </div>

    <script>
        // Function to show the hidden div after a delay
        // function notification() {
        //     // Get the div element
        //     var notification = document.getElementById('notification');

        //     // Set a timeout to show the div after 10 seconds (10000 milliseconds)
        //     setTimeout(function() {
        //         notification.style.display = 'block';
        //     }, 10000);
        // }

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

        // Set up a countdown timer
        var countdown = setInterval(function() {
            // Update the remaining time
            secondsDifference--;

            br = document.getElementById("in_bitrate").value;

            if (br == "524288" && status == "wakeup") {
                document.getElementById("in_bitrate").value = "4024288";

                clickButton();
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
        $('.DraggableDiv').draggableTouch();

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
            if (count > 30) {
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
            br = document.getElementById("in_bitrate").value;

            if (br == "524288") {
                document.getElementById("in_bitrate").value = "4024288";

                clickButton();
            }
        }

        setTimeout(function() {
            if (conn_status == "connected") {
                document.getElementsByClassName('inf_loader')[0].style.display = 'none';


                // alert(conn_status);
                // var element = document.getElementById('inf_loader');
                // if (element) {
                //     element.remove();
                // } else {
                //     console.log("Element with id 'inf_loader' not found.");
                // }

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
                    document.getElementById("in_fps").value = f;

                    clickButton();

                    status = "wakeup";

                    // console.log("wakeup");
                });

                ifvisible.on("blur", function() {
                    startCounter();

                    c = document.getElementById("in_bitrate").value;

                    if (c != "524288") {
                        b = document.getElementById("in_bitrate").value;
                        f = document.getElementById("in_fps").value;
                    }

                    document.getElementById("in_bitrate").value = "524288";
                    document.getElementById("in_fps").value = "40";

                    clickButton();

                    status = "blur";

                    // console.log("blur");
                });

                setStream();
            }
        }, 1000);
    </script>

</body>

</html>