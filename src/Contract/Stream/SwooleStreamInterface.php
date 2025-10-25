<?php
declare(strict_types=1);

namespace SuperKernel\Stream\Contract\Stream;

use Psr\Http\Message\StreamInterface;

interface SwooleStreamInterface extends StreamInterface
{
	public function pop(float $timeout = -1): mixed;
}