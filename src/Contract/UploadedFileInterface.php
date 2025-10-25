<?php
declare(strict_types=1);

namespace SuperKernel\Stream\Contract;

use RuntimeException;
use SuperKernel\Stream\Contract\Stream\FileStreamInterface;

interface UploadedFileInterface
{
	/**
	 * Get the underlying file stream
	 */
	public function getStream(): FileStreamInterface;

	/**
	 * Move the uploaded file to the target path
	 *
	 * @param string $targetPath
	 *
	 * @throws RuntimeException
	 */
	public function moveTo(string $targetPath): void;

	/**
	 * Get file size
	 */
	public function getSize(): ?int;

	/**
	 * Get upload error code
	 */
	public function getError(): int;

	/**
	 * Get the client's original file name
	 */
	public function getClientFilename(): ?string;

	/**
	 * Get the client MIME type
	 */
	public function getClientMediaType(): ?string;

	/**
	 * Check if the file has been moved
	 */
	public function isMoved(): bool;
}