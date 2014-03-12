<?php

namespace Zoop\Promotion;

use \Exception;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Promotion\DataModel\Discount\DiscountInterface;
use Zoop\Promotion\Discount\Compiler as DiscountCompiler;

class Compiler
{
    use CartVariablesTrait;
    use ProductVariablesTrait;

    const VARIABLE_DISCOUNT = '$discount';
    const VARIABLE_FUNCTIONS = '$functions';
    const VARIABLE_FUNCTION = '$function';

    private $promotion;
    private $cartFunctions = [];
    private $productFunctions = [];
    private $compiledCartFunction;
    private $compiledProductFunction;

    /**
     *
     * @return PromotionInterface
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     *
     * @param PromotionInterface $promotion
     */
    public function setPromotion(PromotionInterface $promotion)
    {
        $this->promotion = $promotion;
    }

    public function compile()
    {
        $discounts = $this->getPromotion()->getDiscounts();

        /* @var $discount DiscountInterface */
        foreach ($discounts as $discount) {
            $compiledFunction = $this->getCompiledDiscountFunction($discount);

            if ($discount->getLevel() === DiscountCompiler::DISCOUNT_LEVEL_CART) {
                $this->addCartFunction($compiledFunction);
            } elseif ($discount->getLevel() === DiscountCompiler::DISCOUNT_LEVEL_PRODUCT) {
                $this->addProductFunction($compiledFunction);
            }
        }

        //compile cart discount functions
        $this->setCompiledCartFunction($this->compileCartFunction());
        //compile product discount functions
        $this->setCompiledProductFunction($this->compileProductFunction());
    }

    private function compileCartFunction()
    {
        $function = [];
        $compiledFunctions = implode("\n", $this->getCartFunctions());

        if (!empty($compiledFunctions)) {
            $function[] = $this->getFunctionHeader();
            $function[] = 'if(is_array(' . $this->getVariableCartProducts() . ')) {';
            $function[] = 'foreach(' . $this->getVariableCartProducts() . ' as ' . $this->getVariableCartProductPrefix() . ') {';
            $function[] = 'if(in_array(' . $this->getVariableCartProductId() . ', ' . $this->getVariableConditionalProducts() . ') || in_array(0, ' . $this->getVariableConditionalProducts() . ')) {';
            $function[] = self::VARIABLE_FUNCTIONS . '[] = ' . $compiledFunctions;
            $function[] = '}';
            $function[] = '}';
            $function[] = '}';
            $function[] = $this->getFunctionFooter();

            $func = implode("\n", $function);
            if ($func !== false) {
                return $func;
            } else {
                throw new Exception('Cannot create function. Check: ' . implode("\n", $function));
            }
        }
        return false;
    }

    private function compileProductFunction()
    {
        $function = [];
        $compiledFunctions = implode("\n", $this->getProductFunctions());

        if (!empty($compiledFunctions)) {
            $function[] = $this->getFunctionHeader();
            $function[] = 'if(in_array(' . $this->getVariableProductId() . ', ' . $this->getVariableConditionalProducts() . ') || in_array(0, ' . $this->getVariableConditionalProducts() . ')) {';
            $function[] = self::VARIABLE_FUNCTIONS . '[] = ' . $compiledFunctions;
            $function[] = '}';
            $function[] = $this->getFunctionFooter();

            $func = implode("\n", $function);
            if ($func !== false) {
                return $func;
            } else {
                throw new Exception('Cannot create function. Check: ' . implode("\n", $function));
            }
        }
        return false;
    }

    private function getFunctionHeader()
    {
        $header[] = $this->getConditionalProducts();
        $header[] = self::VARIABLE_DISCOUNT . ' = 0;';
        $header[] = self::VARIABLE_FUNCTIONS . ' = [];';

        return implode("\n", $header);
    }

    private function getFunctionFooter()
    {
        $footer[] = 'foreach(' . self::VARIABLE_FUNCTIONS . ' as ' . self::VARIABLE_FUNCTION . ') {';
        $footer[] = self::VARIABLE_DISCOUNT . ' += ' . self::VARIABLE_FUNCTION . '();';
        $footer[] = '}';
        $footer[] = 'return ' . self::VARIABLE_DISCOUNT . ';';

        return implode("\n", $footer);
    }

    private function getCompiledDiscountFunction(DiscountInterface $discount)
    {
        $compiler = new DiscountCompiler();
        $compiler->setDiscount($discount);

        return $compiler->compile();
    }

    private function getConditionalProducts()
    {
        $productIds = $this->getPromotion()->getProductIds();
        if (is_array($productIds) && (in_array(0, $productIds) || !empty($productIds))) {
            return $this->getVariableConditionalProducts() . ' = [' . implode(', ', $productIds) . '];';
        } else {
            return $this->getVariableConditionalProducts() . ' = [];';
        }
    }

    public function getCartFunctions()
    {
        return $this->cartFunctions;
    }

    public function getProductFunctions()
    {
        return $this->productFunctions;
    }

    public function setCartFunctions($cartFunctions)
    {
        $this->cartFunctions = $cartFunctions;
    }

    public function addCartFunction($cartFunction)
    {
        $this->cartFunctions[] = $cartFunction;
    }

    public function setProductFunctions($productFunctions)
    {
        $this->productFunctions = $productFunctions;
    }

    public function addProductFunction($productFunction)
    {
        $this->productFunctions[] = $productFunction;
    }

    public function getCompiledCartFunction()
    {
        return $this->compiledCartFunction;
    }

    public function getCompiledProductFunction()
    {
        return $this->compiledProductFunction;
    }

    public function setCompiledCartFunction($compiledCartFunction)
    {
        $this->compiledCartFunction = $compiledCartFunction;
    }

    public function setCompiledProductFunction($compiledProductFunction)
    {
        $this->compiledProductFunction = $compiledProductFunction;
    }
}
