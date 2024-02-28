<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping Test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function measurePing() {
                var startTime = new Date().getTime();
                $('<img src="https://hypercube.my.id/kitty.png">').on('load', function() {
                    var endTime = new Date().getTime();
                    var pingTime = endTime - startTime;
                    $('#pingResult').text('Ping time: ' + pingTime + 'ms');
                }).on('error', function() {
                    $('#pingResult').text('Failed to ping Google.');
                });
            }

            // Call the function every 4 seconds
            setInterval(measurePing, 4000);
        });
    </script>
</head>

<body>
    <div id="pingResult"></div>
</body>

</html>