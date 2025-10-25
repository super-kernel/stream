<?php
declare(strict_types=1);

namespace SuperKernel\Stream\Request;

use RuntimeException;
use SuperKernel\Stream\Contract\Stream\FileStreamInterface;
use SuperKernel\Stream\Contract\UploadedFileInterface;
use const UPLOAD_ERR_OK;

final class UploadedFile implements UploadedFileInterface
{
	private bool $moved = false;

	public function __construct(
		private readonly FileStreamInterface $fileStream,
		private readonly int                 $error = UPLOAD_ERR_OK,
		private readonly ?string             $clientFilename = null,
		private readonly ?string             $clientMediaType = null,
	)
	{
	}

	public function getStream(): FileStreamInterface
	{
		if ($this->moved) {
			throw new RuntimeException("File has already been moved");
		}
		return $this->fileStream;
	}

	public function moveTo(string $targetPath): void
	{
		if ($this->moved) {
			throw new RuntimeException("File has already been moved");
		}

		if (!rename($this->fileStream->getFilepath(), $targetPath)) {
			throw new RuntimeException("Failed to move file to $targetPath");
		}

		$this->moved = true;
	}

	public function getSize(): ?int
	{
		return $this->fileStream->getSize();
	}

	public function getError(): int
	{
		return $this->error;
	}

	public function getClientFilename(): ?string
	{
		return $this->clientFilename;
	}

	public function getClientMediaType(): ?string
	{
		return $this->clientMediaType;
	}

	public function isMoved(): bool
	{
		return $this->moved;
	}
}