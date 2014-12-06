<?php
namespace water\page;

use water\database\Users;
use water\session\SessionStore;

class login extends Page{
    public function showPage($message = false){
        $user = SessionStore::getCurrentSession();
        if(!empty($_POST["username"]) && !empty($_POST["password"])){
            if(Users::checkLogin($_POST["username"], $_POST["password"])){
                SessionStore::createSession($_POST["username"]);
                (new index())->showPage("You are now logged in.");
            }
            else{
                echo $this->getTemplateEngine()->render($this->getTemplateSnip("page"), [
                    "title" => "Login",
                    "content" => $this->getTemplateEngine()->render($this->getTemplate(), [
                        "message" => "Username or password is incorrect.",
                        "user" => $user
                    ])
                ]);
            }
        }
        else {
            echo $this->getTemplateEngine()->render($this->getTemplateSnip("page"), [
                "title" => "Login",
                "content" => $this->getTemplateEngine()->render($this->getTemplate(), [
                    "message" => ($message === false ? false : $message),
                    "user" => $user
                ])
            ]);
        }
    }
    public function hasPermission(){
        return true;
    }
}