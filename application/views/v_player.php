<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title>Connecting to Server ...</title>
    <link rel="stylesheet" href="/asset/main.css">
    <link rel="stylesheet" href="/asset/styles/bootstrap.css">
    <link rel="stylesheet" href="/asset/styles/style.css">
    <link rel="stylesheet" href="/asset/draggable.css">
    <link rel="stylesheet" href="/asset/styles/loader.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.0/css/all.min.css" integrity="sha512-ykRBEJhyZ+B/BIJcBuOyUoIxh0OfdICfHPnPfBy7eIiyJv536ojTCsgX8aqrLQ9VJZHGz4tvYyzOM0lkgmQZGw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="icon" type="image/x-icon" href="/images/ggc_play.png">
    <link rel="stylesheet" href="/asset/styles/custom.css">
</head>

<body style="background-color: black; position: relative" class="theme-dark" data-highlight="highlight-red" data-gradient="body-default">
    <div class="battery">
        <div class="straight"></div>
        <div class="curve"></div>
        <div class="center"></div>
        <div class="inner"></div>
    </div>

    <div class="DraggableDiv">
        <button class="btn btn-dark rounded-xl" id="fullscreen-toggle">
            <i class="fas fa-expand" style="font-size: 10px;"></i>
        </button>
        <button class="btn btn-dark rounded-xl mt-1" id="audio-toggle">
            <i class="fas fa-volume-up" style="font-size: 10px;"></i>
        </button>
        <button class="btn btn-dark rounded-xl mt-1" id="sync-toggle">
            <i class="fas fa-sync" style="font-size: 10px;"></i>
        </button>
        <button class="btn btn-dark rounded-xl mt-1" id="slide-toggle">
            <i class="fas fa-ellipsis-h" style="font-size: 10px;"></i>
        </button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/asset/bundle.js"></script>
    <script src="/asset/scripts/bootstrap.min.js"></script>
    <script src="/asset/scripts/custom.js"></script>
    <script src="/asset/draggable.js"></script>
    <script src="/asset/scripts/ifvisible.js"></script>
    <script src="/asset/scripts/main.js"></script>
    <script src="/asset/mouse-sync.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi sistem dengan selector canvas yang diinginkan
            const mouseSync = initializeMouseSync('.touch-layer');
        });
    </script>

</body>

</html>