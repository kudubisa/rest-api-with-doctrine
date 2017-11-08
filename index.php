<?php
require_once("vendor/autoload.php");
require_once("dbal.php");

//This is Slim Section
$app = new \Slim\Slim(
	array(
		"debug" => true,
		"mode" => "development"
	)
);


$app->get("/", function () use ($conn){
	// $app->render('add_news_cat.php');
	//echo "Welcome folks, this is great isn't it?";
	$out = "";
	$sql = "SELECT * FROM news_cat";
	$stmt = $conn->query($sql);

	while($row = $stmt->fetch()){
		$out .= $row['name_cat']."<br>";
	}

	echo $out;


});

$app->get("/news_cat", function() use($conn){
	$q = "SELECT * FROM news_cat order By id_cat asc";
	$r = $conn->query($q);

	while($row = $r->fetch()){
		$data[] = $row;
	}

	echo json_encode($data);
	$conn->close();
	//echo "This should show  all news category";
});

$app->get("/news_cat/:id", function($id) use($conn){
	$q = "SELECT * FROM news_cat WHERE id_cat = ?";
	
	$stmt = $conn->prepare($q);
	$stmt->bindValue(1,$id);
	$stmt->execute();
	$r = $stmt->fetchAll();

	echo json_encode($r); 
	$conn->close();
});

$app->post("/news_cat", function() use($app,$conn){
	$request = $app->request;


	$q = "INSERT INTO news_cat (name_cat) values(?)";
	$stmt = $conn->prepare($q);

	$name_cat = $request->post('name_cat');

	$stmt->bindValue(1, $name_cat);

	$stmt->execute();

	if($stmt){
		$data = array(
			"success" => 1
		);
	}else{
		$data = array(
			"success" => 0
		);
	}

	echo json_encode($data);

});

$app->put("/news_cat/:id", function($id) use($app,$conn){
	$request = $app->request;
	
	$q = "UPDATE news_cat set name_cat = ? WHERE id_cat = ?";
	$stmt = $conn->prepare($q);

	$name_cat = $request->params('name_cat');

	$stmt->bindValue(1, $name_cat);
	$stmt->bindValue(2, $id);

	

	$stmt->execute();

	if($stmt){
		$data = array(
			"success" => 1,
			"name_cat" => $name_cat
		);
	}else{
		$data = array(
			"success" => 0,
			"name_cat" => $name_cat
		);
	}

	echo json_encode($data);

});


$app->delete("/news_cat/:id", function($id) use($app,$conn){
	$request = $app->request;

	$q = "DELETE from news_cat WHERE id_cat = ?";
	$stmt = $conn->prepare($q);

	$stmt->bindValue(1,$id);

	$r = $stmt->execute();

	if($r){
		$data = array(
			"success" => 1
		);
	}else{
		$data = array(
			"success" => 0
		);
	}

	echo json_encode($data);

});

$app->run();







