<?php

namespace System\Library;

class User
{
    /**
     * @var object|Registry
     */
    protected Registry $registry;

    /**
     * @var array
     */
    private array $data = [];

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        if (isset($this->registry->session->data['user_id'])) {

            $user_query = $this->registry->db->query("SELECT * FROM `#__user` WHERE `user_id` = '" . (int)$this->registry->session->data['user_id'] . "' AND `status` = '1'");

            if ($user_query->num_rows) {
                $this->customer_id = $customer_query->row['customer_id'];
                $this->firstname = $customer_query->row['firstname'];
                $this->lastname = $customer_query->row['lastname'];
                $this->customer_group_id = $customer_query->row['customer_group_id'];
                $this->email = $customer_query->row['email'];
                $this->telephone = $customer_query->row['telephone'];
                $this->newsletter = $customer_query->row['newsletter'];
                $this->safe = (bool)$customer_query->row['safe'];
                $this->commenter = (bool)$customer_query->row['commenter'];

                $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `language_id` = '" . (int)$this->config->get('config_language_id') . "', `ip` = '" . $this->db->escape(oc_get_ip()) . "' WHERE `customer_id` = '" . (int)$this->customer_id . "'");
            } else {
                $this->logout();
            }
        }
    }

    /**
     * __get
     *
     * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
     *
     * @param    string  $key
     *
     * @return   mixed
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * __set
     *
     * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
     *
     * @param    string  $key
     * @param    object  $value
     *
     * @return   null
     */
    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    /**
     * Get
     *
     * @param	string	$key
     *
     * @return	object
     */
    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Set
     *
     * @param	string	$key
     * @param	object	$value
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Has
     *
     * @param	string	$key
     *
     * @return	bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Unset
     *
     * Unsets registry value by key.
     *
     * @param	string	$key
     *
     * @return	null
     */
    public function unset(string $key): void
    {
        if (isset($this->data[$key])) unset($this->data[$key]);
    }
}