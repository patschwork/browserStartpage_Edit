<?php

namespace app\controllers;

use Yii;

class EditController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $data_file_path = "/tmp/dataDUMMMMMMYYYY.json";
        $data = "";
        $hint = "";

        if (array_key_exists('data_file_path', Yii::$app->params)) 
        {
            $data_file_path = Yii::$app->params['data_file_path'];
            if (file_exists($data_file_path)) {
                $data = file_get_contents($data_file_path);
            }
            else
            {
                $hint = "File data.json not found at $data_file_path";
            }
        }
        else
        {
            $hint = "Parameter 'data_file_path' not found in params.php!<br><br>Please add something like this:<br><pre>'data_file_path' => '/tmp/data.json',</pre>";
        }   

        if ($hint !== "")
        {
            Yii::$app->session->setFlash('error', $hint);
        }
        
        $model = new \yii\base\DynamicModel(['JSON']);
        $model->addRule(['JSON'], 'string', ['max' => 100000]);
        
        // $model->JSON = $this->getExample2();
        $model->JSON = $data;

		if (Yii::$app->request->post())
		{
			
            if ($model->load(Yii::$app->request->post()))
            {
                file_put_contents($data_file_path, $model->JSON);
            }
        }
        return $this->render('index',
            [
                 'model' => $model
                ,'hint' => $hint
            ]
        );
    }

    private function getExample1()
    {
        $example_schema = [
            'title' => 'Example JSON form',
            'type' => 'object',
            'properties' => [
                'name' => [
                    'title' => 'Full Name',
                    'type' => 'string',
                    'minLength' => 5
                ],
                'date' => [
                    'title' => 'Date',
                    'type' => 'string',
                    'format' => 'date',
                ],
            ],
        ];
        return $example_schema;
    }

    private function getExample2()
    {   
        $json = '{
            "name": "Jeremy Dorn",
            "age": 25,
            "favorite_color": "#ffa500",
            "gender": "male",
            "location": {
              "city": "San Francisco",
              "state": "CA",
              "citystate": "San Francisco, CA"
            },
            "pets": [
              {
                "type": "dog",
                "name": "Walter"
              }
            ]
          }';
        return $json;
    }

}
