<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Paypal
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Payment information import/export model
 * Collects and provides access to PayPal-specific payment data
 */
class Mage_Paypal_Model_Info
{
    /**
     * Cross-models public exchange keys
     *
     * @var string
     */
    const PAYER_ID       = 'payer_id';
    const PAYER_EMAIL    = 'email';
    const PAYER_STATUS   = 'payer_status';
    const ADDRESS_ID     = 'address_id';
    const ADDRESS_STATUS = 'address_status';
    const PROTECTION_EL  = 'protection_eligibility';
    const FRAUD_FILTERS  = 'collected_fraud_filters';
    const CORRELATION_ID = 'correlation_id';
    const AVS_CODE       = 'avs_result';
    const CVV2_MATCH     = 'cvv2_check_result';
    const CENTINEL_VPAS  = 'centinel_vpas_result';
    const CENTINEL_ECI   = 'centinel_eci_result';

    /**
     * All payment information map
     *
     * @var array
     */
    protected $_paymentMap = array(
        self::PAYER_ID       => 'paypal_payer_id',
        self::PAYER_EMAIL    => 'paypal_payer_email',
        self::PAYER_STATUS   => 'paypal_payer_status',
        self::ADDRESS_ID     => 'paypal_address_id',
        self::ADDRESS_STATUS => 'paypal_address_status',
        self::PROTECTION_EL  => 'paypal_protection_eligibility',
        self::FRAUD_FILTERS  => 'paypal_fraud_filters',
        self::CORRELATION_ID => 'paypal_correlation_id',
        self::AVS_CODE       => 'paypal_avs_code',
        self::CVV2_MATCH     => 'paypal_cvv2_match',
        self::CENTINEL_VPAS  => self::CENTINEL_VPAS,
        self::CENTINEL_ECI   => self::CENTINEL_ECI,
    );

    /**
     * Map of payment information available to customer
     *
     * @var array
     */
    protected $_paymentPublicMap = array(
        'paypal_payer_email',
    );

    /**
     * Rendered payment map cache
     *
     * @var array
     */
    protected $_paymentMapFull = array();

    /**
     * All available payment info getter
     *
     * @param Mage_Payment_Model_Info $payment
     * @param bool $labelValuesOnly
     * @return array
     */
    public function getPaymentInfo(Mage_Payment_Model_Info $payment, $labelValuesOnly = false)
    {
        // collect paypal-specific info
        $result = $this->_getFullInfo(array_values($this->_paymentMap), $payment, $labelValuesOnly);

        // add last_trans_id
        $label = Mage::helper('paypal')->__('Last Transaction ID');
        $value = $payment->getLastTransId();
        if ($labelValuesOnly) {
            $result[$label] = $value;
        } else {
            $result['last_trans_id'] = array('label' => $label, 'value' => $value);
        }

        return $result;
    }

    /**
     * Public payment info getter
     *
     * @param Mage_Payment_Model_Info $payment
     * @param bool $labelValuesOnly
     * @return array
     */
    public function getPublicPaymentInfo(Mage_Payment_Model_Info $payment, $labelValuesOnly = false)
    {
        return $this->_getFullInfo($this->_paymentPublicMap, $payment, $labelValuesOnly);
    }

    /**
     * Grab data from source and map it into payment
     *
     * @param array|Varien_Object|callback $from
     * @param Mage_Payment_Model_Info $payment
     */
    public function importToPayment($from, Mage_Payment_Model_Info $payment)
    {
        Varien_Object_Mapper::accumulateByMap($from, array($payment, 'setAdditionalInformation'), $this->_paymentMap);
    }

    /**
     * Grab data from payment and map it into target
     *
     * @param Mage_Payment_Model_Info $payment
     * @param array|Varien_Object|callback $to
     * @param array $map
     * @return array|Varien_Object
     */
    public function &exportFromPayment(Mage_Payment_Model_Info $payment, $to, array $map = null)
    {
        Varien_Object_Mapper::accumulateByMap(array($payment, 'getAdditionalInformation'), $to,
            $map ? $map : array_flip($this->_paymentMap)
        );
        return $to;
    }

