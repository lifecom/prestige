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

/**
 * Добавляет старые LS-методы для совместимости
 */
class PluginLs_ModuleViewer extends PluginLs_Inherit_ModuleViewer {
    /**
     * DEPRECATED
     */
    public function GetBlocks($bSort = false) {
        $aWidgets = $this->GetWidgets($bSort);
        $aBlocks = array();
        foreach ($aWidgets as $sGroup=>$aWidgetList) {
            foreach($aWidgetList as $oWidget) {
                $aParams = $oWidget->getParams();
                if ($sPlugin = $oWidget->GetPluginId()) {
                    if (!isset($aParams['plugin'])) $aParams['plugin'] = $sPlugin;
                }
                $aBlocks[$sGroup][]=array(
                    'type'     => ($oWidget->getType() == 'exec' ? 'block' : $oWidget->getType()),
                    'name'     => $oWidget->getName(),
                    'params'   => $oWidget->getParams(),
                    'priority' => $oWidget->getPriority(),
                );
            }
        }
        return $aBlocks;
    }

    /**
     * DEPRECATED
     */
    protected function DefineTypeBlock($sName, $sDir = null) {
        return $this->DefineWidgetType($sName, $sDir);
    }

    /**
     * DEPRECATED
     */
    protected function SortBlocks() {
        return $this->SortWidgets();
    }

    protected function DefineWidgetType(&$sName, $sDir = null, $sPlugin = null) {
        if (strpos($sName, 'widgets/widget.') === 0) {
            $sLsBlockName = str_replace('widgets/widget.', 'blocks/block.', $sName);
            if ($sLsBlockName = $this->TemplateExists(is_null($sDir) ? $sLsBlockName : rtrim($sDir, '/') . '/' . ltrim($sLsBlockName, '/'))) {
                // Если найден шаблон, то считаем, что это шаблонный LS-block
                $sName = $sLsBlockName;
                return 'template';
            }
        }
        return parent::DefineWidgetType($sName, $sDir, $sPlugin);
    }

    public function VarAssign() {
        parent::VarAssign();

        // В Alto CMS по умолчанию используется Smarty-переменная $aWidgets
        $this->Assign('aBlocks', $this->GetBlocks(true));

        // В Smarty 3.x рекомендуется использовать статический класс Config
        $this->Assign('oConfig', Config::getInstance());
    }

    protected function InitBlockParams() {
        return $this->InitWidgetParams();
    }

    public function AddBlock($sGroup, $sName, $aParams = array(), $iPriority = 5) {
        return $this->AddWidget($sGroup, $sName, $aParams, $iPriority);
    }

    public function AddBlocks($sGroup, $aBlocks, $ClearWidgets = true) {
        return AddWidgets($sGroup, $aBlocks, $ClearWidgets);
    }

    public function ClearBlocks($sGroup) {
        return $this->ClearWidgets($sGroup);
    }

    public function ClearBlocksAll() {
        return $this->ClearAllWidgets();
    }

