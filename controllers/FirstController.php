<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\components\Component;


class FirstController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    //get percentage to pass to Progress widget

    public function actionPercentage($id) {
        if (Yii::app()->request->isAjaxRequest) {
           $item = YourModelName::model()->findByPk($id); //obtain instance of object containing your function
           echo $item->getBuildPercentage(); //to return value in ajax, simply echo it   
        }
        }
        
    public function actionBackupget(){
        return $this->render('backup');
    }

    public function actionBackuppost(){
        $component = new Component();
        $component->init();
        $component->create();
        var_dump( $component->backupsFolder);
        return $this->render('success');
    }

    public function actionVue()
{
	// set the specific layout for pages that will render vue
	$this->layout = 'vue_main';

	// override bundle configuration if needed
	Yii::$app->assetManager->bundles = [];

	// render page
	return $this->render('vue_page');
}
    
    //test
    // public function actionHello($message){   
    //     $component = new Component();
    //     $component->init();
    //     return $this->render('hello',
    //     ['msg' => $message]
    // );
    // }

    public function actionTest(){

        echo '<pre>';
        var_dump(Yii::$app->request->get());
        echo '<pre>';

    //    $id = Yii::$app->request->get('id');
    //     if($id == 1){
    //         $percentage = 1;
    //         // echo $percentage;
    //         return $this->render('test', [
    //             'percentage' => $percentage,   
    //         ]);
    //     }
    //     else{
    //         $percentage = 20;
    //         // echo $percentage;
    //         return $this->render('test', [
    //             'percentage' => $percentage,
           
    //         ]);
    //     }


    }
}
