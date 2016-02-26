<?php
class ModelModuleQuestionnare extends Model {
    public function edit($data) {
        $questionnareId = $data['questionnare']['id'];
        if (!empty($questionnareId)) {
            $this->db->query("update questionnare set name=\"".$data['questionnare']['name']."\", isActive=".(isset($data['questionnare']['isActive']) ? 1 : 0)." where id=".$questionnareId."");
            $this->saveQuestions($data['question'], $questionnareId);
        }
        else {
            $this->db->query("insert into questionnare(name, isActive) values(\"".$data['questionnare']['name']."\",".(isset($data['questionnare']['isActive']) ? 1 : 0).")");
            $questionnareId = $this->db->getLastId();
            $this->saveQuestions($data['question'], $questionnareId);
        }
    }
    
    private function saveQuestions($questions,$questionnareId) {
        foreach ($questions as $question) {
            if (!empty($question['id'])) {
                $this->db->query("update questionnare_question set question=\"".$question['question']."\", type=".$question['type']." where id=".$question['id']);
            }
            else {
                $this->db->query("insert into questionnare_question(question, type, questionnareId) values(\"".$question['question']."\",".$question['type'].",".$questionnareId.")");
            }
        }
    }
    
    public function get($id) {
        $query = $this->db->query("select * from questionnare where id=".$id);
        return $query->rows;
    }

    public function getList() {
        $query = $this->db->query("select * from questionnare");
        return $query->rows;
    }
    
    public function getQuestions($questionnareId) {
        $query = $this->db->query("select * from questionnare_question where questionnareId=".$questionnareId);
        return $query->rows;
    }
    
    public function getAnswers($questionnareId) {
        $query = $this->db->query("SELECT qr.user as user, qq.question as question, qr.answer as answer
                                   FROM questionnare_results AS qr
                                   LEFT JOIN questionnare_question AS qq ON qq.id = qr.questionId
                                   LEFT JOIN questionnare AS q ON q.id = qq.questionnareId
                                   WHERE qq.questionnareId=".$questionnareId);
        return $query->rows;
    }

    public function install() {
        $this->db->query("create table if not exists questionnare "
            . "("
            . "id int not null auto_increment primary key,"
            . "name char(100),"
            . "isActive bool,"
            . "index (name))");
        $this->db->query("create table if not exists questionnare_question "
            . "("
            . "id int not null auto_increment primary key,"
            . "questionnareId int not null,"
            . "question char(255) not null,"
            . "type int not null,"
            . "constraint fk_questionnareId foreign key (questionnareId) references questionnare(id))");
        $this->db->query("create table if not exists questionnare_results "
            . "("
            . "id int not null auto_increment primary key,"
            . "user char(100) not null,"
            . "questionId int not null,"
            . "answer char(255),"
            . "index (user),"
            . "index (questionId),"
            . "constraint fk_questionId foreign key (questionId) references questionnare_question(id))");
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('questionnare', array('questionnare_module' => array(array('layout_id' => 6, 'position' => 'column_left', 'status' => 1))));
        return true;   
    }
    
    public function uninstall() {
        $this->db->query("drop table questionnare_results");
        $this->db->query("drop table questionnare_question");
        $this->db->query("drop table questionnare");
    }
}

