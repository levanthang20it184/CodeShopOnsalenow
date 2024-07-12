<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
class Cron_model extends CI_Model
{
  function cron_job()
  {
    $query = "CREATE TABLE IF NOT EXISTS `price_history_this_week` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `product_id` INT NOT NULL,
      `history_date` DATE NOT NULL,
      `selling_price` FLOAT NOT NULL,
      `cost_price` FLOAT NOT NULL,
      PRIMARY KEY (`id`),
      KEY `history_date` (`history_date`)
    ) ENGINE=MyISAM";
    $result = $this->db->query($query);

    $query = "DELETE FROM `price_history_this_week`";
    $result = $this->db->query($query);

    $query = "
      INSERT INTO `price_history_this_week` (`product_id`, `history_date`, `selling_price`, `cost_price`)
      SELECT ph.product_id, ph.history_date, ph.selling_price, ph.cost_price
      FROM price_history AS ph
      WHERE ph.selling_price < (
          SELECT selling_price
          FROM price_history
          WHERE product_id = ph.product_id
          AND history_date < ph.history_date
          ORDER BY history_date DESC
          LIMIT 1
      )
      AND ph.history_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
      AND ph.history_date <= CURDATE();
    ";
    $result = $this->db->query($query);
  }  
}

?>