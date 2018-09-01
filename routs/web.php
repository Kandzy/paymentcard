<?php
/**
 * Created by PhpStorm.
 * User: dkliukin
 * Date: 8/2/18
 * Time: 11:46 AM
 */

use \App\Controller\ValidationController;

$app->get('/', function ($request, $response)
{
    return $this->view->render($response, 'mainpage/main.twig');
})->setName("main.page");

$app->group('/payment', function (){
    $this->get("/card", function ($request, $response){
        $info =[
         "year" => date("Y"),
        ];
        return $this->view->render($response, 'validation/card_data.twig', compact("info"));
    })->setName("page.validation");

    $this->post('/validation', ValidationController::class.":index")->setName("validation");

//    $this->get('/validation/result', function ($request, $response){
//        $params = $request->getParams();
////        var_dump($params);
//        echo "result";
//    })->setName("validation.result");
});