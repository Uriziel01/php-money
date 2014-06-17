<?PHP
class money
{
    private $currency;
    private $amount;
    private $restrictToSameCurrency = false;

    public function __construct($amount, $currency)
    {
        if (!is_int($amount)) {
            throw new InvalidArgumentException("The amount must be an integer.");
        }
        $this->amount = $amount;
        $this->currency = $currency;
    }
    public static function __callStatic($method, $arguments)
    {
        return new money($arguments[0], new currency($method));
    }
    public function __toString()
    {
        return (string) ($this->amount / (pow(10, $this->currency->getPrecision()))) . ' ' . (string) $this->currency;
    }
    public function isSameCurrency(Money $other)
    {
        if($this->currency->equals($other->currency))
        {
            return true;
        }
        return false;
    }
    public function setRestrictionToSameCurrency($status = true)
    {
        if(!is_bool($status))
        {
            throw new InvalidArgumentException("The status must be an boolean.");
        }
        $this->restrictToSameCurrency = $status;
    }
    public function isSameAmount(Money $other)
    {
        if($this->restrictToSameCurrency && !$this->isSameCurrency($other))
        {
            throw new InvalidArgumentException('Different currencies');
        }
        if(!$this->currency->equals($other->currency))
        {
            $other = $other->convertTo((string)$this->currency);
        }
        if($this->amount === $other->amount)
        {
            return true;
        }
        return false;
    }
    public function equals(money $other)
    {
        if($this->currency->equals($other->currency) && $this->amount === $other->amount)
        {
            return true;
        }
        return false;
    }
    public function getCurrency()
    {
        return $this->currency;
    }
    public function add(money $other)
    {
        if($this->restrictToSameCurrency && !$this->isSameCurrency($other))
        {
            throw new InvalidArgumentException('Different currencies');
        }
        $precisionDifference = $this->currency->getPrecision() - $other->currency->getPrecision();

        $ratesRatio = $other->currency->getRate() / $this->currency->getRate();
        $otherAmount = (int) round($other->amount * $ratesRatio, 0, PHP_ROUND_HALF_EVEN);

        return new self($this->amount + $otherAmount, $this->currency);
    }
    public function substract(money $other)
    {
        if($this->restrictToSameCurrency && !$this->isSameCurrency($other))
        {
            throw new InvalidArgumentException('Different currencies');
        }

        $ratesRatio = $other->currency->getRate() / $this->currency->getRate();
        $otherAmount = (int) round($other->amount * $ratesRatio, 0, PHP_ROUND_HALF_EVEN);
        return new self($this->amount - $otherAmount, $this->currency);
    }
    public function isPositive()
    {
        return $this->amount > 0;
    }
    public function isNegative()
    {
        return $this->amount < 0;
    }
    public function allocate(array $ratios)
    {
        $remainder = $this->amount;
        $results = [];
        $total = array_sum($ratios);

        foreach ($ratios as $ratio) {
            $share = (int) floor($this->amount * $ratio / $total);
            $results[] = new Money($share, $this->currency);
            $remainder -= $share;
        }
        for ($i = 0; $remainder > 0; $i++) {
            $results[$i]->amount++;
            $remainder--;
        }

        return $results;
    }
    public function convertTo($currencyName)
    {
        $converted = new money(0, new currency($currencyName));
        return $converted->add($this);
    }
    private function assertOperand($operand)
    {
        if (!is_int($operand) && !is_float($operand)) {
            throw new InvalidArgumentException('Operand should be an integer or a float');
        }
    }
    public function multiply($multiplier)
    {
        $product = (int) round($this->amount * $multiplier, 0, PHP_ROUND_HALF_EVEN);

        return new self($product, $this->currency);
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public static function parseMoneyFromString($string, $occurences = 0)
    {
        $results = [];
        $currencies = currency::getExistingCurrencies();
        foreach ($currencies as $currencyName => $currency) {
            $currency['leftSymbol'] = isset($currency['leftSymbol']) ? '\\' . $currency['leftSymbol'] : '';
            $currency['rightSymbol'] = isset($currency['rightSymbol']) ? '\\' . $currency['rightSymbol'] : '';
            $pattern  = '|' . $currency['leftSymbol'] . '([\ ]*)([0-9]{1,8})([\.\,]?)([0-9]{0,3})([\ ]*)' . $currency['rightSymbol'] . '|Si';
            preg_match_all($pattern, $string, $matches);
            $matches = array_filter($matches);
            if(count($matches) > 1)
            {
                $results[] = $matches;
            }
        }
        return $results;
    }
}