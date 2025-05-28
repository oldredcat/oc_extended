<?php

namespace App\Default\Model\Design;

use System\Engine\Model;

class Layout extends Model
{
    public function getLayoutByRoute(string $route): int
    {
        $query = $this->db->query("SELECT * FROM `#__layout_route` WHERE '" . $this->db->escape($route) . "' LIKE `route` AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `route` DESC LIMIT 1");
        if ($query->num_rows) {
            return (int)$query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getLayoutModules(int $layout_id, string $position = ''): array
    {
        $sql = "SELECT * FROM `#__layout_module` WHERE layout_id = '$layout_id' ";
        if (!empty($position)) {
            $sql .= "AND position = '" . $this->db->escape($position) . "' ORDER BY sort_order";
        } else {
            $sql .= "ORDER BY position ASC, sort_order ASC";
        }
        $query = $this->db->query($sql);
        return $query->rows;
    }
}