<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once('v1.inc');
header('Content-Type: application/json');
$path = $_REQUEST["q"];
$returnObject = null;

function exception_error_handler( $severity, $message, $file, $line ) 
{
    if ( !( error_reporting() & $severity ) ) {
        // This error code is not included in error_reporting
        return;
    }
    
    http_response_code(500);
    return array(
    "error" => $severity . $message . $file . $line,
    );
}
set_error_handler( "exception_error_handler" );

echo json_encode(api_router($path));
# ---------------------------------------------------------------------------
function api_router($path)
{
    $path_elements = explode('/', $path);
    $api_version = array_shift($path_elements);
    if($api_version !== 'v1')
    {
        http_response_code(500);
        return array(
            "error" => "Invalid API version",
        );
    }
    $function_path = array($api_version);
    $params = array();
    $index = 0;
    foreach($path_elements as $element)
    {
        if($index % 2 == 0)
            array_push($function_path, $element);
        else
            array_push($params, $element);
        $index += 1;
    }
    array_push($function_path, $_SERVER['REQUEST_METHOD']);
    $function = implode('_', $function_path);
    if(!function_exists($function))
    {
        http_response_code(500);
        return array(
            "error" => "API path $path not found",
        );
    }
    try
    {
        return $function($params);
    }
    catch(Exception $exception)
    {
        http_response_code(400);
        return array(
            "error" => "The following error occurred: " . $exception->getMessage(),
        );
    }
}
