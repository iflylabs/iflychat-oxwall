<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */


require_once(OW_DIR_PLUGIN.'iflychat'.DS.'helper.php');
class IFLYCHAT_CTRL_Admin extends ADMIN_CTRL_Abstract {



    public function index()
    {

        $obj = new iflychatHelper;
        $language = OW::getLanguage();
        $this->setPageHeading($language->text("iflychat", "iflychat_setting_page"));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('iflychat')->getStaticJsUrl() . 'iflychat-admin.js');

        $configs = OW::getConfig()->getValues('iflychat');
        $this->assign('configs', $configs);
        $form = new IFLYCHAT_ConfigSaveForm($configs);
        $this->addForm($form);


        if( OW::getRequest()->isPost() && $form->isValid($_POST) )
        { if ( $form->process($_POST) ){

            $data = $form->getValues();

            $variable_get = $obj->params('iflychat_ext_d_i');


            define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
            define('IFLYCHAT_EXTERNAL_PORT', '80');
            define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
            define('IFLYCHAT_EXTERNAL_A_PORT', '443');

            $pdata = array(
                'api_key' => $data['iflychat_external_api_key'],
                'enable_chatroom' => $data['iflychat_enable_chatroom'],
                'theme' => ($data['iflychat_theme'] == 1)?'light':'dark',
                'notify_sound' => $data['iflychat_notification_sound'],
                'smileys' => $data['iflychat_enable_smileys'],
                'log_chat' => $data['iflychat_log_messages'],
                'chat_topbar_color' => $data['iflychat_chat_topbar_color'],
                'chat_topbar_text_color' => $data['iflychat_chat_topbar_text_color'],
                'font_color' => $data['iflychat_font_color'],
                'chat_list_header' => $data['iflychat_chat_list_header'],
                'public_chatroom_header' => $data['iflychat_public_chatroom_header'],
                'rel' => $data['iflychat_rel'],
                'version' => 'Oxwall-1.6.0',
                'show_admin_list' => $data['iflychat_show_admin_list'],
                'clear' => $data['iflychat_allow_single_message_delete'],
                'delmessage' => $data['iflychat_allow_clear_room_history'],
                'ufc' => $data['iflychat_allow_user_font_color'],
                'guest_prefix' => ($data['iflychat_anon_prefix'] . " "),
                'enable_guest_change_name' => $data['iflychat_anon_change_name'],
                'use_stop_word_list' => $data['iflychat_use_stop_word_list'],
                'stop_word_list' => $data['iflychat_stop_word_list'],
            );



            $options = array(
                'method' => 'POST',
                'data' => json_encode($pdata),
                'timeout' => 15,
                'headers' => array('Content-Type' => 'application/json'),
            );


            $uri = IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/p/';


            $obj->iflychat_extended_http_request($uri, $options);

            $form->process(json_encode($data));

            OW::getFeedback()->info($language->text('iflychat', 'settings_updated'));
        }else {

            OW::getFeedback()->error(OW::getLanguage()->text('iflychat', 'admin_index_form_save_error_message'));
        }
            $this->redirect();
        }


$default = array(

    'iflychat_external_api_key' => '',
    'iflychat_show_admin_list' => '1',
    'iflychat_theme' => '1',
    'iflychat_notification_sound' => '1',
    'iflychat_user_picture' => '1',
    'iflychat_enable_smileys' => '1',
    'iflychat_log_messages' => '1',
    'iflychat_anon_prefix' => 'Guest',
    'iflychat_anon_use_name' => '1',
    'iflychat_anon_change_name' => '1',
    'iflychat_load_chat_async' => '1',
    'iflychat_ext_d_i' => '',
    'iflychat_support_chat_init_label' => 'Chat with us',
    'iflychat_support_chat_box_header' => 'Support',
    'iflychat_support_chat_box_company_name' => 'Support Team',
    'iflychat_support_chat_box_company_tagline' => 'Ask us anything...',
    'iflychat_support_chat_auto_greet_enable' => '1',
    'iflychat_support_chat_auto_greet_message' => 'Hi there! Welcome to our website. Let us know if you have any query!',
    'iflychat_support_chat_auto_greet_time' => '1',
    'iflychat_support_chat_init_label_off' => 'Leave Message',
    'iflychat_support_chat_offline_message_desc' => 'Hello there. We are currently offline. Please leave us a message. Thanks.',
    'iflychat_support_chat_offline_message_label' => 'Message',
    'iflychat_support_chat_offline_message_contact' => 'Contact Details',
    'iflychat_support_chat_offline_message_send_button' => 'Send Message',
    'iflychat_support_chat_offline_message_email' => '',
    'iflychat_enable_chatroom' => '1',
    'iflychat_stop_word_list' => 'asshole,assholes,bastard,beastial,beastiality,beastility,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bullshit,clit,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cum,cummer,cumming,cums,cumshot,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,damn,dildo,dildos,dick,dink,dinks,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejaculation,fag,fagging,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,felatio,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,fuck,fucked,fucker,fuckers,fuckin,fucking,fuckings,fuckme,fucks,fuk,fuks,gangbang,gangbanged,gangbangs,gaysex,goddamn,hardcoresex,horniest,horny,hotsex,jism,jiz,jizm,kock,kondum,kondums,kum,kumer,kummer,kumming,kums,kunilingus,lust,lusting,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfucks,niger,nigger,niggers,orgasim,orgasims,orgasm,orgasms,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pis,piss,pisser,pissed,pisser,pissers,pises,pisses,pisin,pissin,pising,pissing,pisof,pissoff,porn,porno,pornography,pornos,prick,pricks,pussies,pusies,pussy,pusy,pussys,pusys,slut,sluts,smut,spunk',
    'iflychat_use_stop_word_list' => '1',
    'iflychat_stop_links' => '1',
    'iflychat_allow_anon_links' => '1',
    'iflychat_allow_render_images' => '1',
    'iflychat_allow_single_message_delete' => '1',
    'iflychat_allow_clear_room_history' => '1',
    'iflychat_allow_user_font_color' => '1',
    'iflychat_path_visibility' => '1',
    'iflychat_path_pages' => '',
    'iflychat_chat_topbar_color' => '#222222',
    'iflychat_chat_topbar_text_color' => '#FFFFFF',
    'iflychat_font_color' => '#FFFFFF',
    'iflychat_public_chatroom_header' => 'Public Chatroom',
    'iflychat_chat_list_header' => 'Chat',
    'iflychat_minimize_chat_user_list' => '2',
    'iflychat_enable_search_bar' => '1',


);