    /**
     * Render info item
     *
     * @param array $keys
     * @param Mage_Payment_Model_Info $payment
     * @param bool $labelValuesOnly
     */
    protected function _getFullInfo(array $keys, Mage_Payment_Model_Info $payment, $labelValuesOnly)
    {
        $result = array();
        foreach ($keys as $key) {
            if (!isset($this->_paymentMapFull[$key])) {
                $this->_paymentMapFull[$key] = array();
            }
            if (!isset($this->_paymentMapFull[$key]['label'])) {
                if (!$payment->hasAdditionalInformation($key)) {
                    $this->_paymentMapFull[$key]['label'] = false;
                    $this->_paymentMapFull[$key]['value'] = false;
                } else {
                    $value = $payment->getAdditionalInformation($key);
                    $this->_paymentMapFull[$key]['label'] = $this->_getLabel($key);
                    $this->_paymentMapFull[$key]['value'] = $this->_getValue($value, $key);
                }
            }
            if (!empty($this->_paymentMapFull[$key]['value'])) {
                if ($labelValuesOnly) {
                    $result[$this->_paymentMapFull[$key]['label']] = $this->_paymentMapFull[$key]['value'];
                } else {
                    $result[$key] = $this->_paymentMapFull[$key];
                }
            }
        }
        return $result;
    }

    /**
     * Render info item labels
     *
     * @param string $key
     */
    protected function _getLabel($key)
    {
        switch ($key) {
            case 'paypal_payer_id':
                return Mage::helper('paypal')->__('Payer ID');
            case 'paypal_payer_email':
                return Mage::helper('paypal')->__('Payer Email');
            case 'paypal_payer_status':
                return Mage::helper('paypal')->__('Payer Status');
            case 'paypal_address_id':
                return Mage::helper('paypal')->__('Payer Address ID');
            case 'paypal_address_status':
                return Mage::helper('paypal')->__('Payer Address Status');
            case 'paypal_protection_eligibility':
                return Mage::helper('paypal')->__('Merchant Protection Eligibility');
            case 'paypal_fraud_filters':
                return Mage::helper('paypal')->__('Triggered Fraud Filters');
            case 'paypal_correlation_id':
                return Mage::helper('paypal')->__('Last Corellation ID');
            case 'paypal_avs_code':
                return Mage::helper('paypal')->__('Aaddress Verification System Response');
            case 'paypal_cvv2_match':
                return Mage::helper('paypal')->__('CVV2 Check Result by PayPal');
            case self::CENTINEL_VPAS:
                return Mage::helper('paypal')->__('Centinel Visa Payer Authentication Service Result');
            case self::CENTINEL_ECI:
                return Mage::helper('paypal')->__('Centinel Electronic Commerce Indicator');
        }
        return '';
    }

    /**
     * Apply a filter upon value getting
     *
     * @param string $value
     * @param string $key
     * @return string
     */
    protected function _getValue($value, $key)
    {
        $label = '';
        switch ($key) {
            case 'paypal_avs_code':
                $label = $this->_getAvsLabel($value);
                break;
            case 'paypal_cvv2_match':
                $label = $this->_getCvv2Label($value);
                break;
            case self::CENTINEL_VPAS:
                $label = $this->_getCentinelVpasLabel($value);
                break;
            case self::CENTINEL_ECI:
                $label = $this->_getCentinelEciLabel($value);
                break;
            default:
                return $value;
        }
        return sprintf('#%s%s', $value, $value == $label ? '' : ': ' . $label);
    }

