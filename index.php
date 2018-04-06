<?php
require 'vendor/autoload.php';
require 'vendor/slim/slim/Slim/Slim.php';
require 'connectdb.php';
\Slim\Slim::registerAutoloader();



$app = new \Slim\Slim();

$app->get('/api/event', function () 
{
	$conn = connect_db();
		// test connection
	if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

	$sql = "SELECT *
			FROM events";

			$result = $conn->query($sql);

	if ($result == TRUE) 
		{
			foreach ($result as $row) 
				{
					echo $row['name'] ."  ". $row['referrer']."  ". $row['CreatedAt']."<br>";
				}
		} 
	else 
		{
			echo "liste vide".$conn->error;
		}
	$conn->close();
    
});

$app->get('/api/event/:id', function ($id) 
{
	$conn = connect_db();
		// Check connection
	if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}
		
		$sql = "SELECT * ,count(*) as nb
				FROM events
				WHERE cookie_id = $id";

		
		$result = $conn->query($sql);

		foreach ($result as $row) 
					{
						if ($row['nb'] >= 1) 
					{
						echo $row['name'] ."  ". $row['referrer']."  ". $row['CreatedAt']."<br>";
					}	 
		else 
			{
				echo "liste vide".$conn->error;
			}
					}

	$conn->close();	
    
});

$app->get('/api/event/name/:name', function ($name) 
{
	$conn = connect_db();
		// Check connection
	if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		// Create database
	$sql = "SELECT * ,  COUNT(*) as nb
			FROM events
			WHERE name = '$name'";

			$result = $conn->query($sql);

	if ($result == TRUE) 
		{
			foreach ($result as $row) 
				{
					echo $row['name'] ."  ". $row['referrer']."  ". $row['CreatedAt']."<br>";
					echo $row['nb'];
				}
		} 
	else 
		{
			echo "liste vide".$conn->error;
		}
	$conn->close();
});


$app->get('/api/event/minute/', function () 
{
	$conn = connect_db();
		// Check connection
	if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		// Create database
	$sql = "SELECT * 
			FROM events
			ORDER BY (CreatedAt)";

			$result = $conn->query($sql);

	if ($result == TRUE) 
		{
			foreach ($result as $row) 
				{
					echo $row['name'] ."  ". $row['referrer']."  ". $row['CreatedAt']."<br>";
				}
		} 
	else 
		{
			echo "liste vide".$conn->error;
		}
	$conn->close();
});

$app->post('/api/event/', function () use($app)
{
	$conn = connect_db();
		// Check connection
	if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}
 
    $json = $app->request->getBody();
    $params = json_decode($json, true);
    if ( (isset($params['name'])) && (isset($params['referrer'])))
	    {
	        $name = $params['name'];
	        $referrer = $params['referrer'];
	        if ((!empty($name)) && (!empty($referrer)))
	           	{
	        	   $sql = "INSERT INTO events (name, referrer)
					VALUES ('$name', '$referrer')";

					if ($conn->query($sql) == TRUE) 
						{
	    					echo "New record created successfully";
						} 
					else 
						{
	    					echo "Error: " . $sql . "<br>" . $conn->error;
						}
	           	}
	 	}
    
	$conn->close();                
});

$app->post('/api/event/put/:id', function ($id) use($app)
{
	$conn = connect_db();
		// Check connection
	if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}
 
    $json = $app->request->getBody();
    $params = json_decode($json, true);
    if ((isset($params['name'])) && (isset($params['referrer'])))
	    {
	        $name = $params['name'];
	        $referrer = $params['referrer'];
	        if ((!empty($name)) && (!empty($referrer)))
	           	{
	        	   $sql = "UPDATE events 
	        	   			SET name = '$name',
	        	   				referrer = '$referrer'
	        	   			WHERE cookie_id = '$id'";

					if ($conn->query($sql) == TRUE) 
						{
	    					echo "evenement modifier avec succees";
						} 
					else 
						{
	    					echo "Error: " . $sql . "<br>" . $conn->error;
						}
	           	}
	 	}
    
	$conn->close();                
});

$app->get('/api/event/delete/:id', function ($id)
{
	$conn = connect_db();
		// Check connection
	if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

	$sql = "DELETE FROM events
	       	WHERE cookie_id = '$id'";

	if ($conn->query($sql) == TRUE) 
		{
	    	echo "evenements supprimer avec succees";
		} 
	else 
		{
	    	echo "Error: " . $sql . "<br>" . $conn->error;
		}
	          

    
	$conn->close();                
});

$app->run();


?>
