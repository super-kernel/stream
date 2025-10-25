<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use RuntimeException;
use SuperKernel\Stream\Contract\Stream\EmptyStreamInterface;

final class EmptyStream implements EmptyStreamInterface
{
	public function __toString(): string
	{
		return '';
	}

	public function close(): void
	{
	}

	public function detach(): null
	{
		return null;
	}

	public function getSize(): ?int
	{
		return 0;
	}

	public function tell(): int
	{
		return 0;
	}

	public function eof(): bool
	{
		return true;
	}

	public function isSeekable(): bool
	{
		return false;
	}

	public function seek(int $offset, int $whence = SEEK_SET): void
	{
		throw new RuntimeException('Cannot seek in an empty stream.');
	}

	public function rewind(): void
	{
	}

	public function isWritable(): bool
	{
		return false;
	}

	public function write(string $string): int
	{
		throw new RuntimeException('Cannot write to an empty stream.');
	}

	public function isReadable(): bool
	{
		return false;
	}

	public function read(int $length): string
	{
		return '';
	}

	public function getContents(): string
	{
		return '';
	}

	public function getMetadata(?string $key = null): null
	{
		return null;
	}
}