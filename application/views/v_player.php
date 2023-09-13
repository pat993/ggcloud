<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" name="viewport" />
    <title>WS scrcpy</title>
    <link href="/ws/main.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/ws/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/ws/styles/style.css">
    <link rel="stylesheet" type="text/css" href="/ws/draggable.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.0/css/all.min.css" integrity="sha512-ykRBEJhyZ+B/BIJcBuOyUoIxh0OfdICfHPnPfBy7eIiyJv536ojTCsgX8aqrLQ9VJZHGz4tvYyzOM0lkgmQZGw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#slide-toggle").click(function() {
                $(".control-wrapper").animate({
                    height: "toggle"
                });
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

        .center {
            margin: auto;
            width: 60%;
            border: 3px solid #73AD21;
            padding: 10px;
        }
    </style>
</head>

<body style="background-color: black; position: relative" class="theme-dark" data-highlight="highlight-red" data-gradient="body-default">

    <div id="preloader">
        <div class="spinner-border color-highlight" role="status"></div>
    </div>

    <div class="DraggableDiv">
        <button class="btn btn-dark rounded-xl" style="width: 32px; height: 32px; font-size: 9px;" type="button" id="fullscreen-toggle"><i class="fas fa-expand"></i></button>
        <br>
        <button class="btn btn-dark rounded-xl mt-1" style="width: 32px; height: 32px; font-size: 9px" type="button" id="slide-toggle"><i class="fas fa-ellipsis-h"></i>
    </div>

    <script>
        var dev_ip = "<?= $ip; ?>";
        var dev_port = "<?= $port; ?>";
    </script>

    <script src="/ws/ggcloud.js"></script>
    <script type="text/javascript" src="/ws/scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="/ws/scripts/custom.js"></script>
    <script src="/ws/draggable.js"></script>

    <script>
        $('.DraggableDiv').draggableTouch();
    </script>

</body>

</html>