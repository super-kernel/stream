<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use RuntimeException;
use SplFileInfo;
use SuperKernel\Stream\Contract\Stream\FileStreamInterface;
use Throwable;
use function fclose;
use function feof;
use function fopen;
use function fread;
use function fseek;
use function ftell;
use function stream_get_contents;
use function stream_get_meta_data;

final class FileStream implements FileStreamInterface
{
	private readonly SplFileInfo $file;

	private readonly ?int $size;

	private mixed $handle = null {
		get {

			if ($this->handle === null) {
				$handle = fopen($this->file->getPathname(), 'r');
				if ($handle === false) {
					throw new RuntimeException("Failed to open file: " . $this->file->getPathname());
				}
				$this->handle = $handle;
			}
			return $this->handle;
		}
	}

	public function __construct(string|SplFileInfo $file, ?int $size = null)
	{
		$this->file = $file instanceof SplFileInfo ? $file : new SplFileInfo($file);

		if (null === $size && false !== $this->file->getSize()) {
			$size = $this->file->getSize();
		}

		$this->size = $size;
	}

	public function __toString(): string
	{
		try {
			$this->rewind();
			return stream_get_contents($this->handle) ?: '';
		}
		catch (Throwable) {
			return '';
		}
	}

	public function close(): void
	{
		if ($this->handle) {
			fclose($this->handle);
			$this->handle = null;
		}
	}

	public function detach()
	{
		$handle       = $this->handle;
		$this->handle = null;
		return $handle;
	}

	public function getSize(): ?int
	{
		return $this->size;
	}

	public function tell(): int
	{
		$pos = ftell($this->handle);
		if ($pos === false) {
			throw new RuntimeException("Unable to get position of file pointer");
		}
		return $pos;
	}

	public function eof(): bool
	{
		return feof($this->handle);
	}

	public function isSeekable(): bool
	{
		return true;
	}

	public function seek(int $offset, int $whence = SEEK_SET): void
	{
		if (fseek($this->handle, $offset, $whence) !== 0) {
			throw new RuntimeException("Failed to seek file");
		}
	}

	public function rewind(): void
	{
		$this->seek(0);
	}

	public function isWritable(): bool
	{
		return $this->file->isWritable();
	}

	public function write(string $string): int
	{
		throw new RuntimeException("FileStream is read-only");
	}

	public function isReadable(): bool
	{
		return true;
	}

	public function read(int $length): string
	{
		$data = fread($this->handle, $length);
		if ($data === false) {
			throw new RuntimeException("Failed to read file");
		}
		return $data;
	}

	public function getContents(): string
	{
		$contents = stream_get_contents($this->handle);
		if ($contents === false) {
			throw new RuntimeException("Failed to get file contents");
		}
		return $contents;
	}

	public function getMetadata(?string $key = null)
	{
		$meta = stream_get_meta_data($this->handle);
		return $key === null ? $meta : ($meta[$key] ?? null);
	}

	public function __destruct()
	{
		$this->close();
	}

	public function getFilepath(): string
	{
		return $this->file->getPathname();
	}
}