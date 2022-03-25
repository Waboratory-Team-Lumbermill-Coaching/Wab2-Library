<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/6/2022
 * Time: 11:37 AM
 */

namespace App\Http\Users\Actions;


use App\Helpers\AppHelper;
use App\Helpers\NotificationHelper;
use App\Http\User\UserService;

class LoginAction extends \Base\Actions\CreateAction {
    /**
     * @var UserService
     */
    protected $service;

    protected $rules = [
        'username' => 'required|exists:users,username',
    ];

    protected $messages = [
        'username' => [
        ],
    ];

    public function __construct(UserService $service) {
        parent::__construct($service);

        $existsMessage = 'Username does not exist. You might want to {{register}} first?';
        $existsMessage = AppHelper::parseTemplate(
            'register',
            AppHelper::renderTag('a', 'register', [
                'href' => url('register')
            ]),
            $existsMessage
        );

        $this->messages['username'] = array_merge($this->messages['username'], [
            'exists' => $existsMessage
        ]);
    }

    /**
     * @param $data
     * @return bool|void
     * @throws \Exception
     */
    protected function handleData($data) {
        // TODO: Implement handleData() method.
        $user = $this->service->authenticate($data);
        if($user) {
            $success = AppHelper::storeAuth($user);
            return AppHelper::redirect('profile');
        }

        NotificationHelper::error('Wrong password. Did you forget it?');

        return false;
    }
}