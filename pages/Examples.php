<?php

$examples = array(
    array(
        'title'         => 'List Pages',
        'file'          => 'page-list',
        'description'   => '
            <p>Get a list of all pages created with your API key.</p>
            <p>See the docs for the 
            <a href="https://playground.bozuko.com:8001/docs/api/#/links/pages" target="bozuko_docs">pages</a>
            link.</p>
        '
    ),
    array(
        'title'         => 'Create Page',
        'file'          => 'page-create',
        'description'   => '
            <p>Create a new page in the Bozuko system. You can create one with only a Facebook Page ID.</p>
            <p>See the docs for the 
            <a href="https://playground.bozuko.com:8001/docs/api/#/links/page" target="bozuko_docs">page link</a>
            and the
            <a href="https://playground.bozuko.com:8001/docs/api/#/objects/page" target="bozuko_docs">page object</a>
            for more details.
            </p>
        '
    ),
    array(
        'title'         => 'Update Page',
        'file'          => 'page-update',
        'description'   => '
            <p>Update a page in the Bozuko system.</p>
            <p>See the docs for the 
            <a href="https://playground.bozuko.com:8001/docs/api/#/links/page" target="bozuko_docs">page link</a>
            and the
            <a href="https://playground.bozuko.com:8001/docs/api/#/objects/page" target="bozuko_docs">page object</a>
            for more details.
            </p>
        '
    ),
    array(
        'title'         => 'Create Game',
        'file'          => 'game-create',
        'description'   => '
            <p>Create a game.</p>
            <p>See the docs for the 
            <a href="https://playground.bozuko.com:8001/docs/api/#/links/game" target="bozuko_docs">game link</a>
            and the
            <a href="https://playground.bozuko.com:8001/docs/api/#/objects/game" target="bozuko_docs">game object</a>
            for more details.
            </p>
        '
    ),
    array(
        'title'         => 'Update Game',
        'file'          => 'game-update',
        'description'   => '
            <p>Update a game.</p>
            <p>See the docs for the 
            <a href="https://playground.bozuko.com:8001/docs/api/#/links/game" target="bozuko_docs">game link</a>
            and the
            <a href="https://playground.bozuko.com:8001/docs/api/#/objects/game" target="bozuko_docs">game object</a>
            for more details.
            </p>
        '
    )
)

?>
<div class="tabbable tabs-left">
    <ul class="nav nav-tabs">
        <? foreach( $examples as $i => $example ): ?>
        <li<? if(!$i){ ?> class="active"<? } ?>>
            <a href="#<?= $example['file'] ?>" data-toggle="tab"><?= $example['title'] ?></a>
        </li>
        <? endforeach; ?>
    </ul>
    <div class="tab-content">
        <? foreach( $examples as $i => $example ): ?>
        <div class="tab-pane<? if(!$i){ ?> active<? } ?>" id="<?= $example['file'] ?>">
            <?= $example['description'] ?>
        
        
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#<?= $example['file'] ?>_example" data-toggle="tab">Example
                        </a>
                    </li>
                    <li>
                        <a href="#<?= $example['file'] ?>_src" data-toggle="tab">Source</a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane active" id="<?= $example['file'] ?>_example">
                        <iframe class="example-frame" src="examples/<?= $example['file'] ?>.php"></iframe>
                        <button class="btn refresh-btn"><i class="icon-refresh"></i> Refresh Example</button>
                    </div>
                    <div class="tab-pane" id="<?= $example['file'] ?>_src">
                        <pre class="prettyprint linenums"><?=
                            htmlentities(file_get_contents(dirname(__FILE__).'/../examples/'.$example['file'].'.php'))
                        ?></pre>
                    </div>
                </div>
                
            </div>
        </div>
        <? endforeach; ?>
    </div>
</div>