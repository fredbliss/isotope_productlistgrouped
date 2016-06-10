<?php

namespace IntelligentSpark\Model\Product;

use Isotope\Model\Product\Standard as IsotopeStandard;

/**
* Class Product
*
* Provide methods to handle Isotope products.
* @copyright  Isotope eCommerce Workgroup 2009-2012
* @author     Andreas Schempp <andreas.schempp@terminal42.ch>
* @author     Fred Bliss <fred.bliss@intelligentspark.com>
* @author     Christian de la Haye <service@delahaye.de>
*/
class Extended extends IsotopeStandard
{

    /**
     * Return select statement to load product data including multilingual fields
     * @param array an array of columns
     * @return string
     */
    public static function getSelectStatement($arrColumns=false)
    {
        static $strSelect = '';

        if ($strSelect == '' || $arrColumns !== false)
        {
            $arrSelect = ($arrColumns !== false) ? $arrColumns : array('p1.*');
            $arrSelect[] = "'".$GLOBALS['TL_LANGUAGE']."' AS language";

            foreach ($GLOBALS['ISO_CONFIG']['multilingual'] as $attribute)
            {
                if ($arrColumns !== false && !in_array('p1.'.$attribute, $arrColumns))
                    continue;

                $arrSelect[] = "IFNULL(p2.$attribute, p1.$attribute) AS {$attribute}";
            }

            foreach ($GLOBALS['ISO_CONFIG']['fetch_fallback'] as $attribute)
            {
                if ($arrColumns !== false && !in_array('p1.'.$attribute, $arrColumns))
                    continue;

                $arrSelect[] = "p1.$attribute AS {$attribute}_fallback";
            }

            $strQuery = "
SELECT
	" . implode(', ', $arrSelect) . ",
	t.class AS product_class,
	c.sorting, pg.title AS category_title,
	c.page_id AS page_ref_id 
FROM tl_iso_products p1
INNER JOIN tl_iso_producttypes t ON t.id=p1.type
LEFT OUTER JOIN tl_iso_products p2 ON p1.id=p2.pid AND p2.language='" . $GLOBALS['TL_LANGUAGE'] . "'
LEFT OUTER JOIN tl_iso_product_categories c ON p1.id=c.pid
LEFT OUTER JOIN tl_page pg ON c.page_id=pg.id";

            if ($arrColumns !== false)
            {
                return $strQuery;
            }

            $strSelect = $strQuery;
        }

        return $strSelect;
    }
}