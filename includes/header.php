<?php 
    session_start();
?>

<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">


<title>CPSC 304 Database

<?php 
    if (defined("title")){
        echo "--".$title;
    }
?>
</title>

<!--
	<link href="bookbiz.css" rel="stylesheet" type="text/css">
-->

<!--
    Javascript to submit a title_id as a POST form, used with the "delete" links
-->
<script>
function formSubmit(titleId) {
    'use strict';
    if (confirm('Are you sure you want to delete this title?')) {
      // Set the value of a hidden HTML element in this form
      var form = document.getElementById('delete');
      form.title_id.value = titleId;
      // Post this form
      form.submit();
    }
}
</script>
</head>
<body>
<?php 
    include_once("nav.php");
?>
