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
        $this->controllers = [
            'default',
        ];
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
    
    // method for module configuration in back-office
    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submit'.$this->name)) {
            $hello_world_name = Tools::getValue('HELLO_WORLD_NAME');
            if (!$hello_world_name ||
                empty($hello_world_name) ||
                !Validate::isGenericName($hello_world_name)) {
                $output .= $this->displayError($this->l('Invalid configuration value'));
            } else {
                Configuration::updateValue('HELLO_WORLD_NAME', $hello_world_name);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output.$this->displayForm();
    }
    
    public function displayForm()
    {
        // get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    
        // init fields form array
        $field_form = [];
        $field_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Hello settings'),
            ],
            'input' => [
                [
                  'type' => 'text',
                  'label' => $this->l('Configuration value'),
                  'name' => 'HELLO_WORLD_NAME',
                  'size' => 20,
                  'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Your option'),
                    'name' => 'HELLO_SWITCH',
                    'values' => [
                        [
                            'id' => 'type_switch_on',
                            'value' => 1,
                        ],
                        [
                            'id' => 'type_switch_off',
                            'value' => 0,
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
        
        $helper = new HelperForm();
        
        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        
        // language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        
        // title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;
        
        // load current value
        $helper->fields_value['HELLO_WORLD_NAME'] = Configuration::get('HELLO_WORLD_NAME');
        $helper->fields_value['HELLO_SWITCH'] = 0;
        
        return $helper->generateForm($field_form);
    }
}
