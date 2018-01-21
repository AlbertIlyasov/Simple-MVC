<?php

class User extends Controller {

   public function __construct() {
      parent::__construct();

      Session::init();
      // echo '<pre>'; print_r(Session::display());
      // echo '</pre>';
   }


   public function index() {
      $data['title'] = 'User page';

      $this->_view->render('header', $data);
      $this->_view->render('user/main', $data);
      $this->_view->render('footer');
   }

   public function add() {
      $data['title'] = 'Register new user';
      $data['form_header'] = 'Register new user';

      //если пользователь уже авторизован, отправим его на его страницу.
      if (User_Model::isAuth()) {
         return URL::redirect('user', 303);
      //если не авторизован, отобразим форму регистрации.
      } else {
         //если страница запрошена методом post, то значит произошла отправка данных из формы и тогда мы проверим
         //если отправлены данные из формы (а это приосходит методом post, попробуем добавить пользователя в базу)
         if (!empty($_POST)) {
            $login = $_POST['login'] ?? null;
            $passwd = $_POST['passwd'] ?? null;
            $email = $_POST['email'] ?? null;
         
            if ($this->_model->add($login, $passwd, $email)) {
               return URL::redirect('user/add', 303);
            } else {
               $data['login'] = $login;
               $data['email'] = $email;
            }
         }


         $this->_view->render('header', $data);
         $this->_view->render('user/add', $data);
         $this->_view->render('footer');
      }
   }

   public function auth() {
      $data['title'] = 'Sign in';
      $data['form_header'] = 'Sign in';

      //если пользователь уже авторизован, отправим его на его страницу.
      if (User_Model::isAuth()) {
         return URL::redirect('user', 303);
      //если не авторизован, отобразим форму авторизации.
      } else {
         //если страница запрошена методом post, то значит произошла отправка данных из формы и тогда мы проверим
         //если отправлены данные из формы (а это приосходит методом post, попробуем добавить пользователя в базу)
         if (!empty($_POST)) {
            $login = $_POST['login'] ?? null;
            $passwd = $_POST['passwd'] ?? null;
         
            if ($this->_model->auth($login, $passwd)) {
               return URL::redirect('user', 303);
            } else {
               $data['login'] = $login;
            }
         }


         $this->_view->render('header', $data);
         $this->_view->render('user/auth', $data);
         $this->_view->render('footer');
      }
   }

   public function confirm() {
      $data['title'] = 'Подтверждение регистрации';
      $data['form_header'] = 'Подтверждение регистрации';

      if ($this->_model->confirm($_GET['url'])) {
         $data['result'] = 'Ваша учётная запись активирована. Теперь вы можете авторизоваться.';

      } else {
         $data['result'] = 'неверный код авторизации';
      }

      $this->_view->render('header', $data);
      $this->_view->render('user/confirm', $data);
      $this->_view->render('footer');
   }

   public function logout() {
      $this->_model->logout();
      return URL::redirect('', 303);
   }
}