<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * CuserController implements the CRUD actions for User model.
 */
class CuserController extends Controller
{
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::class,
        'actions' => [
          'delete' => ['post'],
        ],
      ],
      'access' => [
        'class' => \yii\filters\AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'actions' => ['index', 'view', 'create', 'update', 'delete', 'gennew'],
            'roles' => ['@']
          ],
          [
            'allow' => false
          ]
        ]
      ]
    ];
  }

  /**
   * Lists all Band models.
   * @return mixed
   */
  public function actionGennew()
  {

  }


}
