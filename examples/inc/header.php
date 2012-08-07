<?php
global $config;
?>
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
        <link href="../google-code-prettify/prettify.css" rel="stylesheet">
        
            
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
        
        <!-- Javascript
        ================================================== -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../google-code-prettify/prettify.js"></script>
        <?php
        global $scripts;
        foreach( $scripts as $script ){
            ?>
        <script src="<?= $script ?>"></script>
            <?php
        }
        ?>
        
        <script src="../scripts/example.js"></script>
        
        <script type="text/javascript">
        var BOZUKO_SERVER='<?= $config['server'] ?>'
          , BOZUKO_KEY='<?= $config['key'] ?>'
          ;
        jQuery(prettyPrint);
        </script>

    </head>
    <body class="example">