<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Traffic Signal</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	.outerDiv{width: 500px; height: 350px; border: 2px solid; margin: 100px auto; padding: 40px;}
	label{font-weight: bold;}
	button{width: 100px; height: 30px; margin-right: 5px;}
	.signal{width: 20px; height:20px; margin:10px; padding: 15px; text-align: center; border-radius:30px; display: inline-block;}
	.red{background:red;}
	.green{background:green;}
	.yellow{background:yellow;}
	.red{background:red;}
	.bottomDiv{margin-top: 20px; margin-left: 100px;}
	</style>	
</head>
<body>

<div id="container">
	<center><h2>Welcome to Traffic Signal</h2></center>
	<div class="outerDiv">
		<form id="signalForm">
			<label for="sequence1">Signal A : <label>
			<input type="number" min="1" max="4" id="sequence1" name="sequence1" value="<?php echo isset($sequence[0]) ? $sequence[0] : ''; ?>" required> <br><br>

			<label for="sequence2">Signal B : <label>
			<input type="number" min="1" max="4" id="sequence2" name="sequence2" value="<?php echo isset($sequence[1]) ? $sequence[1] : ''; ?>" required> <br><br>

			<label for="sequence3">Signal C : <label>
			<input type="number" min="1" max="4" id="sequence3" name="sequence3" value="<?php echo isset($sequence[2]) ? $sequence[2] : ''; ?>" required> <br><br>

			<label for="sequence4">Signal D : <label>
			<input type="number" min="1" max="4" id="sequence4" name="sequence4" value="<?php echo isset($sequence[3]) ? $sequence[3] : ''; ?>" required> <br><br>

			<label for="greenInterval">Green Light Intervals (Seconds) : <label>
			<input type="number" id="greenInterval" name="greenInterval" value="<?php echo $green_interval; ?>" required> <br><br>

			<label for="yellowInterval">Yellow Light Intervals (Seconds) : <label>
			<input type="number" id="yellowInterval" name="yellowInterval" value="<?php echo $yellow_interval; ?>" required> <br><br>

			<button type="button" onclick="startSignal()"> Start </button>
			<button type="button" onclick="stopSignal()"> Stop </button>
		</form>
		
		<div class="bottomDiv">
			<div id="signal1" class="signal red">A</div>
			<div id="signal2" class="signal red">B</div>
			<div id="signal3" class="signal red">C</div>
			<div id="signal4" class="signal red">D</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
	let currentSignalIndex = 0;
	let currentSignalState = 'red';
	let currentInterval = 0;
	let greenInterval, yellowInterval, sequence;
	let intervalId;

	function startSignal(){
		const formData = $('#signalForm').serialize();

		$.post('traffic/start', formData, function(data){
			if(data.success){
				sequence =  data.sequence;	
				greenInterval =  data.greenInterval;	
				yellowInterval =  data.yellowInterval;	
				currentSignalIndex = 0;
				currentSignalState = 'green';
				currentInterval = greenInterval;
				updateSignalState();
				intervalId = setInterval(updateSignalState, 1000);
			}else{
				alert(data.message);
			}
		}, 'json');
	}

	function updateSignalState(){
		currentInterval--;
		console.log(currentInterval);
		if(currentInterval <= 0){			
			switch(currentSignalState){
				case 'green' :
					currentSignalState = 'yellow';
					currentInterval = yellowInterval;
					setSignalColor(sequence[currentSignalIndex], 'yellow');
					break;
				case 'yellow':
					currentSignalState = 'red';
					currentInterval = 1;
					setSignalColor(sequence[currentSignalIndex], 'red');

					currentSignalIndex = (currentSignalIndex + 1) % sequence.length;

					currentSignalState = 'green';
					currentInterval = greenInterval;

					setSignalColor(sequence[currentSignalIndex], 'green');
					break;
				case 'red':
					break;
			}		
		}
	}

	function setSignalColor(signal, color){
		$('.signal').removeClass('green yellow').addClass('red');

		$('#signal'+signal).removeClass('red').addClass(color);
	}

	function stopSignal(){
		clearInterval(intervalId);

		$('.signal').removeClass('green yellow').addClass('red');
	}
</script>

</body>
</html>
