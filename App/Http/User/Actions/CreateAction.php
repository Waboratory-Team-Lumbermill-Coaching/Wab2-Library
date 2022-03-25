<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:55 PM
 */

namespace App\Http\Users\Actions;


use App\Helpers\NotificationHelper;
use App\Http\User\UserService;
use Base\Actions\CreateAction as BaseCreateAction;

class CreateAction extends BaseCreateAction {
    /**
     * @var UserService
     */
    protected $service;

    protected $rules = [
        'username' => 'required|between:4,255|unique:users,username',
        'password' => 'required|between:4,255',
        'confirm_password' => 'required|same:password',
        'full_name' => 'required|between:5,255',
        'born_on' => 'required',
    ];

    protected $messages = [
        'username' => [
            'unique' => 'Username already taken!',
        ],
        'confirm_password' => [
            'same' => 'Passwords mismatch!'
        ]
    ];

    public function __construct(UserService $service) {
        parent::__construct($service);
    }

    /**
     * @param $data
     * @return \App\Models\User|mixed
     * @throws \Exception
     */
    protected function handleData($data) {
        $user = $this->service->create($data);
        NotificationHelper::success('Congratulations, ' . ucfirst($user->username) . '. Login in our platform');

        return $user;
    }

}