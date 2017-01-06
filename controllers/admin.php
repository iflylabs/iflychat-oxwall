<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */


require_once(OW_DIR_PLUGIN . 'iflychat' . DS . 'helper.php');
//define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api.iflychat.com');
//define('IFLYCHAT_EXTERNAL_A_PORT', '443');
class IFLYCHAT_CTRL_Admin extends ADMIN_CTRL_Abstract {
  public function __construct() {
    parent::__construct();
    $language = OW::getLanguage();
    $this->setPageHeading($language->text("iflychat", "iflychat_setting_page"));

    $general = new BASE_MenuItem();
    $general->setLabel('Plugin setting');
    $general->setUrl(OW::getRouter()->urlForRoute('iflychat_admin'));
    $general->setKey('iflychat_settings');
    $general->setIconClass('ow_ic_gear_wheel');
    $general->setOrder(0);

    $view = new BASE_MenuItem();
    $view->setLabel('App setting');
    $view->setUrl(OW::getRouter()->urlForRoute('iflychat_admin_customization'));
    $view->setKey('iflychat_customization');
    $view->setIconClass('ow_ic_files');
    $view->setOrder(1);

    $menu = new BASE_CMP_ContentMenu(array($general, $view));
    $this->addComponent('menu', $menu);
  }

  public function index() {

    $obj = new iflychatHelper;
    $language = OW::getLanguage();
    OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('iflychat')->getStaticJsUrl() . 'iflychat-admin.js');
    $configs = OW::getConfig()->getValues('iflychat');
    $configSaveForm = new ConfigSaveForm();
    $embedChatDescription = $language->text('iflychat', 'Embed_chat_Description');
    $this->assign('embedChatDescription', $embedChatDescription);
    $this->addForm($configSaveForm);
    if (OW::getRequest()->isPost()&& $configSaveForm->isValid($_POST)) {
//    if (OW::getRequest()->isPost()) {
      $data = $configSaveForm->getValues();

      //generate request on save button
      $response = $obj->generateToken($data['iflychat_external_api_key']);
      $response = json_decode($response);
      if (isset($response->code) && $response->code === 200) {
        $configSaveForm->process();
        OW::getFeedback()->info($language->text('iflychat', 'settings_updated'));
        $this->redirect(OW::getRouter()->urlForRoute('iflychat_admin'));
      } else {
        OW::getFeedback()->error('Invalid Api key');
      }
    }
    $configSaveForm->getElement('iflychat_external_api_key')->setValue($configs['iflychat_external_api_key']);
    $configSaveForm->getElement('iflychat_app_id')->setValue($configs['iflychat_app_id']);
    $configSaveForm->getElement('iflychat_show_popup_chat')->setValue($configs['iflychat_show_popup_chat']);
    $configSaveForm->getElement('iflychat_enable_friends')->setValue($configs['iflychat_enable_friends']);
    $configSaveForm->getElement('iflychat_path_pages')->setValue($configs['iflychat_path_pages']);
    $configSaveForm->getElement('iflychat_moderators')->setValue($configs['iflychat_moderators']);
    $configSaveForm->getElement('iflychat_administers')->setValue($configs['iflychat_administers']);
  }

  public function customization() {
    $form = new IFLYCHAT_CustomizationForm();
    $this->addForm($form);
  }
  public function dashboard(){
    $obj = new iflychatHelper;
    $iflychat_host = IFLYCHAT_EXTERNAL_A_HOST;
    $host = explode("/", $iflychat_host);
    $host_name = $host[2];
    $token = '';
    $response = $obj->generateToken($obj->params('iflychat_external_api_key'));
    $response = json_decode($response);
    if ($response->code === 200) {
                $_SESSION['token'] = $response->key;
      $token = $response->key;
    }
    $dashboardUrl = "//" . IFLYCHAT_EXTERNAL_CDN_HOST . "/apps/dashboard/#/app-settings?sessid=" . $token . "&hostName=" . $host_name . "&hostPort=" . IFLYCHAT_EXTERNAL_A_PORT;
    header('Location: '.$dashboardUrl);
  }
}

class ConfigSaveForm extends Form {

  public function __construct() {

    parent::__construct('configSaveForm');
    $language = OW::getLanguage();


//General settings
    $textField = new TextField("iflychat_app_id");
    $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_APP_ID"));
    $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_APP_ID_DESC"));
    $this->addElement($textField);

    $textField = new TextField("iflychat_external_api_key");
    $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_API_KEY"));
    $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_API_KEY_DESC"));
    $textField->setRequired();
    $this->addElement($textField);

    $selectField = new Selectbox("iflychat_show_popup_chat");
    $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SHOW_POPUP_CHAT"));
    $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SHOW_POPUP_CHAT_DESC"));
    $selectField->setValue('1');
    $selectField->setOptions(array(
      "1" => "Everywhere",
      "2" => "Frontend Only",
      "3" => "Everywhere except those listed page",
      "4" => "Only the list page",
      "5" => "Disable"
    ));
    $this->addElement($selectField);

    $textareaField = new Textarea("iflychat_path_pages");
    $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_PATH_PAGES"));
    $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_PATH_PAGES_DESC"));
    $this->addElement($textareaField);


    $textareaField = new Textarea("iflychat_moderators");
    $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_MODERATORS"));
    $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_MODERATORS_DESC"));
    $this->addElement($textareaField);

    $textareaField = new Textarea("iflychat_administers");
    $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ADMINISTERS"));
    $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ADMINISTERS_DESC"));
    $this->addElement($textareaField);

    $selectField = new Selectbox("iflychat_enable_friends");
    $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_FRIENDS"));
    $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_FRIENDS_DESC"));
    $selectField->setValue('1');
    $selectField->setOptions(array(
      "1" => "No",
      "2" => "Yes"
    ));
    $this->addElement($selectField);

    $submit = new Submit('submit');
    $submit->setValue('Save');
    $this->addElement($submit);
  }

  public function process() {
    $values = $this->getValues();
    $config = OW::getConfig();
    $config->saveConfig('iflychat', 'iflychat_external_api_key', $values['iflychat_external_api_key']);
    $config->saveConfig('iflychat', 'iflychat_app_id', $values['iflychat_app_id']);
    $config->saveConfig('iflychat', 'iflychat_show_popup_chat', $values['iflychat_show_popup_chat']);
    $config->saveConfig('iflychat', 'iflychat_enable_friends', $values['iflychat_enable_friends']);
    $config->saveConfig('iflychat', 'iflychat_path_pages', $values['iflychat_path_pages']);
    $config->saveConfig('iflychat', 'iflychat_moderators', $values['iflychat_moderators']);
    $config->saveConfig('iflychat', 'iflychat_administers', $values['iflychat_administers']);
    return array('result' => true);
  }
}

class IFLYCHAT_CustomizationForm extends Form {

  public function __construct() {
    parent::__construct('IFLYCHAT_CustomizationForm');
  }

  public function process() {
    return TRUE;
  }
}