<?php

/*
 * Author: PunkVv <punkv@qq.com>
 */

namespace app\common\service;

use app\common\HttpCode;
use app\common\model\AdminUser;
use app\common\Token;
use app\common\util\Encrypt;
use app\common\validate\AdminUserValidate;
use app\common\VService;

class LoginService extends VService
{
    /**
     * 后台用户名密码登录
     * @param $param
     * @return mixed
     */
    public function login($param)
    {
        $validate = new AdminUserValidate();
        if ($this->validate($param, $validate, 'login')) {
            $username = $param['username'];
            $password = $param['password'];
            $info = AdminUser::getInfoByName($username);

            if (!$info || !Encrypt::validate($password, $info->password)) {
                $this->message = '用户名或者密码错误';
                $this->name = 'USER_AUTH_FAIL';
                $this->code = HttpCode::$invalidRequest;
            } elseif ($info->status !== 1) {
                $this->message = '用户已被禁用';
                $this->name = 'USER_AUTH_FAIL';
                $this->code = HttpCode::$invalidRequest;
            } else {
                // 登录成功生成 token
                $userId = $info->id;
                $value = [
                    'id' => $userId,
                    'name' => $username,
                ];
                $token = Token::create('admin'.$userId, $value, 2 * 60 * 60);
                $this->data['token'] = $token;
                $this->data['id'] = $userId;
            }
        }

        return $this->result();
    }
}