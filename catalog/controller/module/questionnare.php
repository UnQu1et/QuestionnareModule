<?php

class ControllerModuleQuestionnare extends Controller {
    public function index() {
        if ($this->request->cookie["QuestionnareHoldOwer"] === null) {
            $this->load->model('module/questionnare');
            $this->data["questions"] = $this->model_module_questionnare->getQuestions($this->customer->getEmail());
            if (!empty($this->data["questions"])) {
                $this->data['action'] = $this->url->link('module/questionnare/edit');
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/questionnare.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/questionnare.tpl';
		} else {
			$this->template = 'default/template/module/questionnare.tpl';
		}

		$this->render();
            }
        }
    }
    
    public function edit() {
        try {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->load->model('module/questionnare');
                $answers = $this->request->post;
                foreach ($answers as $id => $answer) {
                    $this->model_module_questionnare->saveAnswer($id, $this->customer->getEmail(), $answer);
                }
                $this->response->setOutput("Спасибо за участие! Мы обязательно рассмотрим ответы от каждого пользователя!");
            }
        } catch (Exception $ex) {
            $this->response->setOutput("Произошла ошибка при сохранении результатов. Приносим свои извинения.");
        }
    }
    
    protected function validate() {
		if (!$this->customer->isLogged()) {
			$this->error['warning'] = "Вы не авторизованы";
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
