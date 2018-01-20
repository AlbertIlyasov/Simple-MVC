<?php

class User_Model extends Model {

	public function add($login, $passwd) {
         
	    $errors = [];
	    if (empty($login)) {
	    	$errors[] = 'Login не заполнен';
	    } elseif ($this->loginIsExists($login)) {
	    	$errors[] = 'Этот логин уже занят';
	    }
	    if (empty($passwd)) {
	        $errors[] = 'Пароль не заполнен';
	    }

	     //если ошибки есть, отобразим их.
	    if (!empty($errors)) {
	  		Message::set(implode('<br />', $errors), 'danger');
	  		return false;
	    }

	    //если ошибок нет, добавим
		$data = [
			'login' => $login,
			'passwd' => Password::hash($passwd),
			// 'salt' => $this->getSalt(),
		];
		echo '<pre>'; var_dump($data); echo '</pre>';
		echo 'hash'; echo Password::hash($passwd);

		//check login and passwd
		Message::set('Вы успешно зарегистрированы!', 'success');
		return $this->_db->insert('users', $data);
	}

	public function loginIsExists($login) {
		return $this->_db->select('SELECT id FROM users WHERE login="'.$login.'" LIMIT 1');
	}

	// public function getSalt() {
	//     echo 'нужно генерить соль, доделать';
	// 	return rand(0,10000);
	// }

	public function auth($login, $passwd) {
// Password::validate($password, $correct_hash)
	}

	public function logout() {

	}

	public function changePasswd() {

	}

	public function updateProfile() {

	}
}