    /**
     * Attempt to convert AVS check result code into label
     *
     * @see https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_api_nvp_AVSResponseCodes
     * @param string $value
     * @return string
     */
    protected function _getAvsLabel($value)
    {
        switch ($value) {
            // Visa, MasterCard, Discover and American Express
            case 'A':
                return Mage::helper('paypal')->__('Matched Address only (no ZIP)');
            case 'B': // international "A"
                return Mage::helper('paypal')->__('Matched Address only (no ZIP). International');
            case 'N':
                return Mage::helper('paypal')->__('No Details matched');
            case 'C': // international "N"
                return Mage::helper('paypal')->__('No Details matched. International');
            case 'X':
                return Mage::helper('paypal')->__('Exact Match. Address and nine-digit ZIP code');
            case 'D': // international "X"
                return Mage::helper('paypal')->__('Exact Match. Address and Postal Code. International');
            case 'F': // UK-specific "X"
                return Mage::helper('paypal')->__('Exact Match. Address and Postal Code. UK-specific');
            case 'E':
                return Mage::helper('paypal')->__('N/A. Not allowed for MOTO (Internet/Phone) transactions');
            case 'G':
                return Mage::helper('paypal')->__('N/A. Global Unavailable');
            case 'I':
                return Mage::helper('paypal')->__('N/A. International Unavailable');
            case 'Z':
                return Mage::helper('paypal')->__('Matched five-digit ZIP only (no Address)');
            case 'P': // international "Z"
                return Mage::helper('paypal')->__('Matched Postal Code only (no Address)');
            case 'R':
                return Mage::helper('paypal')->__('N/A. Retry');
            case 'S':
                return Mage::helper('paypal')->__('N/A. Service not Supported');
            case 'U':
                return Mage::helper('paypal')->__('N/A. Unavailable');
            case 'W':
                return Mage::helper('paypal')->__('Matched whole nine-didgit ZIP (no Address)');
            case 'Y':
                return Mage::helper('paypal')->__('Yes. Matched Address and five-didgit ZIP');
            // Maestro and Solo
            case '0':
                return Mage::helper('paypal')->__('All the address information matched');
            case '1':
                return Mage::helper('paypal')->__('None of the address information matched');
            case '2':
                return Mage::helper('paypal')->__('Part of the address information matched');
            case '3':
                return Mage::helper('paypal')->__('N/A. The merchant did not provide AVS information');
            case '4':
                return Mage::helper('paypal')->__('N/A. Address not checked, or acquirer had no response. Service not available');
            default:
                return $value;
        }
    }

    /**
     * Attempt to convert CVV2 check result code into label
     *
     * @see https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_api_nvp_AVSResponseCodes
     * @param string $value
     * @return string
     */
    protected function _getCvv2Label($value)
    {
        switch ($value) {
            // Visa, MasterCard, Discover and American Express
            case 'M':
                return Mage::helper('paypal')->__('Matched (CVV2CSC)');
            case 'N':
                return Mage::helper('paypal')->__('No match');
            case 'P':
                return Mage::helper('paypal')->__('N/A. Not processed');
            case 'S':
                return Mage::helper('paypal')->__('N/A. Service not supported');
            case 'U':
                return Mage::helper('paypal')->__('N/A. Service not available');
            case 'X':
                return Mage::helper('paypal')->__('N/A. No response');
            // Maestro and Solo
            case '0':
                return Mage::helper('paypal')->__('Matched (CVV2)');
            case '1':
                return Mage::helper('paypal')->__('No match');
            case '2':
                return Mage::helper('paypal')->__('N/A. The merchant has not implemented CVV2 code handling');
            case '3':
                return Mage::helper('paypal')->__('N/A. Merchant has indicated that CVV2 is not present on card');
            case '4':
                return Mage::helper('paypal')->__('N/A. Service not available');
            default:
                return $value;
        }
    }

    /**
     * Attempt to convert centinel VPAS result into label
     *
     * @see https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_DoDirectPayment
     * @param string $value
     * @return string
     */
    private function _getCentinelVpasLabel($value)
    {
        switch ($value) {
            case '2':
            case 'D':
                return Mage::helper('paypal')->__('Authenticated, Good Result');
            case '1':
                return Mage::helper('paypal')->__('Authenticated, Bad Result');
            case '3':
            case '6':
            case '8':
            case 'A':
            case 'C':
                return Mage::helper('paypal')->__('Attempted Authentication, Good Result');
            case '4':
            case '7':
            case '9':
                return Mage::helper('paypal')->__('Attempted Authentication, Bad Result');
            case '':
            case '0':
            case 'B':
                return Mage::helper('paypal')->__('No Liability Shift');
            default:
                return $value;
        }
    }

    /**
     * Attempt to convert centinel ECI result into label
     *
     * @see https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_DoDirectPayment
     * @param string $value
     * @return string
     */
    private function _getCentinelEciLabel($value)
    {
        switch ($value) {
            case '01':
            case '07':
                return Mage::helper('paypal')->__('Merchant Liability');
            case '02':
            case '05':
            case '06':
                return Mage::helper('paypal')->__('Issuer Liability');
            default:
                return $value;
        }
    }
}
