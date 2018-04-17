<?php

namespace Shopsys\ShopBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Order\Order;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class ExtendedOrder extends Order
{
    /**
     * @return string
     */
    public function getNumber()
    {
        return 'EXTENDED # ' . parent::getNumber();
    }
}
