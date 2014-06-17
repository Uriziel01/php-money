<?PHP

class Currency
{
	private $name;
	private $rate = 1.00;
	private $leftSymbol;
	private $rightSymbol;
	private $precision = 2;
	private static $existingCurrencies = ['EUR' => ['rightSymbol' => '€', 'rate' => 4.1877], 'PLN' => ['rightSymbol' => 'Zł'], 'USD' => ['rate' => 3.1234, 'leftSymbol' => '$'], 'GBP' => ['rate' => 5.1996, 'leftSymbol' => '£'], 'CLP' => ['rate' => 0.0221, 'precision' => 0]];

	public function __construct($name)
	{
		if(!in_array($name, array_keys(self::$existingCurrencies))){
			throw new UnknownCurrencyException($name);
		}
		$this->name = (string)$name;
		if(isset(self::$existingCurrencies[$name]['rate']))
		{
			$this->rate = self::$existingCurrencies[$name]['rate'];
		}
		if(isset(self::$existingCurrencies[$name]['precision'])){
			$this->precision = self::$existingCurrencies[$name]['precision'];
		}
	}
	public function getName()
	{
		return $this->name;
	}
	public function getAbbreviation()
	{
		return $this->abbreviation;
	}
	public function __toString()
	{
		return $this->getName();
	}
	public function getPrecision()
	{
		return $this->precision;
	}
	public function getRate()
	{
		return $this->rate;
	}
	public function equals(currency $other)
	{
		return $this->name === $other->name;
	}
	public static function getExistingCurrencies()
	{
		return self::$existingCurrencies;
	}
	public function getLeftSymbol()
	{
		return $this->leftSymbol;
	}
	public function getRightSymbol()
	{
		return $this->rightSymbol;
	}
}