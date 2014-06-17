<?php
require 'money.php';
require 'currency.php';
require 'UnknownCurrencyException.php';

class moneyTest extends PHPUnit_Framework_TestCase
{
    public function testCanBeCreated()
    {
        $money = money::USD(123);

        $this->assertEquals(123, $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency()->getName());
    }
    /**
    * @expectedException UnknownCurrencyException
    */
    public function testCreatingUnknownCurrency()
    {
        $a = money::XYZ(123);
    }
    /**
    * @expectedException InvalidArgumentException
    */
    public function testAmountHaveToBeInteger()
    {
        $a = money::USD(12.34);
    }
    public function testToStringConversion()
    {
        $money = money::USD(123);

        $this->assertEquals("1.23 USD", (string)$money);
    }
    public function testIsSameAmountNonStrict()
    {
        $a = money::USD(123);
        $a->setRestrictionToSameCurrency(false);
        $this->assertEquals(true, $a->isSameAmount(money::USD(123)));
    }
    public function testIsSameAmountStrict()
    {
        $a = money::USD(123);
        $a->setRestrictionToSameCurrency(true);
        $this->assertEquals(true, $a->isSameAmount(money::EUR(123)));
    }
}