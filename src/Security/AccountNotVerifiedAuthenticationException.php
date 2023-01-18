<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AccountNotVerifiedAuthenticationException extends AuthenticationException
{
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null, private RequestStack $stack)
	{
		parent::__construct( $message, $code, $previous );
	}

	public function setMessageKey($message): void
	{
		$this->message = $message;
	}
	public function getMessageKey(): string
	{
		return $this->message;
	}
}