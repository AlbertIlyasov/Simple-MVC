<?php

class Products extends Controller {

   public function __construct() {
      parent::__construct();
   }

   public function index() {
      $data['title'] = 'Übersicht';
      $data['products'] = $this->_model->all();

      $this->_view->render('header', $data);
      $this->_view->render('products/list', $data);
      $this->_view->render('footer');
   }

   public function add() {
      $data['title'] = 'Neues Produkt';
      $data['form_header'] = 'Neues Produkt anlegen';

      $this->_view->render('header', $data);
      $this->_view->render('products/form', $data);
      $this->_view->render('footer');
   }

   public function insert(){
      $this->_model->insert($_POST['name'], $_POST['url'], $_POST['image'], $_POST['price']);
      return URL::redirect('products', 303);
   }

   public function delete($id){
      $this->_model->delete($id);
      return URL::redirect('products', 303);
   }

   public function search(){
      $data['title'] = 'Übersicht';
      $data['products'] = $this->_model->search($_GET['q']);

      $this->_view->render('header', $data);
      $this->_view->render('products/list', $data);
      $this->_view->render('footer');
   }

   public function edit($id){
      $data['title'] = 'Produkt bearbeiten';
      $data['form_header'] = 'Produkt editieren';

      $data['product'] = $this->_model->get($id);

      $this->_view->render('header', $data);
      $this->_view->render('products/form', $data);
      $this->_view->render('footer');
   }

   public function update(){
      $this->_model->update($_POST['id'], $_POST['name'], $_POST['url'], $_POST['image'], $_POST['price']);

      return URL::redirect('products', 303);
   }



}