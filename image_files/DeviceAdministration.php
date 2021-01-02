<?php
// Check user login or not
if (!isset($_SESSION['uname'])) {
    header('Location: login/index.php');
}

// logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: login/index.php');
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Paul's Training Videos</title>
  <link href="Content/bootstrap.css" rel="stylesheet" />

  <script src="Scripts/jquery-1.11.0.min.js"></script>
  <script src="Scripts/bootstrap.min.js"></script>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <h2>Administration - Sensor registry</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <table id="productTable" class="table table-bordered table-condensed table-striped">
          <thead>
            <tr>
              <th>Edit</th>
              <th>dev_id</th>
              <th>dev_location_name</th>
              <th>dev_latitude</th>
              <th>dev_longitude</th>
              <th>Delete</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
            Sensor administration
          </div>
          <div class="panel-body">
            <div class="form-group">
              <label for="device_id">
                Device id
              </label>
              <input type="text" class="form-control" value="123456" id="device_id" />
            </div>
            <div class="form-group">
              <label for="device_location">
                Device location name
              </label>
              <input type="text" class="form-control" value="123456" id="device_location" />
            </div>
            <div class="form-group">
              <label for="device_latitude">
                Device latitude
              </label>
              <input type="text" class="form-control" value="00.00000" id="device_latitude" />
            </div>
            <div class="form-group">
              <label for="device_longitude">
                Device longitude
              </label>
              <input type="text" class="form-control" value="00.00000" id="device_longitude" />
            </div>
          </div>
          <div class="panel-footer">
            <div class="row">
              <div class="col-xs-12">
                <button type="button" id="updateButton" class="btn btn-primary" onclick="productUpdate();">
                  Add
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <h1>Navigation</h1>
    <form action="login/home.php">
        <input type="submit" value="Hauptmenü" />
    </form>
    <form method='post' action="">
        <input type="submit" value="Logout" name="but_logout">
    </form>

  <script>
    // Next id for adding a new Product
    var nextId = 1;
    // ID of Product currently editing
    var activeId = 0;

    function productDisplay(ctl) {
      var row = $(ctl).parents("tr");
      var cols = row.children("td");

      activeId = $($(cols[0]).children("button")[0]).data("id");
      $("#device_id").val($(cols[1]).text());
      $("#device_location").val($(cols[2]).text());
      $("#device_latitude").val($(cols[3]).text());
      $("#device_longitude").val($(cols[4]).text());

      // Change Update Button Text
      $("#updateButton").text("Update");
    }

    function productUpdate() {
      if ($("#updateButton").text() == "Update") {
        productUpdateInTable(activeId);
      } else {
        productAddToTable();
      }

      // Clear form fields
      formClear();

      // Focus to product name field
      $("#device_id").focus();
    }

    // Add product to <table>
    function productAddToTable() {
      // First check if a <tbody> tag exists, add one if not
      if ($("#productTable tbody").length == 0) {
        $("#productTable").append("<tbody></tbody>");
      }

      // Append product to table
      $("#productTable tbody").append(
        productBuildTableRow(nextId));

      // Increment next ID to use
      nextId += 1;
    }

    //#####################################################################################################################
    // initialize table from database <table> (tobi)
    function productAddToTableFromDatabase(id, dev_id, dev_location_name, dev_latitude, dev_longitude) {
      // First check if a <tbody> tag exists, add one if not
      if ($("#productTable tbody").length == 0) {
        $("#productTable").append("<tbody></tbody>");
      }

      // Append product to table
      $("#productTable tbody").append(
        productBuildTableRowFromDatabase(nextId, id, dev_id, dev_location_name, dev_latitude, dev_longitude));

      // Increment next ID to use
      nextId += 1;
    }

    // Build a <table> row of Product data (Tobi)
    function productBuildTableRowFromDatabase(nextIdx, id, dev_id, dev_location_name, dev_latitude, dev_longitude) {

      var ret =
        "<tr>" +
        "<td>" +
        "<button type='button' " +
        "onclick='productDisplay(this);' " +
        "class='btn btn-default' " +
        "data-id='" + nextIdx + "'>" +
        "<span class='glyphicon glyphicon-edit' />" +
        "</button>" +
        "</td>" +
        "<td>" + dev_id + "</td>" +
        "<td>" + dev_location_name + "</td>" +
        "<td>" + dev_latitude + "</td>" +
        "<td>" + dev_longitude + "</td>" +
        "<td>" +
        "<button type='button' " +
        "onclick='productDelete(this," + dev_id +");' " +
        "class='btn btn-default' " +
        "data-id='" + nextIdx + "'>" +
        "<span class='glyphicon glyphicon-remove' />" +
        "</button>" +
        "</td>" +
        "</tr>"

      return ret;
    }
    //#####################################################################################################################
    // Update product in <table>
    function productUpdateInTable(id) {
      // Find Product in <table>
      var row = $("#productTable button[data-id='" + id + "']")
        .parents("tr")[0];

      // Add changed product to table
      $(row).after(productBuildTableRow(id));
      // Remove original product
      $(row).remove();

      // Clear form fields
      formClear();

      // Change Update Button Text
      $("#updateButton").text("Add");
    }

    // Build a <table> row of Product data
    function productBuildTableRow(id) {
      if (id === nextId) {
        sendJSON("add", $("#device_id").val(), $("#device_location").val(), $("#device_latitude").val(), $("#device_longitude").val());
      } else {
        sendJSON("update", $("#device_id").val(), $("#device_location").val(), $("#device_latitude").val(), $("#device_longitude").val());
      }

      //todo replace 756 unten
      var ret =
        "<tr>" +
        "<td>" +
        "<button type='button' " +
        "onclick='productDisplay(this);' " +
        "class='btn btn-default' " +
        "data-id='" + id + "'>" +
        "<span class='glyphicon glyphicon-edit' />" +
        "</button>" +
        "</td>" +
        "<td>" + $("#device_id").val() + "</td>" +
        "<td>" + $("#device_location").val() + "</td>" +
        "<td>" + $("#device_latitude").val() + "</td>" +
        "<td>" + $("#device_longitude").val() + "</td>" + 
        "<td>" +
        "<button type='button' " +
        "onclick='productDelete(this" + $("#device_id").val() +");' " +
        "class='btn btn-default' " +
        "data-id='" + id + "'>" +
        "<span class='glyphicon glyphicon-remove' />" +
        "</button>" +
        "</td>" +
        "</tr>"

      return ret;
    }

    // Delete product from <table>
    function productDelete(ctl, dev_id) {
      $(ctl).parents("tr").remove();
      console.log("device_id: ");
      console.log(dev_id);
      sendJSON("delete", dev_id, "0", "0", "0");
    }

    // Clear form fields
    function formClear() {
      $("#device_id").val("");
      $("#device_location").val("");
      $("#device_latitude").val("");
      $("#device_longitude").val("");
    }


    $(document).ready(function () {
      initTable();
    });


    function initTable() {
      {
        // abrufen der device tabelle und anzeigen in tabelle
        $.post("data.php",
          function (data_sensors) {
            console.log(data_sensors);
            //sendJSON("111", "65555", "555", "6676");
            for (var i in data_sensors) {
              productAddToTableFromDatabase(data_sensors[i].id, data_sensors[i].dev_id, data_sensors[i].dev_location_name, data_sensors[i].dev_latitude, data_sensors[i].dev_longitude);
            }
          });


      }
    }


    function sendJSON(action_type, dev_id, dev_location_name, dev_latitude, dev_longitude) {

      // Creating a XHR object 
      let xhr = new XMLHttpRequest();
      let url = "addDevice.php";

      // open a connection 
      xhr.open("POST", url, true);

      // Set the request header i.e. which type of content you are sending 
      xhr.setRequestHeader("Content-Type", "application/json");

      //Create a state change callback 
      // xhr.onreadystatechange = function () {
      //   if (xhr.readyState === 4 && xhr.status === 200) {

      //     // Print received data from server 
      //     result.innerHTML = this.responseText;

      //   }
      // };

      // Converting JSON data to string 
      var data = JSON.stringify({ "type": action_type, "dev_id": dev_id, "dev_location_name": dev_location_name, "dev_latitude": dev_latitude, "dev_longitude": dev_longitude });

      // Sending data with the request 
      xhr.send(data);
    }







  </script>
</body>

</html>