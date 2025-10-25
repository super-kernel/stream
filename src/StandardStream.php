<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use Psr\Http\Message\StreamInterface;
use Stringable;
use const SEEK_CUR;
use const SEEK_END;
use const SEEK_SET;

final class StandardStream implements StreamInterface, Stringable
{
	private readonly string $data;

	private int $pointer = 0;

	private readonly int $size;

	public function __construct(string $data)
	{
		$this->data = $data;
		$this->size = strlen($data);
	}

	public function __toString(): string
	{
		return $this->data;
	}

	public function close(): void
	{
	}

	public function detach(): string
	{
		return $this->data;
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
		$this->pointer = max(0, min(match ($whence) {
			SEEK_SET => $offset,
			SEEK_CUR => $this->pointer + $offset,
			SEEK_END => $this->size + $offset,
		}, $this->size));
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
		return 0;
	}

	public function isReadable(): bool
	{
		return true;
	}

	public function read(int $length): string
	{
		$result        = substr($this->data, $this->pointer, $length);
		$this->pointer += strlen($result);
		return $result;
	}

	public function getContents(): string
	{
		$remaining     = substr($this->data, $this->pointer);
		$this->pointer = $this->size;
		return $remaining;
	}

	public function getMetadata(?string $key = null)
	{
		$metadata = [
			'seekable' => $this->isSeekable(),
			'size'     => $this->size,
			'readable' => $this->isReadable(),
			'writable' => $this->isWritable(),
		];

		return $key === null ? $metadata : ($metadata[$key] ?? null);
	}
}