<?php

class User_Model extends Model {
	//статус пользователя, который ещё не подтвердил регистрацию через e-mail
	const WAITING_CONFIRMATION = 2;
	//статус пользователя - активен
	const ACTIVE = 1;

	public function add($login, $passwd, $email) {
         
	    $errors = [];
	    if (empty($login)) {
	    	$errors[] = 'Login не заполнен';
	    } elseif ($this->loginIsExists($login)) {
	    	$errors[] = 'Этот логин уже занят';
	    }
	    if (empty($passwd)) {
	        $errors[] = 'Пароль не заполнен';
	    }
	    if (empty($email)) {
	    	$errors[] = 'E-mail не заполнен';
	    } elseif (!$this->emailIsCorrect($email)) {
	    	$errors[] = 'E-mail неверный';
	    } elseif ($this->emailIsExists($email)) {
	    	$errors[] = 'E-mail уже занят';
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
			'email' => $email,
			'status' => self::WAITING_CONFIRMATION,
		];

		//check login and passwd
		if ($id = $this->_db->insert('users', $data)) {
			$this->sendConfirmationEmail($id);
			Message::set('Вы успешно зарегистрированы! Проверьте вашу почту и перейдите по ссылке в письме для подтверждения регистрации.', 'success');
			return true;
		} else {
			Message::set('Ошибка в процессе добавления в базу!', 'danger');
			return false;
		}
	}

	public function generateConfirmationCode() {
		return rand(0,100);
	}

	public function confirm($url) {
		$url = explode('/', $url);

		if (count($url) != 4) {
			return false;
		}

		$id = $url[2];
		$code = $url[3];

		//если код подтверждения неверный, активируем пользователя
		if (!$this->_db->select('SELECT id FROM users WHERE id="'.$id.'" and confirmation_code="'.$code.'"')) {
			return false;
		} 

		//на этом шаге знаем, что  код подтверждения верный, поэтому активируем пользователя
		$this->_db->update('users', ['confirmation_code' => null], ['id' => $id]);
		$this->setStatus($id, self::ACTIVE);
		return true;
	}


	public function setStatus($id, $status) {
		return $this->_db->update('users', ['status' => $status], ['id' => $id]);
	}

	public function sendConfirmationEmail($id) {
		$users = $this->_db->select('SELECT id, email FROM users WHERE id="'.$id.'" LIMIT 1');
		$user = $users[0];

		$email = $user['email'];
		$code = $this->generateConfirmationCode();
		$this->_db->update('users', ['confirmation_code' => $code], ['id' => $id]);


		$subj = 'Подтвердите регистрацию';
		$body = 'Здравствуйте! Для подвтерждения регистрации перейдите по ссылке '.DIR.'user/confirm/'.$user['id'].'/'.$code;
		return mail($email, $subj, $body);
	}

	public function loginIsExists($login) {
		return $this->_db->select('SELECT id FROM users WHERE login="'.$login.'" LIMIT 1');
	}

	public function auth($login, $passwd) {
        
	    $errors = [];
	    if (empty($login)) {
	    	$errors[] = 'Login не заполнен';
	    }
	    if (empty($passwd)) {
	        $errors[] = 'Пароль не заполнен';
	    } elseif (!empty($login)) {
	    	echo 'фильтрация login';
	    	//получим id и хэш пароля по логину
	    	$users = $this->_db->select('SELECT id, passwd, status FROM users WHERE login="'.$login.'"');

	    	//если массив непустой, значит пользователь с переданным логином найден. Проверим переданный пароль. 
	    	if (!empty($users)) {
	    		$user = $users[0];

				if (!Password::validate($passwd, $user['passwd'])) {
		    		$errors[] = 'Неверный логин или пароль';
		    	} elseif ($user['status'] != self::ACTIVE) {
		    		if ($user['status'] == self::WAITING_CONFIRMATION) {
		    			$errors[] = 'Вы не активировали аккаунт. Пожалуйста, проверьте свою почту и перейдите по ссылке в письме';
		    		}
		    	}
	    	} else {
		    	$errors[] = 'Неверный логин или пароль';

	    	}
	    }

	     //если ошибки есть, отобразим их.
	    if (!empty($errors)) {
	  		Message::set(implode('<br />', $errors), 'danger');
	  		return false;
	    }

	    Session::set('auth', ['id' => $user['id']]);

		return true;
	}

	public function emailIsCorrect($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function emailIsExists($email) {
		return $this->_db->select('SELECT id FROM users WHERE email="'.$email.'" LIMIT 1');
	}

	public function logout() {
		Session::clear('auth');
	}

	public static function isAuth() {
		return Session::get('auth', 'id');
	}

	public function changePasswd() {

	}

	public function updateProfile() {

	}
}
