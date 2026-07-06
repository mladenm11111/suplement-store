<?php

use PHPUnit\Framework\TestCase;

class SuplementStoreTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new Controller();
    }

    protected function tearDown(): void
    {
    }

    // ============================
    // UNIT TESTOVI — isValidId()
    // ============================

    public function testIsValidIdReturnsTrueForPositiveNumber(): void
    {
        $this->assertTrue($this->controller->isValidId('1'));
        $this->assertTrue($this->controller->isValidId('5'));
        $this->assertTrue($this->controller->isValidId('100'));
    }

    public function testIsValidIdReturnsFalseForZero(): void
    {
        $this->assertFalse($this->controller->isValidId('0'));
    }

    public function testIsValidIdReturnsFalseForNegativeNumber(): void
    {
        $this->assertFalse($this->controller->isValidId('-1'));
    }

    public function testIsValidIdReturnsFalseForString(): void
    {
        $this->assertFalse($this->controller->isValidId('abc'));
    }

    public function testIsValidIdReturnsFalseForEmpty(): void
    {
        $this->assertFalse($this->controller->isValidId(''));
    }

    public function testIsValidIdReturnsFalseForNull(): void
    {
        $this->assertFalse($this->controller->isValidId(null));
    }
}