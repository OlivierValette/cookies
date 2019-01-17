<?php

use \PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Helloworld extends Module implements WidgetInterface
{
    
    public function __construct()
    {
        $this->name = 'Helloworld';
        $this->version = '1.0.0';
        $this->author = 'Olivier Valette';
        $this->bootstrap = true;
        parent::__construct();
        
        $this->displayName = $this->l('Hello world');
        $this->description = $this->l('This is an example module');
    }
    
    public function install()
    {
        // select global install if multi-shop environment
        if ((bool)Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        
        // install module
        // connect to displayLeftColumn and displayHeader hooks
        // add HELLO_WORLD_NAME with value 'World' to the ps_configuration prestashop table
        if (!(bool)parent::install() ||
            !(bool)$this->registerHook('displayLeftColumn') ||
            !(bool)$this->registerHook('displayHeader') ||
            !(bool)Configuration::updateValue('HELLO_WORLD_NAME', 'World')
            ) {
            return false;
        }
        return true;
    }
    
    public function uninstall()
    {
        if (!(bool)parent::uninstall() ||
            !(bool)Configuration::deleteByName('HELLO_WORLD_NAME')
        ) {
            return false;
        }
        return true;
    }
    
    public function hookDisplayLeftColumn($params)
    {
        $this->context->smarty->assign([
            'name' => Configuration::get('HELLO_WORLD_NAME'),
        ]);
        return $this->fetch('module:helloworld/views/templates/hook/helloworld.tpl');
        
    }
    
    public function hookDisplayHeader($params)
    {
        $this->context->controller->registerStylesheet(
            'modules-helloworld',
            'modules/'.$this->name.'/views/asset/css/helloworld.css',
            ['media' => 'all', 'priority' => 150, ]
        );
        
/*        $this->context->controller->registerJavascript(
            'modules-helloworld',
            'module/'.$this->name.'/views/asset/js/helloworld.js',
            ['position' => 'bottom', 'priority' => 150]
        ); */
        
    }
    
    // better than hookDisplayLeftColumn() with a specific hook
    // these two methods allow to hook everywhere, with widget in back-office Display/Position
    
    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch('module:helloworld/views/templates/hook/helloworld.tpl');
    }
    
    public function getWidgetVariables($hookName, array $configuration)
    {
        return [
            'name' => Configuration::get('HELLO_WORLD_NAME'),
        ];
    }
}
