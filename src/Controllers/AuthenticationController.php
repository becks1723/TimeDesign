<?php

namespace TimeDesign\Controllers;

use phpCAS;
use TimeDesign\Models\User;
use TimeDesign\Response\RedirectResponse;

class AuthenticationController extends Controller {
    public function login() {
        if (isset($_SESSION['user'])) {
            return new RedirectResponse($this->core, ".");
        }
        phpCAS::client(
            CAS_VERSION_3_0,
            "cas.auth.rpi.edu",
            443,
            "/cas",
            $this->core->getConfig()->getURI()
        );
        phpCAS::setNoCasServerValidation();
        if (!phpCAS::isAuthenticated()) {
            phpCAS::forceAuthentication();
        }
        $rcs = phpCAS::getUser();
        $user = $this->core->getDbQueries()->getUserByRCS($rcs);
        if ($user === null) {
            $this->core->getDbQueries()->addUser($rcs);
        }
        $_SESSION['user'] = $rcs;
        $_SESSION['xsrf_token'] = $this->randString();
        return new RedirectResponse($this->core, ".");
    }

    private function randString(): string {
        $chars = [];
        for ($i = 0; $i < 26; $i++) {
            $chars[] = chr($i + 65);
            $chars[] = chr($i + 97);
        }
        for ($i = 0; $i < 10; $i++) {
            $chars[] = chr($i + 48);
        }
        $str = "";
        for ($i = 0; $i < 32; $i++) {
            $str .= $chars[rand(0, count($chars)-1)];
        }
        return $str;
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        return new RedirectResponse($this->core, ".");
    }
}
