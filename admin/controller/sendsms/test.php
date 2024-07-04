<?php
namespace Opencart\Admin\Controller\Extension\Sendsms\Sendsms;

class Test extends \Opencart\System\Engine\Controller {

    public function index() :void
    {
        $this->load->language('extension/sendsms/module/sendsms');
        $this->document->setTitle($this->language->get('heading_test'));

        $this->load->model('design/layout');
        $data['heading_title'] = $this->language->get('heading_test');
        $data['user_token'] = $this->session->data['user_token'];

        # breadcrumbs
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
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_test'),
            'href' => $this->url->link('extension/sendsms/sendsms/history', 'user_token=' . $this->session->data['user_token'], true)
        );

        # page links
        $data['history_link'] = $this->url->link('extension/sendsms/sendsms/history', 'user_token=' . $this->session->data['user_token'], true);
        $data['history_text'] = $this->language->get('text_history');
        $data['about_link'] = $this->url->link('extension/sendsms/sendsms/about', 'user_token=' . $this->session->data['user_token'], true);
        $data['about_text'] = $this->language->get('text_about');
        $data['campaign_link'] = $this->url->link('extension/sendsms/sendsms/campaign', 'user_token=' . $this->session->data['user_token'], true);
        $data['campaign_text'] = $this->language->get('text_campaign');
        $data['test_link'] = $this->url->link('extension/sendsms/sendsms/test', 'user_token=' . $this->session->data['user_token'], true);
        $data['test_text'] = $this->language->get('text_test');

        # texts
        $data['button_save'] = $this->language->get('button_send');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/sendsms', 'user_token=' . $this->session->data['user_token'], true);
        $data['entry_phone'] = $this->language->get('test_entry_phone');
        $data['entry_message'] = $this->language->get('test_entry_message');
        $data['entry_characters_left'] = $this->language->get('entry_characters_left');

        # common template
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['save'] = $this->url->link('extension/sendsms/sendsms/test.save', 'user_token=' . $this->session->data['user_token']);

        $this->response->setOutput($this->load->view('extension/sendsms/sendsms/test', $data));
    }
    
    public function save(): void
    {
        $this->load->language('extension/sendsms/module/sendsms');

		$json = [];

		if (!$this->user->hasPermission('access', 'extension/sendsms/sendsms/test')) {
			$json['error'] = $this->language->get('error_permission');
		}
        
        if (empty($this->request->post['module_sendsms_phone'])) {
            if (isset($json['error'])) {
                $json['error'] .= '<br />'.$this->language->get('error_phone_required');
            } else {
                $json['error'] = $this->language->get('error_phone_required');
            }
        }

        if (empty($this->request->post['module_sendsms_message'])) {
            if (isset($json['error'])) {
                $json['error'] .= '<br />'.$this->language->get('error_message_required');
            } else {
                $json['error'] = $this->language->get('error_message_required');
            }
        }

		if (!$json) {
			# send the message
            $this->load->model('extension/sendsms/module/sendsms/send');
            $this->model_extension_sendsms_module_sendsms_send->send_sms($this->request->post['module_sendsms_phone'], $this->request->post['module_sendsms_message'], 'test');

            $json['success'] = $this->language->get('text_success_test_send');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
}
