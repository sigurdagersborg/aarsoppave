<?php
require_once 'include/config_session.inc.php';
require_once "include/meny.inc.php";
require_once 'include/dbconn.inc.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resourceName = $_POST["resourceName"];
    // $resourceWorker = $_POST["resourceWorker"];
    $resourceFromDateTime = $_POST["resourceFromDateTime"];
    $resourceToDateTime = $_POST["resourceToDateTime"];
    
    $sql = "INSERT INTO resources (name,ansatt, from_date, to_date) VALUES ('$resourceName', '$resourceFromDateTime', '$resourceToDateTime')";
    $result = $pdo->query($sql);

    if ($result === TRUE) {
        echo "Ressurs lagt til vellykket!";
    } else {
        echo "Feil: " . $sql . "<br>" . $pdo->errorInfo()[2];
    }
}

$sql = "SELECT id, name, from_date, to_date FROM resources";
$result = $pdo->query($sql);
$events = [];

if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $events[] = [
            'id' => $row['id'],
            'title' => $row['name'],
            'start' => $row['from_date'],
            'end' => $row['to_date']
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ressurskalender</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>Kalender</h2>
            <div id="calendar" style="width: 80vw;"></div>


            <br>
            <?php
            if (isset($_SESSION['isAdmin'])) {
   ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="resourceName">Navn på ressurs:</label>
                    <input type="text" class="form-control" name="resourceName" required>
                </div>
                <div class="form-group">
                    <label for="resourceFromDateTime">Fra dato og klokkeslett:</label>
                    <input type="datetime-local" class="form-control" name="resourceFromDateTime" required>
                </div>
                <div class="form-group">
                    <label for="resourceToDateTime">Til dato og klokkeslett:</label>
                    <input type="datetime-local" class="form-control" name="resourceToDateTime" required>
                </div>
                <button type="submit" class="btn btn-primary">Legg til ressurs</button>
            </form>
            <?php } ?>
        </div>
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialiser FullCalendar
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: <?php echo json_encode($events); ?>,
            editable: true,
            eventDrop: function (event, delta, revertFunc) {
            }
        });
    });
</script>

</body>
</html>
