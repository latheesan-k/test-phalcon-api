<?php $appVersion = $di->get('config')->app->version; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Phalcon API <?php echo $appVersion ?></title>
</head>
<body>
<pre>
<b>Welcome to the Test Phalcon API <?php echo $appVersion ?> project.</b>

Usage:

    GET <b>/</b>
        <i>Returns this home page.</i>

    GET <b>/api/v1/files</b>
        <i>Returns a list of uploaded files.</i>

    GET <b>/api/v1/files/{id}</b>
        <i>Returns info about a single file record.</i>
        <i>Parameter {id} is the unique file record identifier (must be numeric).</i>

    POST <b>/api/v1/files</b>
        <i>To upload a new csv file record</i>
        <i>Request body should contain the full csv file contents</i>

    &bull; You must specify the desired response on every request
    &bull; Currently supported response types are: <b>json</b>
    &bull; Example request: <i>GET /api/v1/files/1?type=json</i>

<hr noshade="" size="1">
<i>&copy; 2017<?php echo date('y') != 2017 ? ' ~ ' . date('Y') : '' ?> Developed by Latheesan Kanesamoorthy. All rights reserved.</i>
<i>Github Project Likn: <a target="_blank" href="https://github.com/latheesan-k/test-phalcon-api">https://github.com/latheesan-k/test-phalcon-api</a></i>
</pre>
</body>
</html>