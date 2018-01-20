<?php

class User extends Controller {

   public function __construct() {
      parent::__construct();
   }

   public function index() {
      return URL::redirect('user/add', 303);
   }

   public function add() {
      $data['title'] = 'Register new user';

      //если страница запрошена методом post, то значит произошла отправка данных из формы и тогда мы проверим
      //если отправлены данные из формы (а это приосходит методом post, попробуем добавить пользователя в базу)
      if (!empty($_POST)) {
         $login = $_POST['login'] ?? null;
         $passwd = $_POST['passwd'] ?? null;
      
         if ($this->_model->add($login, $passwd)) {
            return URL::redirect('user/add', 303);
         } else {
            $data['login'] = $login;
         }
      }


      $this->_view->render('header', $data);
      $this->_view->render('user/add', $data);
      $this->_view->render('footer');
   }
}