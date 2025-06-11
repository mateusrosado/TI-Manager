<?php
// File: TI-MANAGER/Core/Session.php

class Session
{
    public function __construct()
    {
        // session_start() já é chamado no index.php, então não precisamos chamá-lo aqui.
        // O construtor é mantido para consistência e para permitir inicialização futura.
    }

    /**
     * Define um valor na sessão.
     * @param string $key Chave para armazenar o dado.
     * @param mixed $value Valor a ser armazenado.
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Obtém um valor da sessão.
     * @param string $key Chave do dado.
     * @param mixed $default Valor padrão caso a chave não exista.
     * @return mixed O valor da sessão ou o padrão.
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Remove um dado específico da sessão.
     * @param string $key Chave a ser removida.
     */
    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destrói todos os dados da sessão e a própria sessão.
     */
    public function destroy()
    {
        session_unset(); // Limpa todas as variáveis de sessão
        session_destroy(); // Destrói a sessão
    }

    /**
     * Define os dados do usuário logado na sessão.
     * @param array $userData Um array com 'id', 'name', 'email', 'role' do usuário.
     */
    public function setUser(array $userData)
    {
        $this->set('user_logged_in', true);
        $this->set('user_id', $userData['id']);
        $this->set('user_name', $userData['name']);
        $this->set('user_email', $userData['email']);
        $this->set('user_role', $userData['role']);
    }

    /**
     * Obtém os dados do usuário logado da sessão.
     * @return array|null Um array com os dados do usuário ou null se não houver usuário logado.
     */
    public function getUser()
    {
        if ($this->get('user_logged_in')) {
            return [
                'id' => $this->get('user_id'),
                'name' => $this->get('user_name'),
                'email' => $this->get('user_email'),
                'role' => $this->get('user_role')
            ];
        }
        return null;
    }

    /**
     * Verifica se há um usuário logado na sessão.
     * @return bool True se houver um usuário logado, false caso contrário.
     */
    public function isLoggedIn(): bool
    {
        return (bool) $this->get('user_logged_in', false);
    }
}
