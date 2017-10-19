<?php
$uid = $_GET["id"];
$linename = $_GET["linename"];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ลงทะเบียน</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
  </head>
  <script type="text/javascript">
        function zoom() {
            document.body.style.zoom = "250%"
        }
</script>
  <body onload="zoom()">
    <form action="insertregis.php" method="post">
      <input type="hidden" name="uid" value="<?=$uid ?>" >
      <input type="hidden" name="money" value="10000" >
    <div class="container">
      <div class="row">
        <div class="col-md-12">
            <center>
            <button type="button" class="btn btn-success" disabled style="width:100%;">สมัครสมาชิก</button>
          </center>
        </div>
        <div class="col-md-12">
            <center>
          <input type="text" name="linename" value="<?=$linename ?>" class="form-control">
          </center>
        </div>
        <div class="col-md-12">
            <input type="text" name="fname" class="form-control" placeholder="ชื่อ">
        </div>
        <div class="col-md-12">
            <input type="text" name="lname" class="form-control" placeholder="นามสกุล">
        </div>
        <div class="col-md-12">
            <center>
            <button type="button" class="btn btn-primary" >ยืนยัน</button>
            <button type="reset" class="btn btn-danger" >ล้างฟอร์ม</button>
          </center>
        </div>
      </div>
    </div>
    </form>
  </body>
</html>
