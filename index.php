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
                'file' => $path . $info['filename'],
                'name' => $info['filename'],
                'slug' => cleanStrRewrite($info['filename'])
            ];
        }
    }
}
$class = ['danger', 'primary', 'success', 'info', 'warning', 'default'];
$k = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sound Boards</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css" />-->
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
    <h1>Sound Boards</h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="btn-toolbar playlist">
                <div class="btn-group">
                    <button class="btn btn-default play-playlist" data-loading-text="<span class='glyphicon glyphicon-volume-up'></span>">
                        <span class="glyphicon glyphicon-play"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php foreach ($files as $title => $list) : ?>
        <h2><?=$title?></h2>
        <div class="row">
            <div class="col-xs-12">
                <div class="btn-toolbar">
                <?php foreach ($list as $i => $file) : ?>
                
                    <div class="btn-group" id="<?=$file['slug']?>" role="group">
                        <button class="btn btn-<?=$class[$k]?> play" type="button">
                            <?=$file['name']?>
                            <audio>
                                <source src="<?=$file['file']?>.ogg" type="audio/ogg" />
                                <source src="<?=$file['file']?>.mp3" type="audio/mpeg" />
                            </audio>
                        </button>
                        <button class="btn btn-<?=$class[$k]?> queue" type="button">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                        </button>
                    </div>

                <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php
	$k++;
	if ($k > count($class)) $k = 0;
	endforeach; ?>
    </div>
    <script>
        $(function() {
            var current,
                $playlist = $('.playlist');

            $('.container-fluid')
                .on('click', 'button.play', function(e) {
                    var $this = $(this),
                        id    = $this.attr('id');
                    e.preventDefault();
                    history.pushState(null, null, '#' + $this.parent().attr('id'));
                    if (current) {
                        current.pause();
                        current.currentTime = 0;
                    }
                    current = $this.find('audio')[0];
                    current.play();
                }).on('click', 'button.queue', function() {
                    var $this   = $(this),
                        $button = $this.prev('button'),
                        $clone  = $button.clone();
                        $div = $('<div/>').addClass('btn-group').append($clone)
                        .append(
                            $('<button/>')
                                .addClass($clone.attr('class') + ' remove')
                                .removeClass('play')
                                .append($('<span/>').addClass('glyphicon glyphicon-remove-sign'))
                        );
                    $div.appendTo($playlist);
                });
            $playlist.on('click', '.remove', function() {
                $(this).parent('.btn-group').remove();
            });
            $('.play-playlist').on('click', function() {
                var $this      = $(this),
                    $audioList = $playlist.find('audio'),
                    key        = 0;
                if ($audioList.length <= 0) return;
                $this.button('loading');
                
                $audioList.off('ended').on('ended', function() {
                    key++;
                    if (typeof $audioList[key] == "undefined") {
                        $this.button('reset');
                        return;
                    }
                    $audioList[key].play();
                });
                $audioList[0].play();
            });

            if (window.location.hash.length) {
                $(window.location.hash).find('button.play').trigger('click');
            }
        });
    </script>
</body>
</html>
