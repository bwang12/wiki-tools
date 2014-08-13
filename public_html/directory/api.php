<?php
    require '../../lib/vendor/autoload.php';
    require '../../lib/config.php';
    require 'lib/class-api.php';

    $api = new Api();

    function listtools() {
        global $api;

        header("Content-Type: application/json; charset=utf-8");
        header("Access-Control-Allow-Origin: *");

        $tools = array();

        foreach ($api->getAllTools() as $tool) {
            $tools[] = $tool->as_array();
        }

        echo json_encode($tools);
    }

    // When redirecting, first count it in the database so we can rank
    // the tools later on
    function redirect($id) {
        global $api;

        $tool = $api->getToolById($id);

        if (!$tool) {
            $url = "http://tools.wmflabs.org/hay/directory";
        } else {
            $tool->redirects = intval($tool->redirects) + 1;
            $tool->save();
            $url = $tool->url;
        }

        header("Location: $url", true, 301);
    }

    if (isset($_GET['redirect'])) {
        redirect($_GET['redirect']);
    } else {
        listtools();
    }