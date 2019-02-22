<?php

function echoStatus($status){
	if($status == 1){
		return "checked";
	}
}


require_once "../PHP/Config.php";
require_once "../PHP/CoreFunctions.php";

if(!$isDeveloperPane){
    header("location: index.php");
}

$pageStatus = sqlFetch("SELECT * FROM PageStatus", "ASSOC");

if($_SERVER["REQUEST_METHOD"] === "POST"){
	if(validateInput($_POST["action"]) == "pageStatus"){
		$activePages = $_POST["status"];

		$returnPageStatus = array();
		foreach($pageStatus as $page){
			$returnPageStatus[] = array("pageName"=>$page["pageName"], "status"=>0);
		}

		foreach($activePages as $page){
			$index = 0;
			foreach($returnPageStatus as $returnPage){
				if($returnPage["pageName"] == $page){
					$returnPageStatus[$index]["status"] = 1;
				}
				$index++;
			}
		}

		foreach($returnPageStatus as $page){
			sqlUpdate("UPDATE PageStatus SET status = ".$page["status"]." WHERE pageName = '".$page["pageName"]."'");
		}

		$pageStatus = sqlFetch("SELECT * FROM PageStatus", "ASSOC");

	}

}

?>


<h4>Page Status</h4>

<form method="POST" onkeypress="return event.keycode != 13;">
	<input name="action" value="pageStatus" style="display: none">

	<table class='fullwidth collapsed'>
		<tr>
			<th>Page Name</th>
			<th>Status</th>
		</tr>

		<?php

		foreach($pageStatus as $page){
			echo "
			<tr>
				<td>".$page["pageName"]."</td>
				<td>
					<label class='switch'>
					<input type='checkbox' name='status[]' value='".$page["pageName"]."' ".echoStatus($page["status"]).">
					<span class='slider round'></span>
					</label>
				</td>
			</tr>";
		}

		?>

	</table>

	<div style="width: 73.61px; margin: 0 auto;">
		<button class="server-action">Submit</button>
	</div>

</form>









<style>
.switch {
  position: relative;
  display: inline-block;
  width: 42px;
  height: 23.8px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18.2px;
  width: 18.2px;
  left: 2.8px;
  bottom: 2.8px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(18.2px);
  -ms-transform: translateX(18.2px);
  transform: translateX(18.2px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 23.8px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>