<?php

namespace Zoop\Promotion\Condition;

use \Exception;
use \ReflectionClass;
use Zoop\Promotion\Condition\Rule\AbstractRule;
use Zoop\Promotion\Condition\Rule\RuleInterface;
use Zoop\Promotion\DataModel\Condition\ConditionInterface;
use Zoop\Promotion\DataModel\Discount\DiscountInterface;

class Compiler
{
    const DISCOUNT_LEVEL_CART = 'Cart';
    const DISCOUNT_LEVEL_PRODUCT = 'Product';
    const RULE_CLASSNAME = 'Zoop\Promotion\Condition\Rule\%s\%s';

    private $discount;

    /**
     *
     * @return DiscountInterface
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     *
     * @param DiscountInterface $discount
     */
    public function setDiscount(DiscountInterface $discount)
    {
        $this->discount = $discount;
    }

    /**
     *
     * @param boolean $force
     * @return string|boolean
     */
    public function compile()
    {
        $compiledCondition = [];
        $compiledConditionFunctions = [];
        $discount = $this->getDiscount();

        if ($discount instanceof DiscountInterface) {
            $conditions = $discount->getConditions();

            if (!empty($conditions)) {
                $num = count($conditions) - 1;

                /* @var $condition ConditionInterface */
                foreach ($conditions as $i => $condition) {
                    $variableRule = $this->getConditionVariable($condition);

                    if (!empty($variableRule)) {
                        if ($variableRule->getType() === AbstractRule::TYPE_FUNCTION) {
                            $functionName = uniqid('$condition_');
                            $compiledConditionFunctions[] = $functionName . ' = ' . $variableRule->getVariable() . ';';
                            $variableName = $functionName . '()';
                        } else {
                            $variableName = $variableRule->getVariable();
                        }

                        $compiledCondition[] = sprintf('%s %s %s', $variableName, $condition->getConditionalOperator(), $this->getConditionValue($condition));

                        if ($i < $num) {
                            $compiledCondition[] = (string) $condition->getLogicalOperator();
                        }
                    }
                }

                //the conditional function
                if (!empty($compiledCondition)) {
                    $compiled[] = implode("\n", $compiledConditionFunctions);
                    $compiled[] = 'if(' . implode(' ', $compiledCondition) . ') {';
                    $compiled[] = '%s';
                    $compiled[] = '}';

                    return implode("\n", $compiled);
                }
            }
        }

        return false;
    }

    private function getConditionValue(ConditionInterface $condition)
    {
        $value = $condition->getValue();
        if (!is_numeric($value)) {
            return '"' . $value . '"';
        }
        return $value;
    }

    /**
     *
     * @param ConditionInterface $condition
     * @return RuleInterface
     */
    private function getConditionVariable(ConditionInterface $condition)
    {

        if (!empty($condition)) {
            $className = $this->getClassName($condition);

            $level = $this->getDiscount()->getLevel();

            if (!empty($level)) {
                $class = sprintf(self::RULE_CLASSNAME, $level, $className);

                $ruleClass = $this->instantiateRuleClass($class);
                if ($ruleClass instanceof RuleInterface) {
                    return $ruleClass;
                }
            }
        }
    }

    private function getClassName($className)
    {
        $className = explode('\\', get_class($className));
        return $className[count($className) - 1];
    }

    /**
     *
     * @param string $class
     * @return RuleInterface
     */
    private function instantiateRuleClass($class)
    {
        try {
            $ref = new ReflectionClass($class);
            return $ref->newInstance();
        } catch (Exception $ex) {
            die('Could not instantiate condition: ' . $ex->getMessage());
        }
    }

}
