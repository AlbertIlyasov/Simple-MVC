<?php

class Products_Model extends Model {

   public function __construct(){
      parent::__construct();
   }

   public function insert($name, $url, $image, $price) {
      $data = [
         'name' => $name,
         'url' => $url,
         'image' => $image,
         'price' => $price,
      ];
      return $this->_db->insert('products', $data);
   }

   public function update($id, $name, $url, $image, $price) {
      $data = [
         'name' => $name,
         'url' => $url,
         'image' => $image,
         'price' => $price,
      ];

      //return $this->_db->updateProducts($data, ['id', $id]);
      return $this->_db->table('products')->where('id', $id)->update($data);
   }

   public function delete($id) {
      $data = [
         'id' => $id
      ];
      return $this->_db->delete('products', $data);
   }

   public function search($query) {
      return $this->_db->select('SELECT * FROM products WHERE
      name like "%'.$query.'%" ||
      url like "%'.$query.'%" ||
      price like "%'.$query.'%"
      ORDER BY id DESC LIMIT 0, 20');
   }


   public function get($id) {
      $products = $this->_db->select('SELECT * FROM products WHERE
      id = '.$id.'');
      return $products[0];
   }


   /**
   * Gibt die letzten 20 Einträge im Archiv zurück.
   * @return array Liste aus Produkten mit id, timestamp, name, url, image und price
   */
   public function all() {
      return $this->_db->select('SELECT * FROM products ORDER BY id DESC LIMIT 0, 20');
   }

}