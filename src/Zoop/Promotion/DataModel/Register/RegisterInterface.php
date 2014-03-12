<?php

namespace Zoop\Promotion\DataModel\Register;

interface RegisterInterface
{

    public function getId();

    public function getState();

    public function setState($state);

    public function getPromotion();
}
