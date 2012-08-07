<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

// add css and scripts
global $scripts, $styles, $config;

$scripts[] = $config['server'].'/js/highstock/1.1/highstock.js';
$scripts[] = $config['server'].'/js/widgets/reports.js';
$scripts[] = '../scripts/report.js';
$scripts[] = $config['server'].'/js/widgets/themechooser.js';

include(dirname(__FILE__).'/inc/header.php');

global $api;
?>
<p>This is a simple example of how to add the bozuko theme chooser.</p>
<p>First you need to include the themechooser script. It is located on your Bozuko server at <strong>/js/widgets/themechooser.js</strong>.
Then, all you need to do is add html5 data attributes to an input tag and the chooser will be rendered.</p>

<?php
ob_start();
?>

<input
    data-bozuko-widget="themechooser"
    data-server="<?= $config['server'] ?>"
    data-key="<?= $config['key'] ?>"
    type="text"
/>
<?php
$code = ob_get_clean();
?>
<pre><?= htmlentities( $code ) ?></pre>
<p>Will automatically be converted to:</p>
<p><?= $code ?></p>
<p>Additionally, this can be accomplished with the following javascript.</p>
<pre class="prettyprint linenums">
$('.your-input-selector').bozukothemechooser({
    server: YOUR_SERVER,
    key: YOUR_KEY
});
</pre>