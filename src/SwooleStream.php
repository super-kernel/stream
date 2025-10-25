<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use RuntimeException;
use SuperKernel\Stream\Contract\Stream\SwooleStreamInterface;

final class SwooleStream implements SwooleStreamInterface
{
	private mixed $callback;

	private bool $closed = false;

	/**
	 * The example:
	 *
	 *      $stream = new SwooleStream(function () {
	 *          yield "Data chunk 1";
	 *          yield "Data chunk 2";
	 *          yield "Data chunk 3";
	 *      });
	 *
	 * @param callable $callback  A callable that accepts a `SwooleStream` instance as an argument and returns an
	 *                            `iterable` data stream. The callback is invoked when `getContent()` is called, and it
	 *                            is responsible for generating the stream's data. The callback should yield data
	 *                            chunks one by one, or return an array or generator. Once the callback finishes
	 *                            yielding data, the stream will be closed automatically.
	 */
	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	public function __toString(): string
	{
		throw new RuntimeException('Cannot convert SwooleStream to string.');
	}

	public function close(): void
	{
		$this->closed = true;
	}

	public function detach(): null
	{
		return null;
	}

	public function getSize(): ?int
	{
		return null;
	}

	public function tell(): int
	{
		throw new RuntimeException('SwooleStream not tell');
	}

	public function eof(): bool
	{
		return $this->closed;
	}

	public function isSeekable(): bool
	{
		return false;
	}

	public function seek(int $offset, int $whence = SEEK_SET): void
	{
		throw new RuntimeException('SwooleStream not seekable');
	}

	public function rewind(): void
	{
		throw new RuntimeException('SwooleStream not rewindable');
	}

	public function isWritable(): bool
	{
		return false;
	}

	public function write(string $string): int
	{
		throw new RuntimeException('Unable to write externally to SwooleStream.');
	}

	public function isReadable(): bool
	{
		return !$this->eof();
	}

	public function read(int $length): string
	{
		throw new RuntimeException('Unable to read externally to SwooleStream.');
	}

	public function getContents(): string
	{
		throw new RuntimeException('SwooleStream not getContents');
	}

	public function getContent(): iterable
	{
		$callback = $this->callback;
		$data     = $callback();

		foreach ($data as $chunk) {
			if ($this->closed) break;
			yield $chunk;
		}

		$this->close();
	}

	public function getMetadata(?string $key = null): null
	{
		return null;
	}
}