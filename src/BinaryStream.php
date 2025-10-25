<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use SuperKernel\Stream\Contract\Stream\BinaryStreamInterface;
use function min;
use function strlen;
use function substr;

final class BinaryStream implements BinaryStreamInterface
{
	private readonly int $size;

	private int $pointer = 0;

	public function __construct(private string $data = '')
	{
		$this->size = strlen($data);
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
		return true;
	}

	public function write(string $string): int
	{
		$bytesWritten = strlen($string);
		$this->data   .= $string;
		return $bytesWritten;
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