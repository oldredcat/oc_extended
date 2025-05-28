<?php

namespace App\Store\Model\Design;

class Layout extends \App\Default\Model\Design\Layout
{
    public function getLayoutByRoute(string $route): int
    {
        $key  = md5($route);
        $data = $this->cache->get($key, 'layout');
        if (!is_array($data)) {
            $data['layout_id'] = parent::getLayoutByRoute($route);
            $this->cache->set($key, $data, 'layout');
        } else {
            $data['layout_id'] = (int)$data['layout_id'];
        }
        return $data['layout_id'];
    }

    public function getLayoutModules(int $layout_id, string $position = ''): array
    {
        $key = $layout_id;
        if ($position) $key .= '.' . md5($position);
        $data = $this->cache->get($key, 'layout');
        if (!is_array($data)) {
            $data = parent::getLayoutModules($layout_id, $position);
            $this->cache->set($key, $data, 'layout');
        }
        return $data;
    }
}