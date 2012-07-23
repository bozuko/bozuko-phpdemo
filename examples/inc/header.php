<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Bozuko Developer API PHP Demonstration &raquo; <?= $page ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
    
        <!-- Styles -->
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
            
        <?php
        global $styles;
        foreach( $styles as $style ){
            ?>
        <link href="<?= $style ?>" rel="stylesheet">
            <?php
        }
        ?>
            
        <!-- <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet"> -->
        <link href="../style/demo.css" rel="stylesheet">
        
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body class="example">