<?php

namespace App\Default\Controller\User;

class Login extends \App\Default\Controller\Common\Page_Std
{
    public function index(): void
    {
        parent::index();

        if (!empty($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        if (!empty($this->session->data['warnings'])) {
            $this->data['warnings'] = $this->session->data['warnings'];
            unset($this->session->data['warnings']);
        }

        if (!empty($this->session->data['errors'])) {
            $this->data['errors'] = $this->session->data['errors'];
            unset($this->session->data['errors']);
        }
    }

    protected function validate(): bool
    {
        if (empty($this->request->post['email'])) {
            $this->data['warnings'][] = $this->language->get('error_login');
        }

        if (empty($this->request->post['password'])) {
            $this->data['warnings'][] = $this->language->get('error_password');
        }

        if (!empty($this->data['warnings'])) return false;

        $this->load->model('user/user');

        // Check how many login attempts have been made.
        $login_info = $this->model_user_user->getLoginAttempts($this->request->post['email']);
        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts', 3)) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->data['errors'][] = $this->language->get('error_attempts');
            return false;
        }

        $email = $this->request->post('email', '');
        $password = $this->request->post('password', '');

        if (!$email) $this->data['warnings'][] = $this->language->get('error_login');

        if (!$password) $this->data['warnings'][] = $this->language->get('error_password');

        if (!$email || !$password) {
            return false;
        }

        if (!$this->user->login($email, $password)) {
            $this->data['errors'][] = $this->language->get('error_email_or_password');
            $this->model_user_user->addLoginAttempt($this->request->post['email']);
            if (!empty($this->session->data['token'])) {
                unset($this->session->data['token']);
            }
            return false;
        } else {
            $this->model_user_user->deleteLoginAttempts($this->request->post['email']);
            unset($this->data['warnings']);
            unset($this->data['errors']);
            return true;
        }
    }
}