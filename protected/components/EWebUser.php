<?php

class EWebUser extends CWebUser {

	protected $_model;

	

	public function getRole() {
		return $this->getState('__role');
	}

	

	public function getXp() {
		return $this->getState('__xp');
	}

	public function getYiiAdmin() {
		return $this->getState('__yiiAdmin');
	}

	public function getYiiTeacher() {
		return $this->getState('__yiiTeacher');
	}

	public function getYiiWali() {
		return $this->getState('__yiiWali');
	}

	public function getYiiKepsek() {
		return $this->getState('__yiiKepsek');
	}

	public function getYiiStudent() {
		return $this->getState('__yiiStudent');
	}

	public function getYiiCNotif() {
		return $this->getState('__yiiCNotif');
	}

	public function getDisplayName() {
		return $this->getState('__dname');
	}

	
	


}