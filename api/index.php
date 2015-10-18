<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/cart', 'getcart');
$app->get('/cart/:id',	'getProduct');

//$app->get('/cart/search/:query', 'findByName');
//$app->post('/cart', 'addProduct');
//$app->put('/cart/:id', 'updateProduct');
//$app->delete('/cart/:id',	'deleteProduct');

$app->post('/neworder', 'addNewOrder');


$app->run();

function getcart() {
	$sql = "select * FROM Product ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$cart = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		// echo '{"Product": ' . json_encode($cart) . '}';
		echo json_encode($cart);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getProduct($id) {
	$sql = "SELECT * FROM Product WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$Product = $stmt->fetchObject();  
		$db = null;
		echo json_encode($Product); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addProduct() {
	error_log('addProduct\n', 3, '/var/tmp/php.log');
	$request = Slim::getInstance()->request();
	$Product = json_decode($request->getBody());
	$sql = "INSERT INTO Product (name, grapes, country, region, year, description, picture) VALUES (:name, :grapes, :country, :region, :year, :description, :picture)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $Product->name);
		$stmt->bindParam("grapes", $Product->grapes);
		$stmt->bindParam("country", $Product->country);
		$stmt->bindParam("region", $Product->region);
		$stmt->bindParam("year", $Product->year);
		$stmt->bindParam("description", $Product->description);
		$stmt->bindParam("picture", $Product->picture);
		$stmt->execute();
		$Product->id = $db->lastInsertId();
		$db = null;
		echo json_encode($Product); 
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addNewOrder(){
	
}

function updateProduct($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$Product = json_decode($body);
	$sql = "UPDATE Product SET name=:name, grapes=:grapes, country=:country, region=:region, year=:year, description=:description, picture=:picture WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $Product->name);
		$stmt->bindParam("grapes", $Product->grapes);
		$stmt->bindParam("country", $Product->country);
		$stmt->bindParam("region", $Product->region);
		$stmt->bindParam("year", $Product->year);
		$stmt->bindParam("description", $Product->description);
		$stmt->bindParam("picture", $Product->picture);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($Product); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteProduct($id) {
	$sql = "DELETE FROM Product WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByName($query) {
	$sql = "SELECT * FROM Product WHERE UPPER(name) LIKE :query ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$cart = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($cart);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {
	$dbhost="eu-cdbr-azure-west-c.cloudapp.net";
	$dbuser="b14d6fbca13269";
	$dbpass="d95aeda4";
	$dbname="ShoppingCart";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>