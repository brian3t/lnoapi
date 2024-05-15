<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Nav;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label'   => 'Users',
            'url'     => ['/user/admin/index'],
        ],
        [
            'label'   => 'Roles',
            'url'     => ['/rbac/role/index'],
            'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        ],
        [
            'label' =>  'Permissions',
            'url'   => ['/rbac/permission/index'],
            'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        ],
        [
            'label' => 'Create',
            'items' => [
                [
                    'label'   => 'New user',
                    'url'     => ['/user/admin/create'],
                ],
                [
                    'label' => 'New role',
                    'url'   => ['/rbac/role/create'],
                    'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
                ],
                [
                    'label' => 'New permission',
                    'url'   => ['/rbac/permission/create'],
                    'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
                ],
            ],
        ],
    ],
]) ?>
