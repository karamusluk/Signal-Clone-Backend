<?php

namespace App\Controllers;

use Core\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class Api extends Controller
{

    public function messages(Response $response)
    {
        $response->headers->set('Content-type', 'application/json');
        $response->setContent(success("hi"));
        $response->send();

//        return redirect(site_url())
//            ->with('işlemler başarıyla tamamlandı!')
//            ->send();

    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return string|void
     */
    public function login(Request $request) {
        if ($request->isMethod('POST')) {
            $this->validator->rule('required', ['name', 'password']);
            if ($this->validator->validate()) {
                $user = auth()->login($this->validator->data());
                if ($user) {
                    return success("User Logged In!", $user);
                } else {
                    return error("Username or password is wrong!");
                    // $this->validator->error('error', 'Kullanıcı adı ya da şifre hatalı!');
                }
            } else {
                return error($this->validator->errors());
            }
        }

        return error("Access Forbidden!");
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void|string
     */
    public function register(Request $request) {
        if ($request->isMethod('POST')) {
            $this->validator->rule('required', ['name', 'password', "fullname", "imageUrl"]);
            if ($this->validator->validate()) {
                $data = $this->validator->data();
                if (auth()->exist($data['name'])) {
                    return error(sprintf('There is a user with "%s" username. Please select another username or try to login!', $data['name']));
                } else {
                    $user = auth()->register($data);
                    if (!$user["error"]) {
                        return success("User created!", $user["data"]);
                    } else {
                        return error("There is an error. Please try again Later!");
                    }
                }
            }
            return error($this->validator->errors());
        }
        
        return error("Access Forbidden!");
    }

    public function posts(Response $response) {

        sleep(1);

        $posts = db('posts')->get();

        $response->setStatusCode(200);
        $response->headers->set('Content-type', 'application/json');
        $response->setContent($posts);
        $response->send();

//        return redirect(site_url())
//            ->with('işlemler başarıyla tamamlandı!')
//            ->send();sftp

    }

    public function createChat(Request $request) {
        if ($request->isMethod('POST')) {
            $this->validator->rule('required', ['name', 'owner']);
            if ($this->validator->validate()) {
                $data = $this->validator->data();
                $chat = db('chats')->insert($data);
                if ($chat) {
                    return success("Chat created!", $chat);
                } else {
                    return error("There is an error. Please try again Later!", $chat);
                }
            
            }
            return error($this->validator->errors());
        }

        return error("Access Forbidden!");
    }

    public function getOwnerChats(Request $request) {
        if ($request->isMethod('GET')) {
            $this->getValidator->rule('required', ['owner']);
            if ($this->getValidator->validate()) {
                $data = $this->getValidator->data();
                $chats = db('chats')
                    ->where("owner", "=", $data["owner"])
                    ->orderBy('created_at', 'desc')
                    ->get()->toArray();
                if (!empty($chats)) {
                    return success("", $chats);
                } else {
                    return error("There is an error. Please try again Later!", $chats);
                }
            }
            return error($this->getValidator->errors());
        }

        return error("Access Forbidden!");
    }

}