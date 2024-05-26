<?php

namespace app\commands;

use soc\yiiuser\User\Factory\MailFactory;
use soc\yiiuser\User\Model\User;
use soc\yiiuser\User\Service\UserCreateService;
use soc\yiiuser\User\Traits\ContainerAwareTrait;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class CreateController extends Controller
{
    use ContainerAwareTrait;

    /**
     * This command creates a new user account. If no password is not set, an 8-char password will be generated. After
     * saving user to database, this command uses mailer component to send credentials (username and password) to user
     * via email. A role can be also assigned but it must exists previously on the database.
     *
     * @param string      $email    Email
     * @param string      $username Username
     * @param bool        $skipemail Skip Email
     * @param string|null $password The password. If null it will be generated automatically
     * @param string|null $role     Role to assign (must already exist)
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex($email, $username, $password = null, $role = null, bool $skipemail = false)
    {
        /** @var User $user */
        $user = $this->make(
            User::class,
            [],
            ['scenario' => 'create', 'email' => $email, 'username' => $username, 'password' => $password]
        );
        $mailService = MailFactory::makeWelcomeMailerService($user);
        if ($skipemail) {
          $this->assignRole($user, $role);
          return $this->stdout("User $username created successfully");
        }

        if ($this->make(UserCreateService::class, [$user, $mailService])->run()) {
            $this->stdout('User has been created' . "!\n", Console::FG_GREEN);

            if (null !== $role) {
                $this->assignRole($user, $role);
            }
        } else {
            $this->stdout('Please fix following errors:' . "\n", Console::FG_RED);
            foreach ($user->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }
    }

    protected function assignRole(User $user, $role)
    {
        $auth = Yii::$app->getAuthManager();
        if (false === $auth) {
            $this->stdout(
                Yii::t(
                    'app',
                    'Cannot assign role "{0}" as the AuthManager is not configured on your console application.',
                    $role
                ) . "\n",
                Console::FG_RED
            );
        } else {
            $userRole = $auth->getRole($role);
            if (null === $userRole) {
                $this->stdout(Yii::t('app', 'Role "{0}" not found. Creating it.', [$role]) . "!\n", Console::FG_GREEN);
                $userRole = $auth->createRole($role);
                $auth->add($userRole);
            }
            $auth->assign($userRole, $user->id);
        }
    }
}
