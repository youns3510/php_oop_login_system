<?php

class sendMail
{

  private $body, $headers, $email;

  public function __construct()
  {
    $this->email = 'Developer@app.com';
    $this->headers = "From: " . $this->email . "\r\n";
    $this->headers .= "Reply-To: " . $this->email . "\r\n";
    $this->headers .= "MIME-Version: 1.0\r\n";
    $this->headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  }

  public function verifyAccount($userEmail, $token)
  {
    $this->body = '<!DOCTYPE html>
      <html lang="en">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <title>Verify Email</title>
      </head>
      <body>
          <div class="conttainer">
          <p>Thanks for signing up on our website. Please click on this link to verify your email</p>
       <a href="//' . $_SERVER["HTTP_HOST"] . '/?active_token=' . $token . '">
       Verify Your Account </a>
          </div>
      </body>
      </html>';
    $send = @mail($userEmail, 'verify Account', $this->body, $this->headers);
    // Send the message
    return  $send;
  }

  public function forgetPassword($userEmail, $token)
  {
    // global $mailer;


    $this->body = '<!DOCTYPE html>
      <html lang="en">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <title>forget password link</title>
      </head>
      <body>
          <div class="conttainer">
          <p> You request new password  link if not please <a href="//' . $_SERVER["HTTP_HOST"] . '">call us</a> immediately  to secure your account and ignore this link</p>
          <p>Please click on this link to reset your password</p>
       <a href="//' . $_SERVER["HTTP_HOST"] . '/forget_password.php?password_token=' . $token . '">
       Click Here</a>
          </div>
      </body>
      </html>';

    // Send the message
    $send = @mail($userEmail, 'forget password link', $this->body, $this->headers);
    return $send; 
  }
}
