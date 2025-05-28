<?php

namespace App\Admin\Model\Design;

class Layout extends \App\Default\Model\Design\Layout
{
    public function addLayout(array $data): int
    {
        $data = $this->prepareData($data);

        $this->db->query("INSERT INTO `#__layout` SET `name` = '" . $data['name'] . "'");

        $layout_id = $this->db->getLastId();

        foreach ($data['layout_route'] as $layout_route) {
            $this->db->query("INSERT INTO `#__layout_route` (`layout_id`, `store_id`, `route`) VALUES ('$layout_id', '".$layout_route['store_id']."', '".$layout_route['route']."')");
        }

        foreach ($data['layout_module'] as $layout_module) {
            $this->db->query("INSERT INTO `#__layout_module` (`layout_id`, `code`, `position`, `sort_order`) VALUES ('$layout_id', '".$layout_module['code']."', '".$layout_module['position']."', '".$layout_module['sort_order']."')");
        }

        return $layout_id;
    }

    public function editLayout(int $layout_id, array $data): void
    {
        $data = $this->prepareData($data);

        $this->db->query("UPDATE `#__layout` SET `name` = '" . $data['name'] . "' WHERE layout_id = '$layout_id'");

        $this->db->query("DELETE FROM `#__layout_route` WHERE layout_id = '$layout_id'");

        foreach ($data['layout_route'] as $layout_route) {
            $this->db->query("INSERT INTO `#__layout_route` (`layout_id`, `store_id`, `route`) VALUES ('$layout_id', '".$layout_route['store_id']."', '".$layout_route['route']."')");
        }

        $this->db->query("DELETE FROM `#__layout_module` WHERE layout_id = '$layout_id'");

        foreach ($data['layout_module'] as $layout_module) {
            $this->db->query("INSERT INTO `#__layout_module` (`layout_id`, `code`, `position`, `sort_order`) VALUES ('$layout_id', '".$layout_module['code']."', '".$layout_module['position']."', '".$layout_module['sort_order']."')");
        }
    }

    public function getLayoutById(int $layout_id): array
    {
        $query = $this->db->query("SELECT DISTINCT * FROM `#__layout` WHERE `layout_id` = '$layout_id'");
        if ($query->num_rows) {
            return $query->row;
        } else {
            return [];
        }
    }

    public function deleteLayout(int $layout_id): void
    {
        $this->db->query("DELETE FROM `#__layout` WHERE `layout_id` = '$layout_id'");
        $this->db->query("DELETE FROM `#__layout_route` WHERE `layout_id` = '$layout_id'");
        $this->db->query("DELETE FROM `#__layout_module` WHERE `layout_id` = '$layout_id'");
    }

    public function getLayouts(array $data = []): array
    {
        $sql = "SELECT * FROM `#__layout`";

        $sort_data = ['name'];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getLayoutRoutes(int $layout_id): array
    {
        $query = $this->db->query("SELECT * FROM `#__layout_route` WHERE layout_id = '$layout_id'");
        return $query->rows;
    }

    public function getRoutesByStoreId(int $store_id): array
    {
        $query = $this->db->query("SELECT * FROM `#__layout_route` WHERE `store_id` = '$store_id'");

        return $query->rows;
    }

    public function getTotalLayouts(int $store_id = 0): int
    {
        $sql = "SELECT COUNT(*) AS total FROM `#__layout`";
        if ($store_id) $sql .= " WHERE `store_id` = '$store_id'";
        $query = $this->db->query($sql);
        return $this->getTotal($query);
    }

    public function addRoute(int $layout_id, array $data): void
    {
        $data = $this->prepareData($data);
        $this->db->query("INSERT INTO `#__layout_route` SET `layout_id` = '$layout_id', `store_id` = '" . $data['store_id'] . "', `route` = '" . $data['route'] . "'");
    }

    public function deleteRoutes(int $layout_id): void
    {
        $this->db->query("DELETE FROM `#__layout_route` WHERE `layout_id` = '$layout_id'");
    }

    public function deleteRoutesByLayoutId(int $layout_id): void
    {
        $this->db->query("DELETE FROM `#__layout_route` WHERE `layout_id` = '$layout_id'");
    }

    public function deleteRoutesByStoreId(int $store_id): void
    {
        $this->db->query("DELETE FROM `#__layout_route` WHERE `store_id` = '$store_id'");
    }

    public function addModule(int $layout_id, array $data): void
    {
        $this->db->query("INSERT INTO `#__layout_module` SET `layout_id` = '$layout_id', `code` = '" . $this->db->escape($data['code']) . "', `position` = '" . $this->db->escape($data['position']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
    }

    public function deleteModules(int $layout_id): void
    {
        $this->db->query("DELETE FROM `#__layout_module` WHERE `layout_id` = '$layout_id'");
    }

    public function deleteModulesByCode(string $code): void
    {
        $this->db->query("DELETE FROM `#__layout_module` WHERE `code` = '" . $this->db->escape($code) . "' OR `code` LIKE '" . $this->db->escape($code . '.%') . "'");
    }

    private function prepareData(array $data): array
    {
        $result['store_id'] = isset($data['store_id'])  ? (int) $data['store_id']           : 0;
        $result['name']     = isset($data['name'])      ? $this->db->escape($data['name'])  : '';

        $result['layout_route']  = $data['layout_route']  ?? [];
        $result['layout_module'] = $data['layout_module'] ?? [];

        for ($i = 0; $i < count($result['layout_route']); $i++) {
            $result['layout_route'][$i]['store_id'] = (int) $result['layout_route'][$i]['store_id'];
            $result['layout_route'][$i]['route']    = $this->db->escape($result['layout_route'][$i]['route']);
        }

        for ($i = 0; $i < count($result['layout_module']); $i++) {
            $result['layout_module'][$i]['code']        = $this->db->escape($result['layout_module'][$i]['code']);
            $result['layout_module'][$i]['position']    = $this->db->escape($result['layout_module'][$i]['position']);
            $result['layout_module'][$i]['sort_order']  = (int) $result['layout_module'][$i]['sort_order'];
        }

        return $result;
    }
}