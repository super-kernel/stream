<?php
declare(strict_types=1);

namespace SuperKernel\Stream;

use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;
use SuperKernel\Stream\Contract\Stream\XmlStreamInterface;
use function htmlspecialchars;
use function is_array;
use function is_object;
use function is_string;
use function libxml_use_internal_errors;
use function simplexml_load_string;
use function strlen;

final class XmlStream implements XmlStreamInterface
{
	private string $data;

	private readonly int $size;

	private int $pointer = 0;

	public function __construct(mixed $data)
	{
		if (is_array($data) || is_object($data)) {
			$this->data = $this->convertToXml($data);
		} elseif (is_string($data)) {
			if (!$this->isValidXml($data)) {
				throw new InvalidArgumentException("Provided string is not a valid XML.");
			}
			$this->data = $data;
		} else {
			throw new InvalidArgumentException("Invalid data type for XmlStream.");
		}

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
		throw new RuntimeException('Cannot write to a XML stream.');
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

	private function convertToXml(mixed $data, ?SimpleXMLElement $xml = null): string
	{
		if ($xml === null) {
			$xml = new SimpleXMLElement('<root/>');
		}

		foreach ($data as $key => $value) {
			if (is_array($value) || is_object($value)) {
				$this->convertToXml($value, $xml->addChild($key));
			} else {
				$xml->addChild($key, htmlspecialchars((string)$value));
			}
		}

		return $xml->asXML();
	}

	private function isValidXml(string $xml): bool
	{
		libxml_use_internal_errors(true);
		$simpleXml = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NOCDATA);
		if ($simpleXml === false) {
			return false;
		}
		return true;
	}
}