<!DOCTYPE html>
<html>
<head>
	<title>loginPage</title>
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
			//form1.action = "https://congress. api.sunlightfoundation.com/legislators?";
			document.getElementById("keyword").innerHTML = "State/Representative*";
		}else if(value == "bills"){
			//form1.action = "https://congress.api.sunlightfoundation.com/bills?";
			document.getElementById("keyword").innerHTML = "Bill ID*";
		}else if(value == "amendments"){
			//form1.action = "https://congress.api.sunlightfoundation.com/amendments?";		
			document.getElementById("keyword").innerHTML = "Amendment ID*";
		}else if(value == "committees"){
			//form1.action = "https://congress.api.sunlightfoundation.com/committees?";
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
	function ajaxFunction(value){
		alert(value);
       var ajaxRequest;  // The variable that makes Ajax possible!
       
       try {
          // Opera 8.0+, Firefox, Safari
          ajaxRequest = new XMLHttpRequest();
       }catch (e) {
          // Internet Explorer Browsers
          try {
             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
          }catch (e) {
             try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
             }catch (e){
                // Something went wrong
                alert("Your browser broke!");
                return false;
             }
          }
       }
       
       // Create a function that will receive data 
       // sent from the server and will update
       // div section in the same page.
			
       ajaxRequest.onreadystatechange = function(){
          if(ajaxRequest.readyState == 4){
             var ajaxDisplay = document.getElementById('ajaxDiv');
             ajaxDisplay.innerHTML = "hello";
          }
       }
       
       // Now get the value from user and pass it to
       // server script.
			
       //var age = document.getElementById('age').value;
       //var wpm = document.getElementById('wpm').value;
       //var sex = document.getElementById('sex').value;
       //var queryString = "?age=" + age ;
       var bioguide_id = ;
    
       //queryString +=  "&wpm=" + wpm + "&sex=" + sex;
       queryString = "";
       queryString = "bioguide_id=" + bioguide_id;

       ajaxRequest.open("GET", "loginPage.php" + queryString, true);
       ajaxRequest.send(); 
    }
	</script>

	<h2>Congress Information Search</h2>

	<form method="GET" name="form1">
		<fieldset style="width: 300px;margin: 0px;padding: 0px;">
			Congress Database 
			<select name="Database" onchange="changeSelect(value)">
				<option value="notSelected" <?php echo isset($_GET["clear"])?"selected=selected":""?>>Select your option</option>
				<option value="legislators" <?php echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="legislators"?"selected=selected":""?>>Legislators</option>
				<option value="committees" <?php echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="committees"?"selected=selected":""?>>Committees</option>
				<option value="bills" <?php echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="bills"?"selected=selected":""?>>Bills</option>
				<option value="amendments" <?php echo isset($_GET['Database'])&&!isset($_GET["clear"])&&$_GET['Database']=="amendments"?"selected=selected":""?>>Amendments</option>
			</select>
			<br />
			<input type="radio" name="chamber" value="senate" <?php echo (isset($_GET["chamber"])&&($_GET["chamber"]=="senate"))?"checked=true":""?>> Senate
			<input type="radio" name="chamber" value="house" <?php echo (isset($_GET["chamber"])&&($_GET["chamber"]=="house"))?"checked=true":""?>> House
			
			<br /> 
			<span id = "keyword">Keyword*</span>
			<input id = "userinput" type="text" name="state" value="<?php echo isset($_GET["state"])&&!isset($_GET["clear"])?$_GET["state"]:""?>" >
			<br />
			<input type="submit" name="" value="Search" onclick="validate()">
			<input type="submit" name="clear" value="clear" onclick="clear()">

			<br />
			<a href="http://sunlightfoundation.com/">Powered by Sunlight Foundation</a>
			
		</fieldset>
	</form>




	<?php
		$apiKey = "apikey=d19367a89106435ead658d7b683091f3";
		$apiQuery = "";
		$apiQuery = "https://congress.api.sunlightfoundation.com/".$_GET['Database']. "?chamber=" .$_GET['chamber']. "&state=" .$_GET['state']. "&" .$apiKey;
		//$apiQuery = "https://congress.api.sunlightfoundation.com/legislators?chamber=house&state=CA&apikey=d19367a89106435ead658d7b683091f3";
		$str = "";
		$str = file_get_contents($apiQuery);
		$json = json_decode($str);
		//echo sizeof($json->results);
		$resShow = "";
		if(sizeof($json->results) == 0){
			echo "<p align=\"center\">The API returned zero for the request.</p>";
		}else{
			$resShow .= "<div id = \"ajaxdiv\">";
			$resShow .= "<table border=\"1\" align=\"center\" width=\"800px\">";
			$resShow .= "<tr><td>Name</td><td>State</td><td>Chamber</td> <td>Details</td></tr>";
			foreach($json->results as $ln){
				$resShow .= "<tr>";
				$resShow .= "<td>". $ln->first_name . " " . $ln->last_name ."</td>";
				$resShow .= "<td>". $ln->state_name ."</td>";
				$resShow .= "<td>". $ln->chamber ."</td>";
				$resShow .= "<td id = \" \"><a href=\"####\" onclick=\"ajaxFunction({$ln->bioguide_id})\" >". "View Details" ."</td>";
				$resShow .= "</tr>";
			}
			$resShow .= "</table></div>";
			echo $resShow;
		}

	?>



</body>
</html>