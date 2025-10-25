<?php
declare(strict_types=1);

namespace SuperKernel\Stream\Contract\Stream;

use Psr\Http\Message\StreamInterface;

interface FileStreamInterface extends StreamInterface
{
	public function getFilepath(): string;
}