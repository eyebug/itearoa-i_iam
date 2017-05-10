<?php

/**
 * Class FeedbackajaxController
 */
class FeedbackajaxController extends \BaseController {

    /**
     * @var FeedbackModel
     */
    private $feedbackModel;

    /**
     * @var Convertor_Feedback
     */
    private $feedbackConvertor;

    public function init() {
        parent::init();
        $this->feedbackModel = new FeedbackModel();
        $this->feedbackConvertor = new Convertor_Feedback();
    }

    public function getQuestionListAction() {
        $paramList['page'] = $this->getPost('page');
        $paramList['hotelid'] = $this->getHotelId();
        $paramList['id'] = intval($this->getPost('id'));
        $paramList['question'] = trim($this->getPost('question'));
        $paramList['type'] = trim($this->getPost('type'));
        $status = $this->getPost('status');
        $status !== 'all' && !is_null($status) ? $paramList['status'] = intval($status) : false;
        $result = $this->feedbackModel->getQuestionList($paramList);
        $result = $this->feedbackConvertor->questionListConvertor($result);
        $this->echoJson($result);
    }

    private function handlerQuestionSaveParams() {
        $paramList = array();
        $paramList['question'] = trim($this->getPost("question"));
        $paramList['type'] = intval($this->getPost('type'));
        $paramList['sort'] = intval($this->getPost('sort'));
        $paramList['hotelid'] = intval($this->getHotelId());
        $paramList['status'] = intval($this->getPost('status'));
        return $paramList;
    }

    public function createQuestionAction() {
        $paramList = $this->handlerQuestionSaveParams();
        $result = $this->feedbackModel->saveQuestionDataInfo($paramList);
        $this->echoJson($result);
    }

    public function updateQuestionAction() {
        $paramList = $this->handlerQuestionSaveParams();
        $paramList['id'] = intval($this->getPost("id"));
        $result = $this->feedbackModel->saveQuestionDataInfo($paramList);
        $this->echoJson($result);
    }

    public function updateOptionAction() {
        $paramList['id'] = intval($this->getPost("id"));
        $paramList['option'] = $this->getPost("option");
        $paramList['option'] = $paramList['option'] ? json_encode($paramList['option']) : '';
        $result = $this->feedbackModel->saveQuestionOptionInfo($paramList);
        $this->echoJson($result);
    }

    public function getResultListAction() {
        $paramList['page'] = $this->getPost('page');
        $paramList['hotelid'] = $this->getHotelId();
        $result = $this->feedbackModel->getResultList($paramList);
        $result = $this->feedbackConvertor->resultListConvertor($result);
        $this->echoJson($result);
    }
}