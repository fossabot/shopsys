<?php

namespace Shopsys\ShopBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\Product;

/**
 * @ORM\Table(
 *     name="products",
 *     indexes={
 *         @ORM\Index(columns={"variant_type"})
 *     }
 * )
 * @ORM\Entity
 */
class ExtendedProduct extends Product
{
    /**
     * @param string|null $locale
     * @return string|null
     */
    public function getName($locale = null)
    {
        return parent::getName($locale) !== null ? 'EXTENDED PRODUCT ' . parent::getName($locale) : null;
    }
}
