<?php

namespace App;

class ApiCode
{
  public const OK = 200;
  public const CREATED = 201;
  public const ACCEPTED = 202;
  public const NO_CONTENT = 204;
  public const SOMETHING_WENT_WRONG = 250;
  public const INVALID_CREDENTIALS = 251;
  public const VALIDATION_ERROR = 252;
  public const EMAIL_ALREADY_VERIFIED = 253;
  public const INVALID_EMAIL_VERIFICATION_URL = 254;
  public const INVALID_RESET_PASSWORD_TOKEN = 255;
  public const ALREADY_EXISTS = 256;
  public const BAD_REQUEST = 400;
  public const UNAUTHORIZED = 401;
  public const PAYMENT_REQUIRED = 402;
  public const FORBIDDEN = 403;
  public const NOT_FOUND = 404;
  public const METHOS_NOT_ALLOWED = 405;
  public const REQUEST_TIMEOUT = 408;
  public const INTERNAL_SERVER_ERROR = 500;
  public const NOT_IMPLEMENTED = 501;
  public const BAD_GATEWAY = 502;
  public const SERVICE_UNAVAILABLE = 503;
  public const NETWORK_CONNECT_TIMEOUT_ERROR = 599;
}
