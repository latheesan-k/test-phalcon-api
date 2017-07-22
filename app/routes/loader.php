<?php

/**
 * Load all the route collection files.
 */
return call_user_func(function()
{
    // Init
    $collections = [];

    // Load collection files by scanning the dir
    foreach (glob(APP_DIR . 'routes/collections/*.php') as $collectionFile)
        $collections[] = include($collectionFile);

    // Finished
    return $collections;
});
