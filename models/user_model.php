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
		];

		//check login and passwd
		if ($this->_db->insert('users', $data)) {
			Message::set('Вы успешно зарегистрированы!', 'success');
			return true;
		} else {
			Message::set('Ошибка в процессе добавления в базу!', 'danger');
			return false;
		}
	}

	public function loginIsExists($login) {
		return $this->_db->select('SELECT id FROM users WHERE login="'.$login.'" LIMIT 1');
	}

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
