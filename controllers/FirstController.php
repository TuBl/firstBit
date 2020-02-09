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

        
    public function actionBackup(){
        $component = new Component();
        $component->init();
        $create = $component->create(); 
        if($create != null){
             Yii::$app->session->setFlash('success', 'Files backed up succesfuly!');
            return true;
        }
        else{
            Yii::$app->session->setFlash('danger', 'Something went wrong!');
            return false;
        }

    }  

    public function actionProgress(){
        $progress = Yii::$app->request->get('progress');
        $progress  = $progress + 10;
        return $progress;
    }

    public function actionIndex(){
        $path    = '../tables';
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        return $this->render('index', [
        'file' => $files
        ]);
        // var_dump($file);

    }
    public function actionSuccess(){
        return $this->render('success');

    }
    public function actionFail(){
        return $this->render('fail');

    }

}