        $settingsJson = OW::getConfig()->getValue('iflychat', 'setting_vars');
           // print_r($settingsJson);
        $settingsArray = (array)json_decode($settingsJson);
        foreach ($settingsArray as $key => $value) {

            if(empty($value)) {

                $settingsArray[$key] = $default[$key];

                $form->setValues($settingsArray);
                $this->addForm($form);
            }


        }




    }




}
class IFLYCHAT_ConfigSaveForm extends Form {

    public function __construct( $configs ) {

        parent::__construct('IFLYCHAT_ConfigSaveForm');

        $language = OW::getLanguage();


//General settings

        $textField = new TextField("iflychat_external_api_key");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_API_KEY"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_API_KEY_DESC"));
        $textField->setRequired();
        $this->addElement($textField);

        $selectField = new Selectbox("iflychat_show_admin_list");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SHOW_ADMIN_LIST"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SHOW_ADMIN_LIST_DESC"));
        $selectField->setValue('2');
        $selectField->setOptions(array(
            "2" => "Community Chat",
            "1" => "Support Chat"
        ));
        $this->addElement($selectField);


        $selectField = new Selectbox("iflychat_theme");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_THEME"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_THEME_DESC"));
        $selectField->setInvitation('Select any');
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Light",
            "2" => "Dark"
        ));
        $this->addElement($selectField);


        $selectField = new Selectbox("iflychat_notification_sound");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_NOTIFICATION_SOUND"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_NOTIFICATION_SOUND_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);



        $selectField = new Selectbox("iflychat_user_picture");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_USER_PICTURE"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_USER_PICTURE_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);



        $selectField = new Selectbox("iflychat_enable_smileys");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SMILEYS"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SMILEYS_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);



        $selectField = new Selectbox("iflychat_log_messages");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_LOG_MESSAGES"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_LOG_MESSAGES_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $textField = new TextField("iflychat_anon_prefix");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ANON_PREFIX"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ANON_PREFIX_DESC"));
        $textField->setRequired();
        $textField->setValue('Guest');
        $this->addElement($textField);

        $selectField = new Selectbox("iflychat_anon_use_name");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ANON_USE_NAME"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ANON_USE_NAME_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Name",
            "2" => "Number"
        ));
        $this->addElement($selectField);



        $selectField = new Selectbox("iflychat_anon_change_name");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ANON_CHANGE_NAME"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ANON_CHANGE_NAME_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);



        $selectField = new Selectbox("iflychat_load_chat_async");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_LOAD_CHAT_ASYNC"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_LOAD_CHAT_ASYNC_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $hiddenField = new HiddenField("iflychat_ext_d_i");
        $hiddenField->setValue("");
        $this->addElement($hiddenField);

