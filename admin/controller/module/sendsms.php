<?php
namespace Opencart\Admin\Controller\Extension\Sendsms\Module;

class Sendsms extends \Opencart\System\Engine\Controller {
    public function index() :void
    {
        $this->load->language('extension/sendsms/module/sendsms');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('design/layout');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_label'] = $this->language->get('entry_label');
        $data['entry_simulation'] = $this->language->get('entry_simulation');
        $data['entry_simulation_phone'] = $this->language->get('entry_simulation_phone');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_yes'] = $this->language->get('entry_yes');
        $data['entry_no'] = $this->language->get('entry_no');
        $data['entry_characters_left'] = $this->language->get('entry_characters_left');
        $data['entry_available_vars'] = $this->language->get('entry_available_vars');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        # get list of order statuses
        $statuses = new \Opencart\Admin\Model\Localisation\OrderStatus($this->registry);
        $statuses = $statuses->getOrderStatuses();
        $data['statuses'] = $statuses;

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/sendsms/module/sendsms', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['save'] = $this->url->link('extension/sendsms/module/sendsms.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=module');

        # form
        if (isset($this->request->post['module_sendsms_status'])) {
            $data['module_sendsms_status'] = $this->request->post['module_sendsms_status'];
        } else {
            $data['module_sendsms_status'] = $this->config->get('module_sendsms_status');
        }
        if (isset($this->request->post['module_sendsms_username'])) {
            $data['sendsms_username'] = $this->request->post['module_sendsms_username'];
        } elseif ($this->config->get('module_sendsms_username')) {
            $data['sendsms_username'] = $this->config->get('module_sendsms_username');
        } else {
            $data['sendsms_username'] = '';
        }
        if (isset($this->request->post['module_sendsms_password'])) {
            $data['sendsms_password'] = $this->request->post['module_sendsms_password'];
        } elseif ($this->config->get('module_sendsms_password')) {
            $data['sendsms_password'] = $this->config->get('module_sendsms_password');
        } else {
            $data['sendsms_password'] = '';
        }
        if (isset($this->request->post['module_sendsms_label'])) {
            $data['sendsms_label'] = $this->request->post['module_sendsms_label'];
        } elseif ($this->config->get('module_sendsms_label')) {
            $data['sendsms_label'] = $this->config->get('module_sendsms_label');
        } else {
            $data['sendsms_label'] = '';
        }
        if (isset($this->request->post['module_sendsms_simulation'])) {
            $data['sendsms_simulation'] = $this->request->post['module_sendsms_simulation'];
        } elseif ($this->config->get('module_sendsms_simulation')) {
            $data['sendsms_simulation'] = $this->config->get('module_sendsms_simulation');
        } else {
            $data['sendsms_simulation'] = '';
        }
        if (isset($this->request->post['module_sendsms_simulation_phone'])) {
            $data['sendsms_simulation_phone'] = $this->request->post['module_sendsms_simulation_phone'];
        } elseif ($this->config->get('module_sendsms_simulation_phone')) {
            $data['sendsms_simulation_phone'] = $this->config->get('module_sendsms_simulation_phone');
        } else {
            $data['sendsms_simulation_phone'] = '';
        }
        foreach ($statuses as $status) {
            if (isset($this->request->post['module_sendsms_message_'.$status['order_status_id']])) {
                $data['sendsms_message_'.$status['order_status_id']] = $this->request->post['module_sendsms_message_'.$status['order_status_id']];
            } elseif ($this->config->get('module_sendsms_message_'.$status['order_status_id'])) {
                $data['sendsms_message_'.$status['order_status_id']] = $this->config->get('module_sendsms_message_'.$status['order_status_id']);
            } else {
                $data['sendsms_message_'.$status['order_status_id']] = '';
            }
            
            if (isset($this->request->post['module_sendsms_short_url_'.$status['order_status_id']])) {
                $data['sendsms_short_url_'.$status['order_status_id']] = $this->request->post['module_sendsms_short_url_'.$status['order_status_id']];
            } elseif ($this->config->get('module_sendsms_short_url_'.$status['order_status_id'])) {
                $data['sendsms_short_url_'.$status['order_status_id']] = $this->config->get('module_sendsms_short_url_'.$status['order_status_id']);
            } else {
                $data['sendsms_short_url_'.$status['order_status_id']] = 0;
            }
        }

        # page links
        $data['history_link'] = $this->url->link('extension/sendsms/sendsms/history', 'user_token=' . $this->session->data['user_token'], true);
        $data['history_text'] = $this->language->get('text_history');
        $data['about_link'] = $this->url->link('extension/sendsms/sendsms/about', 'user_token=' . $this->session->data['user_token'], true);
        $data['about_text'] = $this->language->get('text_about');
        $data['campaign_link'] = $this->url->link('extension/sendsms/sendsms/campaign', 'user_token=' . $this->session->data['user_token'], true);
        $data['campaign_text'] = $this->language->get('text_campaign');
        $data['test_link'] = $this->url->link('extension/sendsms/sendsms/test', 'user_token=' . $this->session->data['user_token'], true);
        $data['test_text'] = $this->language->get('text_test');

        # common template
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/sendsms/module/sendsms', $data));
    }
    
    public function save(): void
    {
        $this->load->language('extension/sendsms/module/sendsms');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/sendsms/module/sendsms')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');
            
            $this->model_setting_setting->editSetting('module_sendsms', $this->request->post);

            $json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    public function install()
    {
        $this->load->model('extension/sendsms/module/sendsms/history');
        $this->model_extension_sendsms_module_sendsms_history->createSchema();
        $this->load->model('setting/event');
        $this->model_setting_event->addEvent([
            'code' => 'sendsms',
            'trigger' => 'catalog/model/checkout/order/addHistory/before',
            'action' => 'extension/sendsms/module/sendsms.statusChange',
            'description' => 'Status change event for SendSMS',
            'sort_order' => 1,
            'status' => true
        ]);

        # permissions
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/about');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/history');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/test');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/campaign');
    }

    public function uninstall()
    {
        $this->load->model('extension/sendsms/module/sendsms/history');
        $this->model_extension_sendsms_module_sendsms_history->deleteSchema();
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('sendsms');

        # permissions
        $this->load->model('user/user_group');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/about');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/history');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/test');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/sendsms/sendsms/campaign');
    }
}
