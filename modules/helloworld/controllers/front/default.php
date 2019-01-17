<?php


class HelloworldDefaultModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->success[] = $this->module->l('Sample success message');
        $this->context->smarty->assign([
           'name' => Configuration::get('HELLO_WORLD_NAME'),
        ]);
        $this->setTemplate('module:helloworld/views/templates/front/helloworlddefault.tpl');
        parent::initContent();
    }
}
