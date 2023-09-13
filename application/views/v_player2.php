<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" name="viewport" />
    <title>WS scrcpy</title>
    <link href="<?= base_url(); ?>/ws/main.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".slide-toggle").click(function() {
                $(".control-wrapper").animate({
                    width: "toggle"
                });
            });
        });
    </script>
</head>

<body>
    <button type="button" style="position: absolute; z-index: 2" class="slide-toggle">X</button>
    <button id="goFS" style="position: absolute; z-index: 2; bottom: 0" class="fullscreen">O</button>

    <script>
        var goFS = document.getElementById("goFS");
        goFS.addEventListener("click", function() {
            document.body.requestFullscreen();
        }, false);
    </script>

    <script>
        var dev_ip = "<?= $ip; ?>";
        var dev_port = "<?= $port; ?>";
    </script>

    <script src="<?= base_url(); ?>/ws/ggcloud.js"></script>
</body>

</html>