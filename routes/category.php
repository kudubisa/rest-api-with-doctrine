<?php

/**
*@SWG\Get(
*   path="/news_cat",
*   tags={"News Category"},
*   summary="View all news category",
*   description="News category search",
*   @SWG\Response(
*     response=200,
*     description="Respon berhasil"
*   ),
* ),
*/

$app->get("/news_cat", function() use($app,$conn, $client){

    $q = "SELECT * FROM news_cat ORDER BY id_cat ASC";

    $data = $conn->fetchAll($q); // we will be using this, because we want to get all of

    $response = $app->response;
    $response->setStatus(200);
    $response->headers->set('Content-Type','application/json');
    $response->write(json_encode($data));


    // echo json_encode($data);


    // $event_idiot = $client->getIdent($client->captureMessage('Try to send a message to raven sentry'));

    // echo '<p>Sentry test 1</p>';

    // // Create n error by looking for a file that isn't there
    // $file=fopen("welcome.txt","r");

});


/**
*@SWG\Get(
*  path="/news_cat/{id_cat}",
*  tags={"News Category"},
*  description="News category search",
*  summary="Search news category by id_cat",
*  @SWG\Parameter(
*    name="id_cat",
*    in="path",
*    description="News Category ID",
*    required=true,
*    type="number",
*  ),
*  @SWG\Response(
*    response=200,
*    description="OKE"
*  ),
* ),
*/

$app->get("/news_cat/:id", function ($id) use ($app, $conn) {
    $q = "SELECT * FROM news_cat WHERE id_cat = ?";

    $stmt = $conn->prepare($q);
    $stmt->bindValue(1,$id);
    $stmt->execute();
    $r = $stmt->fetchAll();

    $response = $app->response;
    $response->setStatus(200);
    $response->headers->set("Content-Type","application/json");
    $response->write(json_encode($r));

});

/**
 * @SWG\Post(
 *     path="/news_cat",
 *     operationId="addCat",
 *     tags={"News Category"},
 *     description="Creates a new category",
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="name_cat",
 *         in="formData",
 *         description="category to add",
 *         required=true,
 *         type="string"
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="cat response"
 *     )
 * )
 */
$app->post("/news_cat", function () use ($app, $conn){
    $request = $app->request;

    $conn->beginTransaction();

    try{
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
        $conn->commit();
    }catch(Exception $e){
        $conn->rollBack();
        echo "Transaction Failed: ".$e->getMessage();
    }


});

/**
 * @SWG\Put(
     path="/news_cat/{id_cat}",
     tags={"News Category"},
     summary="Update News Category",
     description="Update News Category By Id",
     @SWG\Parameter(
       name="id_cat",
       in="path",
       description="News Cateogry ID",
       required=true,
       type="integer"
     ),
     @SWG\Parameter(
       name="name_cat",
       in="formData",
       description="News Category Name",
       required=false,
       type="string"
     ),
     @SWG\Response(
       response=200,
       description="PUT OKE"
     )
 * )
 */

$app->put("/news_cat/:id", function ($id) use ($app,$conn) {
    $request = $app->request;

    $conn->beginTransaction();
    try{

        $q = "UPDATE news_cat set name_catw = ? WHERE id_cat = ?";
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
        $conn->commit();

    }catch(Exception $e){
        $conn->rollBack();
        echo "Transaction Failed : ".$e->getMessage();
    }



});

/**
 * @SWG\Delete(
     path="/news_cat/{id_cat}",
     tags={"News Category"},
     summary="Delete News Category",
     description="Delete the news Category by id",
     @SWG\Parameter(
       name="id_cat",
       in="path",
       description="News ID",
       required=true,
       type="integer"
     ),
     @SWG\Response(
       response=200,
       description="DELETE OKE"
     )
 * )
 */
$app->delete("/news_cat/:id", function($id) use($app,$conn){
    $request = $app->request;


    $conn->beginTransaction();

    try{
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
        $conn->commit();
    }catch(Exception $e){
        $conn->rollBack();
    }

});


// NEWS SECTION
// Here we will using query builder
// use Doctrine\DBAL\Connection#createQueryBuilder;
$qb = $conn->createQueryBuilder();


/**
    @SWG\get(
        path="/news",
        tags={"News Entry"},
        summary="View all News entry",
        description="View whole news entry by one call",
        @SWG\Response(
            response=200,
            description="GET OKE"
        )
    )
 */

$app->get("/news", function() use($app,$qb,$conn){
    $qb->select("*")
        ->from("news")
        ->orderBy("created_at","desc");

    $data = $conn->fetchAll($qb);

    $response = $app->response;
    $response->headers->set("Content-Type","application/json");
    $response->setStatus(200);
    $response->write(json_encode($data));

});

