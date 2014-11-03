<?php
$home  = './sounds/';
$dirs  = new DirectoryIterator($home);
$files = [];
foreach ($dirs as $dir) {
    if ($dir->isDot()) continue;
    if ($dir->isDir()) {
        $filename         = $dir->getFilename();
        $files[$filename] = [];
        $path = $home . $filename . '/';
        foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDot()) continue;
            $info               = pathinfo($file->getFilename());
            $files[$filename][] = [
                'file' => $path . $info['basename'],
                'name' => $info['filename']
            ];
        }
    }
}
$class = ['', 'green', 'blue', 'yellow'];
$k = 0;
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sound Boards</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
    <h1>Sound Boards</h1>
    <?php foreach ($files as $title => $list) : ?>
        <h2><?=$title?></h2>
        <div class="row">
            <?php foreach ($list as $i => $file) :
            if ($i != 0 && $i % 6 == 0) : ?>
        </div>
        <div class="row">
            <?php endif; ?>
            <div class="col-md-2">
                <a href="#" class="push <?=$class[$k]?>" data-src="<?=$file['file']?>">
                    <?=$file['name']?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php $k++; endforeach; ?>
    </div>
    <script>
        $(function() {
            var audio;
            $('a').on('click', function(e) {
                e.preventDefault();
                if (audio) {
                    audio.pause();
                }
                audio = new Audio($(this).data('src'));
                audio.play();
            });
        });
    </script>
</body>
</html>
