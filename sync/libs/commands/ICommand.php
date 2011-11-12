<?php
interface ICommand {
	public function pre();
	public function test();
	public function execute();
	public function post();
	public function rescue();
}
?>
