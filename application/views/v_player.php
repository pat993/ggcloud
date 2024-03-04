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

    <div id="preloader">
        <div class="spinner-border color-highlight-purple" role="status"></div>
    </div>

    <div class="DraggableDiv">
        <button class="btn btn-dark rounded-xl" style="width: 32px; height: 32px; font-size: 9px;" type="button" id="fullscreen-toggle"><i class="fas fa-expand"></i></button>
        <br>
        <button class="btn btn-dark rounded-xl mt-1" style="width: 32px; height: 32px; font-size: 9px" type="button" id="slide-toggle"><i class="fas fa-ellipsis-h"></i>
    </div>

    <script>
        // Function to show the hidden div after a delay
        function notification() {
            // Get the div element
            var notification = document.getElementById('notification');

            // Set a timeout to show the div after 10 seconds (10000 milliseconds)
            setTimeout(function() {
                notification.style.display = 'block';
            }, 10000);
        }

        window.onload = notification();

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

        // Set up a countdown timer
        var countdown = setInterval(function() {
            // Update the remaining time
            secondsDifference--;

            // Log the remaining time
            console.log("Countdown:", secondsDifference, "seconds remaining");

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

        // ifvisible.on("wakeup", function() {
        //     // go back updating data
        //     window.location.reload();
        // });
    </script>

</body>

</html>