<?php
class CurrentUser
{
    public static function fetch() {
        $session = self::fetchSession();

        if ($session) {
          // user or client

            $user = User::find($session->user_id);
            return $user ?? null;
        }

        return null;
    }

    public static function fetchSession() {
        $headers = new Headers;
        $token = $headers->authorizationToken();

        // find session
        $hashed_token = SessionTokenGenerator::hashToken($token);
        $session = Session::findBy('token', $hashed_token);

        return $session ?? null;
    }
}
