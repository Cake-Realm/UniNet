<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

header('Content-Type: application/json');

function error($message)
{
	$data["error"] = true;
	$data["message"] = $message;
	echo json_encode($data, JSON_PRETTY_PRINT);
	die();
}

if (!isset($_GET["type"]))
	error("Type was not provided.");

if ($_GET["type"] == "categories")
	$data = get_categories_alphabetically();
else if ($_GET["type"] == "courses")
{
	if (isset($_GET["category_id"]))
	{
		$subjects = get_subjects_from_category($_GET["category_id"]);
		if ($subjects == false)
			error("Invalid category id.");
		$data = $subjects;
	}
	else
		error("Category Id was not provided.");
}
else if ($_GET["type"] == "modules")
{
	if (isset($_GET["course_id"]))
	{
		$modules = get_modules_from_subject($_GET["course_id"]);
		if ($modules == false)
			error("No courses found.");
		$data = $modules;
	}
	else
		error("Course Id was not provided.");
}
else if ($_GET["type"] == "questions")
{
	if (isset($_GET["module_id"], $_GET["page_number"], $_GET["page_size"]))
	{
		$questions = get_questions_from_module($_GET["module_id"], $_GET["page_number"], $_GET["page_size"]);
		if ($questions == false)
			error("No modules found.");
		$data = $questions;
	}
	else
		error("Module Id, Page Number or Page Size was not provided.");
}
else if ($_GET["type"] == "answers")
{
	if (isset($_GET["question_id"], $_GET["page_number"], $_GET["page_size"]))
	{
		$answers = get_answers_for_question($_GET["question_id"], $_GET["page_number"], $_GET["page_size"], NULL);
		if ($answers == false)
			error("No questions found.");
		$data = $answers;
	}
	else
		error("Question Id, Page Number or Page Size was not provided.");
}
else
	error("Invalid Type.");

/*if (!isset($_GET["username"], $_GET["password"]))
	error("Invalid parameters.");*/

$data["error"] = false;
for ($i = 0; $i < count($data); $i++)
{
	for ($j = 0; $j < 500; $j++)
	{
		if (isset($data[$i][$j]))
			unset($data[$i][$j]);
	}
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>