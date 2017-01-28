<?php
class SessionTokenGenerator
{
  // generate token that not exists in database
  public static function generate($lenght = 100)
  {
      $generator = new RandomStringGenerator;

      do {
          $token = $generator->generate($lenght);
          $hashed_token = SessionTokenGenerator::hashToken($token);
          $session = Session::findBy('token', $hashed_token);
      } while ($session);

      return $token;
  }

  // for seciurity we hash md5 tokens
  // if someone see tokens, cant use it to login
  public static function hashToken($token)
  {
      return md5($token);
  }
}
