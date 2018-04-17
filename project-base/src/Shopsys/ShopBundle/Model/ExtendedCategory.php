<?php

namespace Shopsys\ShopBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Shopsys\FrameworkBundle\Model\Category\Category;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="categories")
 * @ORM\Entity
 */
class ExtendedCategory extends Category
{
    /**
     * @param string|null $locale
     * @return string|null
     */
    public function getName($locale = null)
    {
        return parent::getName($locale) !== null ? 'EXTENDED CATEGORY ' . parent::getName($locale) : null;
    }
}
