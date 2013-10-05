<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Description: Advanced Community Engine
 * @Version: 0.9a
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

class PluginLs_ActionEngine extends ActionPlugin {

    public function Init() {
        $this->SetDefaultEvent('lib');
    }

    protected function RegisterEvent() {
        $this->AddEventPreg('/^lib$/i', '/^external$/i', '/^kcaptcha$/i', 'EventLibCaptcha');
    }

    /**
     * Отображение каптчи старым способом (LS-compatible)
     */
    protected function EventLib() {
        if (Router::GetCurrentPath() == 'external/kcaptcha/index.php') {
            return $this->EventLibCaptcha();
        }
    }

    protected function EventLibCaptcha() {
        $captcha = new KCAPTCHA();
        $_SESSION['captcha_keystring'] = $captcha->getKeyString();
    }

}

// EOF