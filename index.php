<?php
require 'vendor/autoload.php' ; 
require 'connectdb.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();


// route get /api/events
$app->get("/api/events", function (Request $request, Response $response) 
{
	$conn = connect_db();
			// test connection
	if ($conn->connect_error) 
	{
		$response = $response->withStatus(500);
		return $response;
	}
	//selection des tout les evenements
	$sql = "SELECT *
			FROM events";

	$result = $conn->query($sql);
	$data = array();

	if ($result == TRUE) 
	{
		foreach ($result as $row)
		{
			$data[] = $row;
		}
	}

	$conn->close();
	//data en format json
	$response = $response->withHeader('Content-Type','application/json');
	$response = $response->withStatus(200);
	$response = $response->withJson(array("events" => $data));

	return $response;
});

$app->get('/api/events/{id}', function (Request $request, Response $response) 
{

	$id = $request->getAttribute("id");
	$conn = connect_db();
			// test connection
	if ($conn->connect_error) 
	{
		$response = $response->withStatus(500);
		return $response;
	}
	$sql = "SELECT *
			FROM events
			WHERE cookie_id = $id";

	$result = $conn->query($sql);

	$data = array();

	if ($result == TRUE) 
	{
		foreach ($result as $row)
		{
			$data[] = $row;
		}
	}

	$conn->close();
	//data en format json
	$response = $response->withHeader('Content-Type','application/json');
	$response = $response->withStatus(200);
	$response = $response->withJson(array("events" => $data));

	return $response;
    
});

$app->get('/api/events/name/{name}', function (Request $request, Response $response) 
{
	$name = $request->getAttribute("name");
	$conn = connect_db();
			// test connection
	if ($conn->connect_error) 
	{
		$response = $response->withStatus(500);
		return $response;
	}
	$sql = "SELECT * 
			FROM events
			WHERE name = '$name'";

	$result = $conn->query($sql);

	$data = array();

	if ($result == TRUE) 
	{
		foreach ($result as $row)
		{
			$data[] = $row;
		}
	}

		$conn->close();
		//data en format json
		$response = $response->withHeader('Content-Type','application/json');
	    $response = $response->withStatus(200);
	    $response = $response->withJson(array("events" => $data));

	return $response;
});

$app->get('/api/events/minute/', function (Request $request, Response $response) 
{
	$conn = connect_db();
			// test connection
	if ($conn->connect_error) 
	{
		$response = $response->withStatus(500);
			return $response;
	}
	$sql = "SELECT * 
			FROM events
			ORDER BY (CreatedAt)";

	$result = $conn->query($sql);

	$data = array();

	if ($result == TRUE) 
	{
		foreach ($result as $row)
		{
			$data[] = $row;
		}
	}

	$conn->close();
	$response = $response->withHeader('Content-Type','application/json');
	$response = $response->withStatus(200);
	$response = $response->withJson(array("events" => $data));

	return $response;
});

$app->post('/api/events/', function (Request $request, Response $response) 
{

	$conn = connect_db();
			// test connection
	if ($conn->connect_error) 
	{
		$response = $response->withStatus(500);
		return $response;
	}

		// Create database
	$body = $request->getBody();
   	$param = json_decode($body);
  	$result = $conn->query("INSERT INTO events (name, referrer) VALUES ('$param->name', '$param->referrer');");
  		 	
});

$app->Delete('/api/events/{id}', function (Request $request, Response $response) 
{

	$id = $request->getAttribute("id");
	$conn = connect_db();
			// test connection
	if ($conn->connect_error) 
	{
		$response = $response->withStatus(500);
		return $response;
	}
	$sql = "DELETE FROM events
	       	WHERE cookie_id = '$id'";

	$result = $conn->query($sql);
    
});

$app->Put('/api/events/{id}', function (Request $request, Response $response) 
{

	$id = $request->getAttribute("id");
	$body = $request->getBody();
   	$param = json_decode($body);
	$conn = connect_db();
			// test connection
	if ($conn->connect_error) 
	{
		$response = $response->withStatus(500);
		return $response;
	}
	$sql = "UPDATE events 
	       	SET name = '$param->name',
	        referrer = '$param->referrer'
	        WHERE cookie_id = '$id'";

	$result = $conn->query($sql);
    
});


$app->run();

?>