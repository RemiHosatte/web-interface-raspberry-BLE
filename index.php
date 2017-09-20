<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
  <?php
  //Scah container
  if (isset($_POST['scan']))
  {
    $devices = json_decode(shell_exec('sudo python /var/www/html/web-interface/scannerBLE.py'), true);
  }
  if (isset($_POST['restart']))
  {
   shell_exec('sudo service bluetooth restart');
  }
  if (isset($_POST['stop'])) {
   shell_exec('sudo pkill -f notificationBLE_HM-10.py');
  }
  if (isset($_POST['hciup'])) {
   shell_exec('sudo hciconfig hci0 up');
  }
  if (isset($_POST['hcidown'])) {
   shell_exec('sudo hciconfig hci0 down');
  }
  ?>
  <div class="containerPerso">
    <div class="titleBLE">BLE Devices Scanner</div>
    <div class=buttonDiv>
      <form  method="post" action="index.php">
      <input id="scanButton" class="button" type="submit" name="scan" value="SCAN">
      </form>
    </div>
    <div class="listDevices">
      <div class='deviceTitle'>
        <div class='deviceName'>Name</div>
        <div class='deviceAdress'>Adress</div>
      </div>
      <?php
      foreach($devices as $device){
        echo
        "<div class='device'>
          <div class='deviceName'>"
          . $device['name'] .
          "</div>
          <div class='deviceAdress'>"
          . $device['adress'] .
          "</div>
        </div>";
      }
      ?>
    </div>
  </div>
  <div class="containerPerso">
    <div class="titleBLE">BLE Live Reader</div>
    <div class=buttonDiv>
      <form  method="post" action="index.php">
      <input id="startButton" class="button" type="submit" name="start" value="START">
      <input id="stopButton" class="button" type="submit" name="stop" value="STOP">
      </form>
    </div>
    <div id="data">
      <div class="temperature"></div>
      <div class="humidity"></div>
      <div class="bpm"></div>
    </div>

  </div>
  <div class="containerPerso" id="bluetoothSettings">
    <div class="titleBLE">Bluetooth Settings</div>
    <div class=buttonDiv>
      <form  method="post" action="index.php">
      <input id="restartButton" class="button" type="submit" name="restart" value="RESTART SERVICE">
      </form>
      <form  method="post" action="index.php">
      <input id="restartButton" class="button" type="submit" name="hciup" value="HCICONFIG UP">
      </form>
      <form  method="post" action="index.php">
      <input id="restartButton" class="button" type="submit" name="hcidown" value="HCICONFIG DOWN">
      </form>
    </div>
  </div>
</body>
</html>
<script>
function updateValue(data)
{
  console.log(data);
  $(".bpm").html(data["bpm"]+" BPM");
/*
  var elem = data["data"].split('_');
  console.log(elem);
  $(".temperature").html(elem[0]+" C°");
    $(".humidity").html(elem[1]+" %");*/
}
</script>
<?php
//Reader container
if (isset($_POST['start'])) {
  liveExecuteCommand("sudo python notificationBLE_HeartRateSensor.py d2:60:3e:1a:d2:e6");
  }

  ?>
<?php

function liveExecuteCommand($cmd)
{

    while (@ ob_end_flush()); // end all output buffers if any

    $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');
    $live_output     = "";
    while (!feof($proc))
    {
        $live_output = fread($proc, 4096);
        ?>
        <script>
        var data = <?php echo $live_output; ?>;
        updateValue(data);
        </script>
        <?php
        @ flush();
    }
    pclose($proc);

} ?>