    protected function BuildBlocks() {
        $sAction = strtolower(Router::GetAction());
        $sEvent = strtolower(Router::GetActionEvent());
        $sEventName = strtolower(Router::GetActionEventName());
        foreach ($this->aBlockRules as $sName => $aRule) {
            $bUse = false;
            /**
             * Если в правиле не указан список блоков, нам такое не нужно
             */
            if (!array_key_exists('blocks', $aRule)) continue;
            /**
             * Если не задан action для исполнения и нет ни одного шаблона path,
             * или текущий не входит в перечисленные в правиле
             * то выбираем следующее правило
             */
            if (!array_key_exists('action', $aRule) && !array_key_exists('path', $aRule)) continue;
            if (isset($aRule['action'])) {
                if (in_array($sAction, (array)$aRule['action'])) $bUse = true;
                if (array_key_exists($sAction, (array)$aRule['action'])) {
                    /**
                     * Если задан список event`ов и текущий в него не входит,
                     * переходи к следующему действию.
                     */
                    foreach ((array)$aRule['action'][$sAction] as $sEventPreg) {
                        if (substr($sEventPreg, 0, 1) == '/') {
                            /**
                             * Это регулярное выражение
                             */
                            if (preg_match($sEventPreg, $sEvent)) {
                                $bUse = true;
                                break;
                            }
                        } elseif (substr($sEventPreg, 0, 1) == '{') {
                            /**
                             * Это имя event'a (именованный евент, если его нет, то совпадает с именем метода евента в экшене)
                             */
                            if (trim($sEventPreg, '{}') == $sEventName) {
                                $bUse = true;
                                break;
                            }
                        } else {
                            /**
                             * Это название event`a
                             */
                            if ($sEvent == $sEventPreg) {
                                $bUse = true;
                                break;
                            }
                        }
                    }
                }
            }
            /**
             * Если не найдено совпадение по паре Action/Event,
             * переходим к поиску по regexp путей.
             */
            if (!$bUse && isset($aRule['path'])) {
                $sPath = rtrim(Router::GetPathWebCurrent(), '/');
                /**
                 * Проверяем последовательно каждый regexp
                 */
                foreach ((array)$aRule['path'] as $sRulePath) {
                    $sPattern = '~' . str_replace(array('/', '*'), array('\/', '[\w\-]+'), $sRulePath) . '~';
                    if (preg_match($sPattern, $sPath)) {
                        $bUse = true;
                        break 1;
                    }
                }

            }

            if ($bUse) {
                /**
                 * Если задан режим очистки блоков, сначала чистим старые блоки
                 */
                if (isset($aRule['clear'])) {
                    switch (true) {
                        /**
                         * Если установлен в true, значит очищаем все
                         */
                        case  ($aRule['clear'] === true):
                            $this->ClearBlocksAll();
                            break;

                        case is_string($aRule['clear']):
                            $this->ClearBlocks($aRule['clear']);
                            break;

                        case is_array($aRule['clear']):
                            foreach ($aRule['clear'] as $sGroup) {
                                $this->ClearBlocks($sGroup);
                            }
                            break;
                    }
                }
                /**
                 * Добавляем все блоки, указанные в параметре blocks
                 */
                foreach ($aRule['blocks'] as $sGroup => $aBlocks) {
                    foreach ((array)$aBlocks as $sName => $aParams) {
                        /**
                         * Если название блока указывается в параметрах
                         */
                        if (is_int($sName)) {
                            if (is_array($aParams)) {
                                $sName = $aParams['block'];
                            }
                        }
                        /**
                         * Если $aParams не являются массивом, значит передано только имя блока
                         */
                        if (!is_array($aParams)) {
                            $this->AddBlock($sGroup, $aParams);
                        } else {
                            $this->AddBlock(
                                $sGroup, $sName,
                                isset($aParams['params']) ? $aParams['params'] : array(),
                                isset($aParams['priority']) ? $aParams['priority'] : 5
                            );
                        }
                    }
                }
            }
        }
    }

    public function SmartyDefaultTemplateHandler($sType, $sName, &$sContent, &$iTimestamp, $oSmarty) {
        $sResult = parent::SmartyDefaultTemplateHandler($sType, $sName, $sContent, $iTimestamp, $oSmarty);
        if (!$sResult) {
            if ($sType == 'file') {
                if ((strpos($sName, 'widgets/widget.') === 0)) {
                    $sFile = Config::Get('path.smarty.template') . str_replace('widgets/widget.', 'blocks/block.', $sName);
                    if (F::File_Exists($sFile)) {
                        return $sFile;
                    }
                } elseif ($sName == 'actions/ActionContent/add.tpl') {
                    $sResult = Config::Get('path.smarty.template') . '/actions/ActionTopic/add.tpl';
                    $this->Hook_AddExecFunction('template_form_add_topic_topic_end', array($this, 'TemplateFormAddTopic'));
                } elseif ((strpos($sName, 'forms/view_field') === 0) || (strpos($sName, 'forms/form_field') === 0)) {
                    $sResult = Plugin::GetTemplateDir('PluginLs') . $sName;
                }
            }
        }
        return $sResult;
    }

    public function TemplateFormAddTopic() {
        return $this->Fetch(Plugin::GetTemplateDir('PluginLs') . 'inc.form_topic_add_end.tpl');
    }
}

// EOF