<?php

class ControllerModuleQuestionnare extends Controller {
    protected function index() {
        $this->load->model('module/questionnare');
        $this->data["questions"] = $this->model_module_questionnare->getQuestions($this->customer->getEmail());
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/questionnare.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/questionnare.tpl';
		} else {
			$this->template = 'default/template/module/questionnare.tpl';
		}

		$this->render();
    }
}
