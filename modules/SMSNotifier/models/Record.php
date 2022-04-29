<?php
/**
 * Record Class for SMSNotifier.
 *
 * @copyright YetiForce S.A.
 * @license YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */

/**
 * Record Class for SMSNotifier.
 */
class SMSNotifier_Record_Model extends Vtiger_Record_Model
{
	/**
	 * Function defines the ability to edit a record.
	 *
	 * @return bool
	 */
	public function isEditable(): bool
	{
		return parent::isEditable() && !\in_array($this->get('smsnotifier_status'), ['PLL_DELIVERED', 'PLL_REPLY']);
	}

	/**
	 * Send sms.
	 *
	 * @return bool
	 */
	public function send(): bool
	{
		$result = false;
		if ($this->isEditable() && ($provider = \App\Integrations\SMSProvider::getDefaultProvider())) {
			$result = $provider->sendByRecord($this);
			$this->set('smsnotifier_status', $result ? 'PLL_SENT' : 'PLL_FAILED');
			$this->save();
		}

		return $result;
	}
}
