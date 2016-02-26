<?php
class ModelModuleQuestionnare extends Model {
    public function getQuestions($user) {
        $query = $this->db->query("select qq.id, qq.question, qq.type
                          from questionnare_question as qq
                          inner join questionnare as q on qq.questionnareId = q.id
                          where q.isActive = 1 and q.id not in(select q.id
                                                               from questionnare_results as qr
                                                               inner join questionnare_question as qq on qr.questionId = qq.id
                                                               inner join questionnare as q on qq.questionnareId = q.id
                                                               where qr.user = '".$user."')");
        return $query->rows;
    }
    public function saveAnswer($user, $questionId, $answer) {
        $this->db->query("insert into questionnare_result(user, questionId, answer)
                          values('".$user."','".$questionId."','".$answer."')");
    }
}
