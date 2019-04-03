<?php
 namespace Doctrine\DBAL\Types; use Doctrine\DBAL\Platforms\AbstractPlatform; class ArrayType extends Type { public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) { return $platform->getClobTypeDeclarationSQL($fieldDeclaration); } public function convertToDatabaseValue($value, AbstractPlatform $platform) { return serialize($value); } public function convertToPHPValue($value, AbstractPlatform $platform) { if ($value === null) { return null; } $value = (is_resource($value)) ? stream_get_contents($value) : $value; $val = unserialize($value); if ($val === false && $value != 'b:0;') { throw ConversionException::conversionFailed($value, $this->getName()); } return $val; } public function getName() { return Type::TARRAY; } public function requiresSQLCommentHint(AbstractPlatform $platform) { return true; } } 