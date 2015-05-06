<?php

class UserController extends Controller
{
    public function process($parameters)
    {
        $this->data = array(
            'user' => array()
        );

        $userManager = new UserManager();
        $user = $userManager->getUserByNick($parameters[1]);

        $this->head = array
        (
            'title' => 'Profil uživatele ' . $user['nickname'],
            'description' => 'Profil uživatele blogu.'
        );
        $this->data['user'] = $user;
        $this->view = 'user';
    }
}