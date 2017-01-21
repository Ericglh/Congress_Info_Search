<!DOCTYPE html>
<html>
<head>
	<title>loginPage</title>
	<meta charset="UTF-8">
</head>
<body>
	<?php 
    error_reporting(0);
    ?>
	<script type="text/javascript">

	function validate(){
		var emptyfield = "";
		var valid = true;
		if(document.getElementsByTagName("select")[0].value=="notSelected"){
			emptyfield += "Congress database, ";
			valid = false;
		}else{
		}

		if(document.getElementById("userinput").value == ""){
			emptyfield += "keyword";
			valid = false;
		}else{

		}

		if(valid == false){
			var errorMsg = "Please enter the following missing information: " + emptyfield;
			alert(errorMsg);
		}else{
			return valid;
		}

	}

	function changeSelect(value){
		if(value == "legislators"){
			//form1.action = "http://congress. api.sunlightfoundation.com/legislators?";
			document.getElementById("keyword").innerHTML = "State/Representative*";
		}else if(value == "bills"){
			//form1.action = "http://congress.api.sunlightfoundation.com/bills?";
			document.getElementById("keyword").innerHTML = "Bill ID*";
		}else if(value == "amendments"){
			//form1.action = "http://congress.api.sunlightfoundation.com/amendments?";		
			document.getElementById("keyword").innerHTML = "Amendment ID*";
		}else if(value == "committees"){
			//form1.action = "http://congress.api.sunlightfoundation.com/committees?";
			document.getElementById("keyword").innerHTML = "Committees ID*";
		}else{
			document.getElementById("keyword").innerHTML = "Keyword*";
		}
	}
	function clear(){
		document.getElementsById('keyword')[0].value="Keyword*";
        document.getElementsByName('Database')[0].value="Select your option";
        document.getElementsByName('chamber')[0].checked=true;
        document.getElementsByName('chamber')[1].checked=false;
        //document.getElementById('result').innerHTML="";  
	}
	</script>

	<h2 align="center">Congress Information Search</h2>
	<div align="center">
		<form method="GET" name="form1">
			<fieldset style="width: 300px;margin: 0px;padding: 0px;">
				Congress Database 
				<select name="Database" onchange="changeSelect(value)">
					<option value="notSelected" <?php echo isset($_GET["clear"])?"selected=selected":""?>>Select your option</option>


					<option value="legislators" 
					<?php
						echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="legislators"?"selected=selected":""
					?>
					>Legislators</option>


					<option value="committees" <?php echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="committees"?"selected=selected":""?>>Committees</option>

					<option value="bills" 
					<?php 
						echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="bills"?"selected=selected":""
					?>
					>Bills</option>

					<option value="amendments" <?php echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="amendments"?"selected=selected":""?>>Amendments</option>
				</select>
				<br />
				<label>Chamber</label>
				<input type="radio" name="chamber" value="senate" checked="checked" <?php echo (isset($_GET["chamber"])&&($_GET["chamber"]=="senate"))?"checked=true":""?>> Senate
				<input type="radio" name="chamber" value="house" <?php echo (isset($_GET["chamber"])&&($_GET["chamber"]=="house")&&!isset($_GET["clear"]))?"checked=true":""?>> House
				<br /> 
				<label id = "keyword" >Keyword*</label>
				<input id = "userinput" type="text" name="state" value="<?php echo isset($_GET["state"])&&!isset($_GET["clear"])?$_GET["state"]:""?>" >
				<br />
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				<input type="submit" name="" value="Search" onclick="validate()">
				<input type="submit" name="clear" value="clear" onclick="clear()">

				<br />
				<a href="http://sunlightfoundation.com/">Powered by Sunlight Foundation</a>
				
			</fieldset>
		</form>
		<br />
		<br />
	</div>

	<?php
		$resShow = "";
		
		if(isset($_GET['bioguide_id'])){
			$apiKey = "apikey=d19367a89106435ead658d7b683091f3";
			$secondAPIQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?chamber=" .$_GET['chamber']. "&state=" .$_GET['query']. "&bioguide_id=". $_GET['bioguide_id']."&" .$apiKey;
			$bioguide = $_GET['bioguide_id'];
			$str = "";
			$str = file_get_contents($secondAPIQuery);
			$json = json_decode($str);
			//echo sizeof($json->results);
			$resShow = "";
			echo "<script type=text/javascript>document.getElementById(\"keyword\").innerHTML = \"State/Representative*\"</script>";
			if(sizeof($json->results) == 0){
				echo "<p align=\"center\">The API returned zero for the request.</p>";
			}else{
				$resShow .= "<div style=\"border-style: solid; border-width: 1px;\">";
				$resShow .= "<p align=\"center\"><img src=\"http://theunitedstates.io/images/congress/225x275/$bioguide.jpg\"></p>";
				
				$resShow .= "<table border=\"0\" align=\"center\" width=\"500px\">";
				foreach($json->results as $jr){
					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Fulle Name</td><td width=\"200px\" style=\"text-align:left;\">$jr->title $jr->first_name $jr->last_name</td></tr>";
					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Term Ends on</td><td width=\"200px\" style=\"text-align:left;\">$jr->term_end</td></tr>";
					if($jr->website == null){
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Website</td><td width=\"200px\" style=\"text-align:left;\">NA</td></tr>";
					}else{
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Website</td><td width=\"200px\" style=\"text-align:left;\"><a href=\"$jr->website\">$jr->website</td></tr>";
					}
					if($jr->office == null){
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Office</td><td width=\"250px\" style=\"text-align:left;\">NA</td></tr>";
					}else{
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Office</td><td width=\"250px\" style=\"text-align:left;\">$jr->office</td></tr>";
					}
					if($jr->facebook_id == null){
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Facebook</td><td width=\"200px\" style=\"text-align:left;\">NA</td></tr>";
					}else{
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Facebook</td><td width=\"200px\" style=\"text-align:left;\"><a href=\"http://www.facebook.com/$jr->facebook_id\">$jr->first_name $jr->last_name</td></tr>";
					}
					if($jr->twitter_id == null){
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Twitter</td><td width=\"200px\" style=\"text-align:left;\">NA</td></tr>";
					}else{
						$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Twitter</td><td width=\"200px\" style=\"text-align:left;\"><a href=\"http://www.twitter.com/$jr->twitter_id\">$jr->first_name $jr->last_name</td></tr>";
					}	
					//$resShow .= "<p align=\"center\">"."Full Name:".$jr->first_name."</p>";
				}
				$resShow .= "</table>";
				$resShow .= "</div>";
			}
			echo $resShow;
			
		}else if(isset($_GET['bill_id'])){

			$keyword = "Bill ID*";
			$apiKey = "apikey=d19367a89106435ead658d7b683091f3";
			$secondAPIQuery = "http://congress.api.sunlightfoundation.com/bills". "?bill_id=". $_GET['bill_id'] ."&chamber=" .$_GET['chamber']. "&" .$apiKey;
			//echo $secondAPIQuery;
			$bioguide = $_GET['bill_id'];
			
			$str = file_get_contents($secondAPIQuery);
			$json = json_decode($str);
			//print_r($json);
			//echo sizeof($json->results);
			$resShow = "";
			echo "<script type=text/javascript>document.getElementById(\"keyword\").innerHTML = \"Bill ID*\"</script>";
			if(sizeof($json->results) == 0){
				echo "<p align=\"center\">The View Detail API returned zero for the request.</p>";
			}else{
				$resShow .= "<div style=\"border-style: solid; border-width: 1px;\">";
				
				$resShow .= "<table border=\"0\" align=\"center\" width=\"500px\">";
				foreach($json->results as $jr){
					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Bill ID</td><td width=\"200px\" style=\"text-align:left;\">$jr->bill_id</td></tr>";
					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Bill Title</td><td width=\"200px\" style=\"text-align:left;\">$jr->short_title</td></tr>";

					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Sponsor</td><td width=\"200px\" style=\"text-align:left;\">";
					foreach($json->results as $fk){
						$resShow .= $fk->sponsor->title . " ";
						$resShow .= $fk->sponsor->first_name . " ";
						$resShow .= $fk->sponsor->last_name;
					}
					$resShow .= "</td></tr>";

					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Introduced On</td><td width=\"200px\" style=\"text-align:left;\">$jr->introduced_on</td></tr>";

					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Last action with date</td><td width=\"200px\" style=\"text-align:left;\">";
					foreach ($json->results as $fk) {
						$resShow .= $fk->last_version->version_name . ", ";
						$resShow .= $fk->last_action_at;
					}
					$resShow .= "</td></tr>";

					$resShow .= "<tr align=\"center\"><td width=\"200px\" style=\"text-align:left;\">Bill URL</td><td width=\"200px\" style=\"text-align:left;\">";
					foreach ($json->results as $fk) {
						$temp = $fk->last_version->urls->pdf;
						$resShow .= "<a href= $temp>";
						if($fk->short_title == null){
							$resShow .= $fk->bill_id;
						}else{
							$resShow .= $fk->short_title;
						}
						
					}
					$resShow .= "</td></tr>";
					
				}
				$resShow .= "</table>";
				$resShow .= "</div>";
			}
			echo $resShow;
		}else{
			if(isset($_GET['Database']) && $_GET['Database'] == "legislators" && isset($_GET['chamber']) && $_GET['state'] != ""){
				
				
				$apiKey = "apikey=d19367a89106435ead658d7b683091f3";
				$stateArray = [
					"Alabama" => "AL",
					"Alaska" => "AK",
					"Arizona" => "AZ",
					"Arkansas" => "AR",
					"California" => "CA",
					"Colorado" => "CO",
					"Connecticut" => "CT",
					"Delaware" => "DE",
					"District Of Columbia" => "DC",
					"Florida" => "FL",
					"Georgia" => "GA",
					"Hawaii" => "HI",
					"Idaho" => "ID",
					"Illinois" => "IL",
					"Indiana" => "IN",
					"Iowa" => "IA",
					"Kansas" => "KS",
					"Kentucky" => "KY",
					"Louisiana" => "LA",
					"Maine" => "ME",
					"Maryland" => "MD",
					"Massachusetts" => "MA",
					"Michigan" => "MI",
					"Minnesota" => "MN",
					"Mississippi" => "MS",
					"Missouri" => "MO",
					"Montana" => "MT",
					"Nebraska" => "NE",
					"Nevada" => "NV",
					"New Hampshire" => "NH",
					"New Jersey" => "NJ",
					"New Mexico" => "NM",
					"New York" => "NY",
					"North Carolina" => "NC",
					"North Dakota" => "ND",
					"Ohio" => "OH",
					"Oklahoma" => "OK",
					"Oregon" => "OR",
					"Pennsylvania" => "PA",
					"Rhode Island" => "RI",
					"South Carolina" => "SC",
					"South Dakota" => "SD",
					"Tennessee" => "TN",
					"Texas" => "TX",
					"Utah" => "UT",
					"Vermont" => "VT",
					"Virginia" => "VA",
					"Washington" => "WA",
					"West Virginia" => "WV",
					"Wisconsin" => "WI",
					"Wyoming" => "WY",
				];
				//$query = ucwords(strtolower(trim($_GET['state'])));
				//$query = urldecode($query);
				$boolQuery = true;
				if(array_key_exists(ucwords(strtolower(trim($_GET['state']))), $stateArray)){
				//input is state name
					$query = ucwords(strtolower(trim($_GET['state'])));
					$query = urldecode($query);
					foreach($stateArray as $key=>$value){
						if($key == $query){
							$query = $value;
						}
					}
					$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?chamber=" .$_GET['chamber']. "&state=" .$query. "&" .$apiKey;
				}else{
				//input is the fullname of a legislator
					$query = trim($_GET['state']);
					$nameArr = split(" ", $query);
					$len = sizeof($nameArr);
					
					if($len == 1){
						$folName = $nameArr[0];
						$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?chamber=" .$_GET['chamber'] ."&query=" .$folName. "&" .$apiKey;
						
					}else if($len == 2){
						$fname = $nameArr[0];
						$lname = $nameArr[1];
						
						$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?chamber=" .$_GET['chamber'] ."&first_name=" . $fname ."&last_name=". $lname ."&" .$apiKey;
						
					}else{
						$fname = $nameArr[0];
						for($i = 1;$i < $len;$i++){
							if($i == ($len - 1)){
								$lname .= $nameArr[$i];
							}else{
								$lname .= $nameArr[$i];
								$lname .= "%20";
							}
						}
						$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?chamber=" .$_GET['chamber'] ."&first_name=" . $fname ."&last_name=". $lname ."&" .$apiKey;
					}
				//$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?chamber=" .$_GET['chamber'] ."&query=" .$query. "&" .$apiKey;
				}
				
				$str = "";
				$str = file_get_contents($apiQuery);
				$json = json_decode($str);
				//echo $apiQuery;
				$resShow = "";

				//php invoke js function
				echo "<script type=text/javascript>document.getElementById(\"keyword\").innerHTML = \"State/Representative*\"</script>";
				if(sizeof($json->results) == 0){
					echo "<p align=\"center\">The API returned zero for the request.</p>";
				}else{
					$resShow .= "<div id = \"ajaxdiv\">";
					$resShow .= "<table border=\"1\" align=\"center\" width=\"800px\">";
					$resShow .= "<tr align=\"center\"><td><b>Name</b></td><td><b>State</b></td><td><b>Chamber</b></td><td><b>Details</b></td></tr>";
					foreach($json->results as $ln){
						$resShow .= "<tr id = \"$ln->bioguide_id\" align=\"center\">";
						$resShow .= "<td align=\"left\">". $ln->first_name . " " . $ln->last_name ."</td>";
						$resShow .= "<td>". $ln->state_name ."</td>";
						$resShow .= "<td>". $ln->chamber ."</td>";
						//$secondQuery .= "bioguide_id=" . $ln->bioguide_id . "&" . $apiKey;
						$secondUrl = "loginPage1.php" . "?bioguide_id=" . $ln->bioguide_id . "&Database=" . $_GET['Database']. "&chamber=" .$_GET['chamber']. "&state=" .$_GET['state'].  "&" .$apiKey;
						$resShow .= "<td><a href=$secondUrl>" . "View Details" ."</td>";
						$resShow .= "</tr>";
					}
					$resShow .= "</table></div>";
					echo $resShow;
				}
			}else if(isset($_GET['Database']) && $_GET['Database'] == "committees" && $_GET['state'] != ""){
				$keyword = "Committees ID*";
				$apiKey = "apikey=d19367a89106435ead658d7b683091f3";
				$apiQuery = "";
				$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?committee_id=". strtoupper(trim($_GET['state'])) ."&chamber=" .$_GET['chamber']. "&" .$apiKey;
				//echo $apiQuery;
				$str = "";
				$str = file_get_contents($apiQuery);
				$json = json_decode($str);
				//echo sizeof($json->results);
				$resShow = "";
				echo "<script type=text/javascript>document.getElementById(\"keyword\").innerHTML = \"Committees ID*\"</script>";
				if(sizeof($json->results) == 0){
					echo "<p align=\"center\">The API returned zero for the request.</p>";
				}else{
					$resShow .= "<table width=\"800px\" border=\"1\" align=\"center\"><tr align=\"center\">";
					$resShow .= "<td><b>Committee ID</b></td><td width=\"500px\"><b>Committee Name</b></td><td><b>Chamber</b></td>";

					foreach($json->results as $jr){
						$resShow .= "<tr align=\"center\"><td>$jr->committee_id</td><td width=\"500px\">$jr->name</td><td>$jr->chamber</td></tr>";
					}
					$resShow .= "</tr></table>";
				}
				echo $resShow;
			}else if(isset($_GET['Database']) && $_GET['Database'] == "bills" && $_GET['state'] != ""){
				$keyword = "Bill ID*";
				$apiKey = "apikey=d19367a89106435ead658d7b683091f3";
				$apiQuery = "";
				
				$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?bill_id=". strtolower(trim($_GET['state'])) ."&chamber=" .$_GET['chamber']. "&" .$apiKey;
				
				$str = "";
				$str = file_get_contents($apiQuery);
				$json = json_decode($str);

				$resShow = "";
				echo "<script type=text/javascript>document.getElementById(\"keyword\").innerHTML = \"Bill ID*\"</script>";
				if(sizeof($json->results) == 0){
					echo "<p align=\"center\">The API returned zero for the request.</p>";
				}else{
					$resShow .= "<table width=\"800px\" border=\"1\" align=\"center\"><tr align=\"center\">";
					$resShow .= "<td><b>Bill ID</b></td><td><b>Short Title</b></td><td><b>Chamber</b></td><td><b>Details</b></td></tr>";

					foreach($json->results as $jr){
						$secondUrl = "loginPage1.php/" . "?bill_id=" . $jr->bill_id . "&chamber=" . $jr->chamber . "&Database=" . $_GET['Database']. "&state=". $_GET['state'];
						$resShow .= "<tr align=\"center\"><td>$jr->bill_id</td>";
						if($jr->short_title == null){
							$resShow .= "<td>NA</td>";
						}else{
							$resShow .= "<td>$jr->short_title</td>";
						}
						
						$resShow .= "<td>$jr->chamber</td><td><a href=$secondUrl>View Details</td></tr>";
					}
					$resShow .= "</table>";
				}
				echo $resShow;
			}else if(isset($_GET['Database']) && $_GET['Database'] == "amendments" && $_GET['state'] != ""){
				$keyword = "Amendment ID*";
				$apiKey = "apikey=d19367a89106435ead658d7b683091f3";
				$apiQuery = "";
				$apiQuery = "http://congress.api.sunlightfoundation.com/".$_GET['Database']. "?amendment_id=". strtolower(trim($_GET['state'])) ."&chamber=" .$_GET['chamber']. "&" .$apiKey;
				//echo $apiQuery;
				$str = "";
				$str = file_get_contents($apiQuery);
				$json = json_decode($str);
				//echo sizeof($json->results);
				$resShow = "";
				echo "<script type=text/javascript>document.getElementById(\"keyword\").innerHTML = \"Amendment ID*\"</script>";
				if(sizeof($json->results) == 0){
					echo "<p align=\"center\">The API returned zero for the request.</p>";
				}else{
					$resShow .= "<table width=\"800px\" border=\"1\" align=\"center\"><tr align=\"center\">";
					$resShow .= "<td><b>Amendment ID</b></td><td><b>Amendment Type</b></td><td><b>Chamber</b></td><td><b>Introduced on</b></td>";

					foreach($json->results as $jr){
						$resShow .= "<tr align=\"center\"><td>$jr->amendment_id</td><td>$jr->amendment_type</td><td>$jr->chamber</td><td>$jr->introduced_on</td></tr>";
					}
					$resShow .= "</tr></table>";
				}
				echo $resShow;
			}
			
		}

	?>

</body>
</html>