<?php
class ControllerModuleQuestionnare extends Controller {
    private $error = array(); 

	public function index() {
            $this->setMainData();
            $this->data['questionnares'] = $this->model_module_questionnare->getList();

            $this->template = 'module/questionnare_list.tpl';
            $this->response->setOutput($this->render());
	}
        
        public function edit() {
            $this->setMainData();
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		$this->model_module_questionnare->edit($this->request->post);		
		$this->session->data['success'] = $this->language->get('text_success');
		$this->redirect($this->url->link('module/questionnare', 'token=' . $this->session->data['token'], 'SSL'));
            }
            
            $this->data['button_remove'] = $this->language->get('button_remove');
            $this->data['button_add_question'] = $this->language->get('button_add_question');
            
            $this->data['action'] = $this->url->link('module/questionnare/edit', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['cancel'] = $this->url->link('module/questionnare', 'token=' . $this->session->data['token'], 'SSL');
            if (isset($this->request->get['id'])) {
                $this->data['questionnare'] = $this->model_module_questionnare->get($this->request->get['id']);
                $this->data['questions'] = $this->model_module_questionnare->getQuestions($this->request->get['id']);
                $this->data['answers'] = $this->model_module_questionnare->getAnswers($this->request->get['id']);
            }
            
            $this->data['breadcrumbs'][] = array(
		'text'      => isset($this->request->get['id']) ? $this->request->get['id'] : 'Новый опрос',
		'href'      => '',
		'separator' => ' :: '
            );
            
            $this->template = 'module/questionnare.tpl';
            $this->response->setOutput($this->render());
        }
        
        private function setMainData(){
            $this->language->load('module/questionnare');
            $this->document->setTitle($this->language->get('heading_title'));
            $this->load->model('module/questionnare');
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['entry_id'] = $this->language->get('entry_id');
            $this->data['entry_name'] = $this->language->get('entry_name');
            $this->data['entry_status'] = $this->language->get('entry_status');
            $this->data['button_save'] = $this->language->get('button_save');
            $this->data['button_add_questionnare'] = $this->language->get('button_add_questionnare');
            $this->data['button_cancel'] = $this->language->get('button_cancel');
            $this->data['button_edit'] = $this->language->get('button_edit');
            $this->data['button_remove'] = $this->language->get('button_remove');

            if (isset($this->error['warning'])) {
		$this->data['error_warning'] = $this->error['warning'];
            } else {
		$this->data['error_warning'] = '';
            }

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_module'),
		'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		'separator' => ' :: '
            );

            $this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('module/questionnare', 'token=' . $this->session->data['token'], 'SSL'),
		'separator' => ' :: '
            );
            
            $this->children = array(
			'common/header',
			'common/footer'
            );
            
        }

        protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/questionnare')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
        
        public function install() {
                $this->load->model('module/questionnare');
                $this->model_module_questionnare->install();
        }
        public function uninstall() {
                $this->load->model('module/questionnare');
                $this->model_module_questionnare->uninstall();
        }
}

