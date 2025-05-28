<?php

namespace App\Admin\Model\Setting;

class Store extends \App\Default\Model\Setting\Store
{
    public function addStore(array $data): int
    {
        $data = $this->prepareData($data);

        if (empty($data['name'])) return 0;

        $this->db->query("INSERT INTO `#__store` SET `name` = '" . $data['name'] . "', `url` = '" . $data['url'] . "'");

        $store_id = $this->db->getLastId();

        // Layout Route
        $results = $this->model_design_layout->getRoutesByStoreId();

        foreach ($results as $result) {
            $this->model_design_layout->addRoute($result['layout_id'], ['store_id' => $store_id] + $result);
        }

        // SEO URL
        $results = $this->model_design_seo_url->getSeoUrlsByStoreId();

        foreach ($results as $result) {
            $this->model_design_seo_url->addSeoUrl($result['key'], $result['value'], $result['keyword'], $store_id, $result['language_id'], $result['sort_order']);
        }

        $this->cache->deleteByPrefix('store');

        return $store_id;
    }

    public function editStore(int $store_id, array $data): void
    {
        $data = $this->prepareData($data);
        if (!empty($data['name'])) {
            $this->db->query("UPDATE `#__store` SET `name` = '".$data['name']."', `url` = '".$data['url']."', `description` = '".$data['description']."' WHERE `store_id` = '$store_id'");
            $this->cache->deleteByPrefix('store');
        }
    }

    public function deleteStore(int $store_id): void
    {
        // Theme
        $this->model_design_theme->deleteThemesByStoreId($store_id);

        // Translation
        $this->model_design_translation->deleteTranslationsByStoreId($store_id);

        // SEO URL
        $this->model_design_seo_url->deleteSeoUrlsByStoreId($store_id);

        // Setting
        $this->model_setting_setting->deleteSettingsByStoreId($store_id);

        $this->cache->deleteByPrefix('store');
    }

    public function getTotalStores(): int
    {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `#__store`");
        return $this->getTotal($query);
    }

    public function getTotalStoresByLayoutId(int $layout_id): int
    {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `#__setting` WHERE `key` = 'config_layout_id' AND `value` = '$layout_id' AND `store_id` != '0'");
        return $this->getTotal($query);
    }

    public function getTotalStoresByLanguage(string $language): int
    {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `#__setting` WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND `store_id` != '0'");
        return $this->getTotal($query);
    }

    public function getTotalStoresByCountryId(int $country_id): int
    {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `#__setting` WHERE `key` = 'config_country_id' AND `value` = '$country_id' AND `store_id` != '0'");
        return $this->getTotal($query);
    }

    public function getTotalStoresByZoneId(int $zone_id): int
    {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `#__setting` WHERE `key` = 'config_zone_id' AND `value` = '$zone_id' AND `store_id` != '0'");
        return $this->getTotal($query);
    }

    public function getTotalStoresByCustomerGroupId(int $customer_group_id): int
    {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM `#__setting` WHERE `key` = 'config_customer_group_id' AND `value` = '$customer_group_id' AND `store_id` != '0'");
        return $this->getTotal($query);
    }

    private function prepareData(array $data): array
    {
        $result['name']         = isset($data['name'])         ? $this->db->escape(trim($data['name']))        : '';
        $result['url']          = isset($data['url'])          ? $this->db->escape(trim($data['url']))         : '';
        $result['description']  = isset($data['description'])  ? $this->db->escape(trim($data['description'])) : '';
        return $result;
    }
}