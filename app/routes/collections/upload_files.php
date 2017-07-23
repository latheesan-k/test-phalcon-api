<?php

/**
 * Construct a route collection.
 */
return call_user_func(function()
{
    // Initialise a new route collection
    $filesCollection = new \Phalcon\Mvc\Micro\Collection();

    // Configure route collection
    $filesCollection
        ->setPrefix('/api/v1/files')
        ->setHandler('\TestPhalconApi\Controllers\FilesController')
        ->setLazy(true);

    // Configure access control headers
    $filesCollection->options('/', 'baseEndpoint');
    $filesCollection->options('/{upload_file_id}', 'recordEndpoint');

    // Configure routes
    $filesCollection->get('/', 'getList');              // List files
    $filesCollection->get('/{file_id:[0-9]+}', 'getInfo');   // Load a single file info
    $filesCollection->post('/', 'createRecord');        // Create a new file

    // Finished
    return $filesCollection;
});