//Support settings

        $textField = new TextField("iflychat_support_chat_init_label");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL_DESC"));
        $textField->setValue('Chat with us');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_box_header");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_HEADER"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_HEADER_DESC"));
        $textField->setValue('Support');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_box_company_name");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_NAME"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_NAME_DESC"));
        $textField->setValue('Support Team');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_box_company_tagline");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_TAGLINE"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_TAGLINE_DESC"));
        $textField->setValue('Ask us anything...');
        $this->addElement($textField);


        $selectField = new Selectbox("iflychat_support_chat_auto_greet_enable");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_ENABLE"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $textareaField = new Textarea("iflychat_support_chat_auto_greet_message");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_MESSAGE"));
        $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_MESSAGE_DESC"));
        $textareaField->setValue('Hi there! Welcome to our website. Let us know if you have any query!');
        $this->addElement($textareaField);

        $selectField = new Selectbox("iflychat_support_chat_auto_greet_time");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_TIME"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_TIME_DESC"));
        $selectField->setValue('1');
        $seconds = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7=>7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20, 30 => 30, 40 => 40, 50 => 50, 60 => 60, 70 => 70, 80 => 80, 90 => 90, 100 => 100, 110 => 110, 120 => 120, 150 => 150, 180 => 180, 240 => 240, 300 => 300);
        $selectField->setOptions($seconds);
        $this->addElement($selectField);

        $textField = new TextField("iflychat_support_chat_init_label_off");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL_OFF"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL_OFF_DESC"));
        $textField->setValue('Leave Message');
        $this->addElement($textField);

        $textareaField = new Textarea("iflychat_support_chat_offline_message_desc");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_DESC"));
        $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_DESC_DESC"));
        $textareaField->setValue('Hello there. We are currently offline. Please leave us a message. Thanks.');
        $this->addElement($textareaField);

        $textField = new TextField("iflychat_support_chat_offline_message_label");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_LABEL"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_LABEL_DESC"));
        $textField->setValue('Message');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_offline_message_contact");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_CONTACT"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_CONTACT_DESC"));
        $textField->setValue('Contact Details');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_offline_message_send_button");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_SEND_BUTTON"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_SEND_BUTTON_DESC"));
        $textField->setValue('Send Message');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_offline_message_email");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_EMAIL"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_EMAIL_DESC"));
        $this->addElement($textField);