/**
    @SWG\Get(
        path="/news/{id}",
        tags={"News Entry"},
        summary="Get One news",
        description="Get one specific news entry by id",
        @SWG\Parameter(
            name="id",
            in="path",
            required=true,
            description="News ID",
            type="integer"
        ),
        @SWG\Response(
            response=200,
            description="GET OKE"
        )
    )
 */
$app->get("/news/:id", function($id) use($app, $conn){

    $stmt = $conn->executeQuery("SELECT * FROM news WHERE id IN (?)",
        array(array($id)),
        array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
    );

    $r = $stmt->fetchAll();

    $response = $app->response;
    $response->setStatus(200);
    $response->headers->set("Content-Type","application/json");
    $response->write(json_encode($r));
});

/**
    @SWG\Post(
        path="/news",
        tags={"News Entry"},
        summary="Insert news entry",
        description="Insert new news entry",
        @SWG\Parameter(
            name="title",
            in="formData",
            required=true,
            description="News Title",
            type="string"
        ),
        @SWG\Parameter(
            name="author",
            in="formData",
            required=true,
            description="News Author",
            type="string"
        ),
        @SWG\Parameter(
            name="text_content",
            in="formData",
            required=true,
            description="News text Content",
            type="string"
        ),
        @SWG\Response(
            response=200,
            description="POST OKE"
        )
    )
 */

$app->post("/news", function() use($app,$conn){
    $req = $app->request;
    $conn->beginTransaction();

    try{

        $stmt = $conn->prepare("INSERT INTO news(title, author, text_content) VALUES(?,?,?)");

        $title = $req->post('title');
        $author = $req->post('author');
        $text_content = $req->post('text_content');

        $stmt->bindValue(1, $title);
        $stmt->bindValue(2, $author);
        $stmt->bindValue(3, $text_content);

        $r = $stmt->execute();

        if($r){
            echo json_encode(array("success"=>1));
        }else{
            echo json_encode(array("success"=>0));
        }

        $conn->commit();
    }catch(Exception $e){
        $conn->rollBack();
        echo "Transaction failed: ".$e->getMessage();
    }


});

/**
    @SWG\Put(
        path="/news/{id}",
        tags={"News Entry"},
        summary="Update news",
        description="Update news entry by id",
        @SWG\Parameter(
            name="id",
            in="path",
            required=true,
            description="news id",
            type="integer"
        ),
        @SWG\Parameter(
            name="title",
            in="formData",
            required=true,
            description="News title",
            type="string"
        ),
        @SWG\Parameter(
            name="author",
            in="formData",
            required=true,
            description="News Author",
            type="string"
        ),
        @SWG\Parameter(
            name="text_content",
            in="formData",
            required=true,
            description="News Text Content",
            type="string"
        ),
        @SWG\Response(
            response=200,
            description="PUT OKE"
        )
    )
 */

$app->put("/news/:id", function($id) use($app,$conn){
    $req = $app->request;
    $conn->beginTransaction();
    try{

        $title = $req->post("title");
        $author = $req->post("author");
        $text_content = $req->post("text_content");

        $stmt = $conn->prepare("UPDATE news set title = ?, author = ?, text_content = ? WHERE id = ? ");

        $stmt->bindValue(1, $title);
        $stmt->bindValue(2, $author);
        $stmt->bindValue(3, $text_content);
        $stmt->bindValue(4, $id);

        $r = $stmt->execute();

        if($r){
            echo json_encode(array("success"=>1));
        }else{
            echo json_encode(array("success"=>0));
        }

        $conn->commit();

    }catch(Exception $e){
        $conn->rollBack();
        $error = $e->getMessage();
        echo json_encode(array("error" => $error));
    }
});

/**
    @SWG\Delete(
        path="/news/{id}",
        tags={"News Entry"},
        summary="Delete news entry",
        description="Delet news entry by id",
        @SWG\Parameter(
            name="id",
            in="path",
            required=true,
            description="News ID",
            type="integer"
        ),
        @SWG\Response(
            response=200,
            description="DELETE OKE"
        )
    )
 */

$app->delete("/news/:id", function($id) use($app,$conn){
    $conn->beginTransaction();

    try{

        $r = $conn->delete("news", array("id"=>$id));

        if($r){
            echo json_encode(array("success"=>1));
        }else{
            echo json_encode(array("success"=>0,"result"=>$r));
        }

        $conn->commit();
    }catch(Exception $e){
        $conn->rollBack();
        $error = $e->getMessage();
        echo json_encode(array("error"=>$error));
    }

});
