        <!-- Javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <?php
        global $scripts;
        foreach( $scripts as $script ){
            ?>
        <script src="<?= $script ?>"></script>
            <?php
        }
        ?>
        <script src="../scripts/example.js"></script>
    </body>
</html>