//Chat moderation


        $selectField = new Selectbox("iflychat_enable_chatroom");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_CHATROOM"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $textareaField = new Textarea("iflychat_stop_word_list");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_STOP_WORD_LIST"));
        $textareaField->setValue('asshole,assholes,bastard,beastial,beastiality,beastility,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bullshit,clit,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cum,cummer,cumming,cums,cumshot,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,damn,dildo,dildos,dick,dink,dinks,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejaculation,fag,fagging,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,felatio,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,fuck,fucked,fucker,fuckers,fuckin,fucking,fuckings,fuckme,fucks,fuk,fuks,gangbang,gangbanged,gangbangs,gaysex,goddamn,hardcoresex,horniest,horny,hotsex,jism,jiz,jizm,kock,kondum,kondums,kum,kumer,kummer,kumming,kums,kunilingus,lust,lusting,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfucks,niger,nigger,niggers,orgasim,orgasims,orgasm,orgasms,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pis,piss,pisser,pissed,pisser,pissers,pises,pisses,pisin,pissin,pising,pissing,pisof,pissoff,porn,porno,pornography,pornos,prick,pricks,pussies,pusies,pussy,pusy,pussys,pusys,slut,sluts,smut,spunk');
        $this->addElement($textareaField);

        $selectField = new Selectbox("iflychat_use_stop_word_list");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_USE_STOP_WORD_LIST"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_USE_STOP_WORD_LIST_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Don't filter",
            "2" => "Filter in public chatroom",
            "3" => "Filter in private chats",
            "4" => "Filter in all rooms"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_stop_links");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_STOP_LINKS"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_STOP_LINKS_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Don't filter",
            "2" => "Filter in public chatroom",
            "3" => "Filter in private chats",
            "4" => "Filter in all rooms"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_anon_links");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_ANON_LINKS"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_ANON_LINKS_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes, apply only to anonymous users",
            "2" => "No, apply to all users"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_render_images");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_RENDER_IMAGES"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_RENDER_IMAGES_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_single_message_delete");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_SINGLE_MESSAGE_DELETE"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_SINGLE_MESSAGE_DELETE_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Allow all users",
            "2" => "Allow only moderators",
            "3" => "Disable"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_clear_room_history");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_CLEAR_ROOM_HISTORY"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_CLEAR_ROOM_HISTORY_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Allow all users",
            "2" => "Allow only moderators",
            "3" => "Disable"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_user_font_color");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_USER_FONT_COLOR"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_USER_FONT_COLOR_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

//Iflychat visibility

        $selectField = new Selectbox("iflychat_path_visibility");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_PATH_VISIBILITY"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "All pages except those listed ",
            "2" => " Only the listed pages",
            "3" => "Pages on which this PHP code retuts only)"
        ));
        $this->addElement($selectField);

        $textareaField = new Textarea("iflychat_path_pages");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_PATH_PAGES"));
        $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_PATH_PAGES_DESC"));
        $this->addElement($textareaField);


//Theme customisation

        $textField = new TextField("iflychat_chat_topbar_color");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_COLOR"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_COLOR_DESC"));
        $textField->setValue('#222222');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_chat_topbar_text_color");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_TEXT_COLOR"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_TEXT_COLOR_DESC"));
        $textField->setValue('#FFFFFF');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_font_color");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_FONT_COLOR"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_FONT_COLOR_DESC"));
        $textField->setValue('#222222');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_public_chatroom_header");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_PUBLIC_CHATROOM_HEADER"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_PUBLIC_CHATROOM_HEADER_DESC"));
        $textField->setValue('Public Chatroom');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_chat_list_header");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_CHAT_LIST_CHATROOM_HEADER"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_CHAT_LIST_CHATROOM_HEADER_DESC"));
        $textField->setValue('Chat');
        $textField->setRequired();
        $this->addElement($textField);

//Iflychat user online list control

        $selectField = new Selectbox("iflychat_minimize_chat_user_list");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_MINIMIZE_CHAT_USER_LIST"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_MINIMIZE_CHAT_USER_LIST_DESC"));
        $selectField->setValue('2');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_enable_search_bar");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SEARCH_BAR"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SEARCH_BAR_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

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

    public function process( $data )
    {
        $config = OW::getConfig();
        $config->saveConfig('iflychat', 'setting_vars', $data);

        return true;
    }

}