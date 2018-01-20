<?php

class Products_Model extends Model {

   public function __construct(){
      parent::__construct();
   }

   /**
   * Gibt die letzten 20 Einträge im Archiv zurück.
   * @return array Liste aus Produkten mit id, timestamp, name, url, image und price
   */
   public function all() {
      return $this->_db->select('SELECT * FROM products ORDER BY id DESC LIMIT 0, 20');
   }

}