<?php
global $api;
try {
    $pages = $api->call('/pages');
}catch(Exception $e){
    print_r($e);
}

$files = array(
    'init',
    'examples/inc/helpers',
    'lib/Bozuko/Api',
    'lib/Bozuko/Api/Request',
    'lib/Bozuko/Api/Response',
    'lib/Bozuko/Api/Exception'
);

?>

<p>This site has been created to provide developers with some functioning example code
that demonstrates the Bozuko Developer API. Our examples have been written in PHP, but
the general concepts will translate across languages.
</p>

<p>The full code for this site can also be <a href="https://github.com/bozuko/bozuko-phpdemo" target="_blank">found on github</a>.

<h3>API Wrapper</h3>

<p>An API Wrapper is necessary for consistent communication between your server and the
Bozuko service. The following files are provided to show our PHP implementation. If
you are not using PHP, you should implement the code in a similar way.
</p>

<p>The <strong>init</strong> file shows how we set up our API wrapper that is used in all of our examples.
<strong>helpers</strong> contains some common functions used in the examples
</p>

<ul class="nav nav-tabs">
    <?php
    foreach($files as $i => $file):
        $name = str_replace('/','_', str_replace(array('examples/inc/','lib/'), '', $file));
    ?>
    <li <?= !$i ? 'class="active"' : '' ?>><a href="#<?= $name ?>" data-toggle="tab"><?= $name ?></a></li>
    <?php
    endforeach;
    ?>
</ul>

<div class="tab-content">
    <?php
    foreach($files as $i => $file):
        $name = str_replace('/','_', str_replace(array('examples/inc/','lib/'), '', $file));
    ?>
    <div class="tab-pane<?= !$i ? ' active' : '' ?>" id="<?= $name ?>">
        <pre class="prettyprint linenums"><?=
        htmlentities(file_get_contents(dirname(__FILE__).'/../'.$file.'.php'))
        ?></pre>
    </div>
    <? endforeach;  ?>
</div>
