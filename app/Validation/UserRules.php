<?php
namespace App\Validation;
use App\Models\UserModel;

class UserRules
{
  public function validateUser(string $str, string $fields, array $data){
    $model = new UserModel();
    $user = $model->where('username', $data['username'])
                  ->first();

    if(!$user) return false;
    $status = password_verify($data['password'], $user['password']);
    return $status;
  }
}