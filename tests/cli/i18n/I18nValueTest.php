<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../cli/i18n/I18nValue.php';

class I18nValueTest extends PHPUnit\Framework\TestCase {
	public static function testConstructorWithoutState(): void {
		$value = new I18nValue('some value');
		self::assertSame('some value', $value->getValue());
		self::assertFalse($value->isIgnore());
		self::assertFalse($value->isTodo());
	}

	public static function testConstructorWithUnknownState(): void {
		$value = new I18nValue('some value -> unknown');
		self::assertSame('some value', $value->getValue());
		self::assertFalse($value->isIgnore());
		self::assertFalse($value->isTodo());
	}

	public static function testConstructorWithTodoState(): void {
		$value = new I18nValue('some value -> todo');
		self::assertSame('some value', $value->getValue());
		self::assertFalse($value->isIgnore());
		self::assertTrue($value->isTodo());
	}

	public static function testConstructorWithIgnoreState(): void {
		$value = new I18nValue('some value -> ignore');
		self::assertSame('some value', $value->getValue());
		self::assertTrue($value->isIgnore());
		self::assertFalse($value->isTodo());
	}

	public static function testClone(): void {
		$value = new I18nValue('some value');
		$clonedValue = clone $value;
		self::assertSame('some value', $value->getValue());
		self::assertSame('some value', $clonedValue->getValue());
		self::assertFalse($value->isIgnore());
		self::assertFalse($clonedValue->isIgnore());
		self::assertFalse($value->isTodo());
		self::assertTrue($clonedValue->isTodo());
	}

	public static function testEqualWhenValueIsIdentical(): void {
		$value = new I18nValue('some value');
		$clonedValue = clone $value;
		self::assertTrue($value->equal($clonedValue));
		self::assertTrue($clonedValue->equal($value));
	}

	public static function testEqualWhenValueIsDifferent(): void {
		$value = new I18nValue('some value');
		$otherValue = new I18nValue('some other value');
		self::assertFalse($value->equal($otherValue));
		self::assertFalse($otherValue->equal($value));
	}

	public static function testStates(): void {
		$reflectionProperty = new ReflectionProperty(I18nValue::class, 'state');
		$reflectionProperty->setAccessible(true);

		$value = new I18nValue('some value');
		self::assertNull($reflectionProperty->getValue($value));
		$value->markAsDirty();
		self::assertSame('dirty', $reflectionProperty->getValue($value));
		$value->unmarkAsIgnore();
		self::assertSame('dirty', $reflectionProperty->getValue($value));
		$value->markAsIgnore();
		self::assertSame('ignore', $reflectionProperty->getValue($value));
		$value->unmarkAsIgnore();
		self::assertNull($reflectionProperty->getValue($value));
		$value->markAsTodo();
		self::assertSame('todo', $reflectionProperty->getValue($value));
	}

	public static function testToString(): void {
		$value = new I18nValue('some value');
		self::assertSame('some value', $value->__toString());
		$value->markAsTodo();
		self::assertSame('some value -> todo', $value->__toString());
	}
}
