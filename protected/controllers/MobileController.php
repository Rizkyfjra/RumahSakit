<?php

class MobileController extends Controller
{
	public $layout='NULL';
	
	
	public function actionIndex()
	{
		$this->render('test');
	}
	
	public function actionInfo()
	{
		$this->render('info');
	}
	
	public function actionAssignment()
	{
		$this->render('assignment');
	}
	
	public function actionLesson()
	{
		$this->render('lesson');
	}
	
	public function actionStartQuiz()
	{
		$this->render('start_quiz');
	}
	
	public function actionDetailChapter()
	{
		$this->renderPartial('detailChapter');
	}
	public function actiondetailAssignmentViaLesson()
	{
		$this->renderPartial('detailAssignmentViaLesson');
	}
	
	public function actiondetailAssignmentViaInfo()
	{
		$this->renderPartial('detailAssignmentViaInfo');
	}
	
	public function actionDetailQuiz()
	{
		$this->renderPartial('detailQuiz');
	}
	
	public function actionDetailLks()
	{
		$this->renderPartial('detailLks');
	}
	
	
	public function actionChapter()
	{
		$this->render('chapter');
	}
	
	public function actionNilai()
	{
		$this->render('nilai');
	}
}
?>