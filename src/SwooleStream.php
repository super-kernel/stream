<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use RuntimeException;
use SuperKernel\Stream\Contract\Stream\SwooleStreamInterface;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

final class SwooleStream implements SwooleStreamInterface
{
	private Channel $channel;

	private mixed $data;

	private bool $closed = false;

	public function __construct(callable $callback, int $capacity = 1024)
	{
		$this->channel = new Channel($capacity);

		Coroutine::create(fn() => $callback($this, $this->channel));
	}

	public function __toString(): string
	{
		throw new RuntimeException('Cannot convert SwooleStream to string.');
	}

	public function close(): void
	{
		$this->channel->close();

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
		return 0;
	}

	public function eof(): bool
	{
		if ($this->closed) {
			return true;
		}

		$data = $this->channel->pop();

		if (false === $data) {
			return true;
		}

		$this->data = $data;

		return false;
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

	public function getMetadata(?string $key = null): null
	{
		return null;
	}

	public function pop(): mixed
	{
		return $this->data;
	}
}