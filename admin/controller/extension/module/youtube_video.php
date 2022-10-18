<?php
class ControllerExtensionModuleYoutubeVideo extends Controller {
	private $error = array();
	
	public function install() {

		$query=$this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product LIKE 'youtube_video'");
		if(!$query->num_rows)
		$this->db->query("ALTER TABLE ".DB_PREFIX."product  ADD `youtube_video` VARCHAR(99) NOT NULL  AFTER `image`;");
	
	}

	public function index() {

		$this->install();

		$this->load->language('extension/module/youtube_video');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_youtube_video', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/youtube_video', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/youtube_video', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_youtube_video_status'])) {
			$data['module_youtube_video_status'] = $this->request->post['module_youtube_video_status'];
		} else {
			$data['module_youtube_video_status'] = $this->config->get('module_youtube_video_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/youtube_video', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/youtube_video')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}