<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Strekoza\SkipFormKey\Plugin;

use Magento\Framework\App\Area;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Exception\LocalizedException;

class FormKeyValidatorSkip
{
    public const ACCEPT_MODULE = ['checkout', 'makeoffer'];

    /**
     * @var AppState
     */
    private $appState;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param AppState $appState
     * @param RequestInterface $request
     */
    public function __construct(
        AppState         $appState,
        RequestInterface $request
    )
    {
        $this->appState = $appState;
        $this->request = $request;
    }

    /**
     * @param $subject
     * @param $result
     * @return bool
     */
    public function afterValidate($subject, $result)
    {
        try {
            $areaCode = $this->appState->getAreaCode();
        } catch (LocalizedException $exception) {
            $areaCode = null;
        }

        if (in_array(
            $areaCode,
            [Area::AREA_ADMINHTML],
            true
        )
        ) {
            return $result;
        }

        $moduleName = $this->request->getModuleName();
        if ($moduleName && in_array(
                $moduleName,
                self::ACCEPT_MODULE,
                true
            )) {
            return true;
        }

        return $result;
    }
}