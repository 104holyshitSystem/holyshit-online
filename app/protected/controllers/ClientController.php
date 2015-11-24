<?php

/**
 * Created by PhpStorm.
 * User: Apple
 * Date: 15/11/24
 * Time: 下午10:03
 */
class ClientController extends CController
{

    public function actionIndex()
    {
        $this->renderPartial('index');
    }
}