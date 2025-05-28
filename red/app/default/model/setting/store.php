<?php

namespace App\Default\Model\Setting;

use System\Engine\Model;

class Store extends Model
{
    public function getStore(int $store_id): array
    {
        $query = $this->db->query("SELECT * FROM `#__store` WHERE `store_id` = '$store_id'");
        if ($query->num_rows) {
            return $query->row;
        } else {
            return [];
        }
    }

    public function getStoreByHostname(string $url): array
    {
        $query = $this->db->query("SELECT * FROM `#__store` WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape($url) . "'");
        if ($query->num_rows) {
            return $query->row;
        } else {
            return [];
        }
    }

    public function getStores(): array
    {
        $sql = "SELECT * FROM `#__store` ORDER BY `url`";
        $query = $this->db->query($sql);
        return $query->rows;
    }
}