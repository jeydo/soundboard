<?php
require_once 'utils.php';
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
                'name' => $info['filename'],
                'slug' => cleanStrRewrite($info['filename'])
            ];
        }
    }
}
$class = ['', 'green', 'blue', 'yellow', 'lightblue'];
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
                <a href="#"
                    class="push <?=$class[$k]?>"
                    data-src="<?=$file['file']?>"
                    id="<?=$file['slug']?>"
                >
                    <?=$file['name']?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php
	$k++;
	if ($k > count($class)) $k = 0;
	endforeach; ?>
    </div>
    <script>
        $(function() {
            var audioList = {},
                current;
            $('a').on('click', function(e) {
                var $this = $(this),
                    id    = $this.attr('id');
                e.preventDefault();
                history.pushState(null, null, '#' + id);
                if (audioList[id] == undefined) {
                    audioList[id] = new Audio($this.data('src'));
                }
                if (current) {
                    current.pause();
                    current.currentTime = 0;
                }
                current = audioList[id];
                current.play();
            });

            if (window.location.hash.length) {
                $(window.location.hash).trigger('click');
            }
        });
    </script>
</body>
</html>
