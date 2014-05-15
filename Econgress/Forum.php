<?php
class Events extends SPRDB {
	public $eventRating, $authorRating, $eventText, $eventDate;
	function GetTableName() {
		return 'Events';
	}
	function GetFieldsList() {
		return 'Author, addDate, ventRating, $authorRating, eventText, eventDate';
	}
}

class Topics extends SPRDB {
	public $topicRating, $authorRating, $TopicText;
	function GetTableName() {
		return 'Topics';
	}
	function GetFieldsList() {
		return 'Author, addDate, topicRating, authorRating, TopicText';
	}
}

class Posts extends SPRDB {
	public $Topic, $postRating, $authorRating, $postText;
	function GetTableName() {
		return 'Posts';
	}
	function GetFieldsList() {
		return 'Author, addDate, Topic, postRating, authorRating, postText';
	}
}
?>