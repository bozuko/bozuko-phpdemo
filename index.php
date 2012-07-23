<?php
require('init.php');
$pages = array('Overview','Examples');
$cur = array_search(@$_REQUEST['page'], $pages);
if($cur===false) $cur = 0;
$page = $pages[$cur];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Bozuko Developer API PHP Demonstration &raquo; <?= $page ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
    
        <!-- Le styles -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet"> -->
        <link href="google-code-prettify/prettify.css" rel="stylesheet">
        <link href="style/demo.css" rel="stylesheet">
        
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="?">Bozuko API Demo</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <?php
                            foreach($pages as $i => $p):
                                $classes = array();
                                if($i == $cur) $classes[] = 'active';
                            ?>
                            
                            <li class="<?=implode(' ',$classes) ?>">
                                <a href="?page=<?= $p ?>"><?=$p?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h1><?= $page ?></h1>
            <?php
            include("pages/$page.php");
            ?>
        </div>
        
        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="google-code-prettify/prettify.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="scripts/demo.js"></script>
    </body>
</html>