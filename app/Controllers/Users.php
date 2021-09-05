<?php namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index(){
        $data = [];
		helper(['form']);
		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
            $rules = [
                'username' => 'required',
                'password' => 'required|min_length[3]|validateUser[username,password]',
            ];
			$errors = [
                'username' => [
                    'required' => 'All accounts must have usernames provided',
                ],
				'password' => [
                    'min_length' => 'Your password is too short.'
				]
			];

			if (!$this->validate($rules, $errors)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new UserModel();
				$user = $model->where('username', $this->request->getVar('username'))->first();
				$this->setUserSession($user);
				return redirect()->to('dashboard');
			}
		}

		echo view('templates/header', $data);
		echo view('login');
		echo view('templates/footer');
    }

    private function setUserSession($user){
		$data = [
			'id' => $user['id'],
			'firstname' => $user['first_name'],
			'lastname' => $user['last_name'],
			'username' => $user['username'],
            'adminlevel' => $user['admin_level'],
            'active' => $user['active'],
			'isLoggedIn' => true,
		];

		session()->set($data);
		return true;
	}

    public function logout(){
		session()->destroy();
		return redirect()->to('/');
	}

}