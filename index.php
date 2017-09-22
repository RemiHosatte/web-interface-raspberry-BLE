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
  if (isset($_POST['gatt']))
  {

    $data = json_decode(shell_exec("sudo python /var/www/html/web-interface/gatt.py " .$_POST['mac_adress']. " " . $_POST['addr_type']), true);
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
    <div class="titleBLE">BLE Notification</div>
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
  <div class="containerPerso">
    <div class="titleBLE">GATT Explorer</div>
    <div class=buttonDiv>
      <form  method="post" action="index.php">
      <div class="btn_zone1">
        <input class="mac_adress" type="text" name="mac_adress" placeholder="MAC Adress"/>
        <br>
        <label><input class="addr_type" type="radio" value="public" name="addr_type"/>Public</label>
        <label><input class="addr_type" type="radio" value="random" name="addr_type"/>Random</label>
      </div>
      <div class="btn_zone2">
          <input id="gattButton" class="button" type="submit" name="gatt" value="GATT"/>
      </div>

      </form>
    </div>
    <div class='gatt'>
        <?php
        foreach($data as $gatt){
          foreach($gatt['services'] as $service){
            echo
              "<div class='service'>
                <div class='first serviceProperties'><b>"
                .$service['uuidName']. "</b><br><i> " .$service['uuid']. "</i> - Handle: [" .$service['hndStart']. " - " .$service['hndEnd']. "]
                </div>
                <div class='first-p characterictics'>";
                  foreach($service['characteristics'] as $characteristic){
                  echo "<div class='characteristic'><b>" .$characteristic['uuidName'].  "</b><br><i> " .$characteristic['uuid']. " </i>- Handle: " .$characteristic['handle']. "<br>";
                  $propertiesStr = [];
                  foreach ($characteristic['propertiesToString'] as $properties) {
                    $propertiesStr[] =  $properties;
                  }
                  echo implode(" - ",$propertiesStr);
                  echo "</div>";
                  }
                  foreach($service['descriptors'] as $descriptors){
                    echo "<div class='descriptor'><b>" .$descriptors['uuidName']. "</b><br><i>" .$descriptors['uuid']. "</i> Handle: " .$descriptors['handle']. "</div>";
                  }
          echo "</div>
              </div>";
          }
        }
        ?>
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
<style>
  .mac_adress {
  width: 150px;
  background-color: #303650;
    box-shadow: inset 0 0 10px 3px #21232d;
    border: 1px solid #2b2d36;
    height: 25px;
    color: #9d9da5;
}
  .btn_zone1 {
  display: inline-block;
}
  .btn_zone2 {
  vertical-align: top;
  display: inline-block;
}
  .gatt {
  font-size: 15px;
  margin-top: 10px;
  height: 284px;
  overflow: auto;
}
.serviceProperties {
  border:1px solid #2b2d36;
  cursor:pointer;
}
.characteristic {
  background-color: #2b314a;
  box-shadow: inset 0 0 10px 3px #21232d;
  padding-left: 5px;
  padding-bottom: 5px;
  padding-top: 5px;
}
.characterictics {
  margin-left: 30px;
}
.descriptor
{
  padding-left: 5px;
  padding-bottom: 5px;
  padding-top: 5px;
  background-color: #5f2b29;
}

</style>
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
<script>
$('.first-p').hide();
state = false;
$( ".first" ).click(function() {
  state = $(this).find('img').attr('src');
  console.log(state);
  if (state == 'less.png')
  {
    $(this).find('img').attr('src', 'more.png');
  }
  else {
    $(this).find('img').attr('src', 'less.png');
  }

    $(this).next().slideToggle(200);

});
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
