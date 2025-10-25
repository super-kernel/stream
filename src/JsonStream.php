<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use RuntimeException;
use SuperKernel\Stream\Contract\Stream\JsonStreamInterface;
use function func_get_args;
use function json_encode;

final class JsonStream implements JsonStreamInterface
{
	private readonly string $data;

	private readonly int $size;

	private int $pointer = 0;

	public function __construct(mixed $value, int $flags = 0, int $depth = 512)
	{
		$this->data = json_encode(...func_get_args());

		$this->size = strlen($this->data);
	}

	public function __toString(): string
	{
		return $this->data;
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
		return $this->size;
	}

	public function tell(): int
	{
		return $this->pointer;
	}

	public function eof(): bool
	{
		return $this->pointer >= $this->size;
	}

	public function isSeekable(): bool
	{
		return true;
	}

	public function seek(int $offset, int $whence = SEEK_SET): void
	{
		$this->pointer = match ($whence) {
			SEEK_SET => $offset,
			SEEK_CUR => $this->pointer + $offset,
			SEEK_END => $this->size + $offset,
		};
	}

	public function rewind(): void
	{
		$this->pointer = 0;
	}

	public function isWritable(): bool
	{
		return false;
	}

	public function write(string $string): int
	{
		throw new RuntimeException('Cannot write to a JSON stream.');
	}

	public function isReadable(): bool
	{
		return true;
	}

	public function read(int $length): string
	{
		$start         = $this->pointer;
		$end           = min($this->pointer + $length, $this->size);
		$this->pointer = $end;

		return substr($this->data, $start, $end - $start);
	}

	public function getContents(): string
	{
		$remaining     = substr($this->data, $this->pointer);
		$this->pointer = $this->size;
		return $remaining;
	}

	public function getMetadata(?string $key = null): null
	{
		return null;
	}
}