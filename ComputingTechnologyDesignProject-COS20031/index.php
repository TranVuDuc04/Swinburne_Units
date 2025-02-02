<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archer Record System</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
          crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="styleforbutton.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <nav>
        <a href="leaderboard.php">Leader Board</a>
        <a href="index.php">Shooting</a>
        <a href="login.php">Recorder Login</a>
    </nav>
<div id="app" class="container-fluid bg-light text-center">
    <?php
    require "main.php";
    ?>
    <div class="mb-4">
        <label for="round" class="h1">Choose round and competition</label><br>
        <select name="round" id="round" onchange="setEndsBasedOnTargetFace()" class="w-50">
            <?php
            // read data from each row
            while ($row = $roundName->fetch_assoc()) {
                $targetFace = ($row['targetFaceID'] == 1) ? '80cm' : '120cm';
                echo "
                    <option value='$row[roundID]' data-target-face='$targetFace' data-range='$row[range]'> $row[name] - Target Face: $targetFace - range: $row[range]m</option>
                    ";
            }
            ?>
        </select>
        <select name="competition" id="competition" class="w-50">
            <?php
            // read data from each row
            while ($row = $competitionName->fetch_assoc()) {
                echo "
                    <option value='$row[competitionID]'>$row[name]</option>
                    ";
            }
            ?>
        </select>
    </div>
    <div class="mb-4">
        <label for="archer" class="h1">Choose archer</label><br>
        <select name="archer" id="archer" class="w-50">
            <?php
            // read data from each row
            while ($row = $archerName->fetch_assoc()) {
                echo "
                    <option value='$row[archerID]'>$row[name]</option>
                    ";
            }
            ?>
        </select>
        <select name="default equipment" id="defaultEquipment" class="w-50">
            <?php
            // read data from each row
            while ($row = $defaultEquipment->fetch_assoc()) {
                echo "
                    <option value='$row[equipmentID]'>$row[type]</option>
                    ";
            }
            ?>
        </select>
    </div>
    <div class="mb-4">
        <button type="button" class="btn-dark p-2 rounded shadow-none mb-4 w-25" onclick="SubmitRound()" value="SubmitRound">Done</button>
    </div>
    <div id="updated"></div>
    <div id="calScore" class="pb-4" style="display: none;">
        <div id="currentEnd" class="text-white"></div>
        <button id="openCal" type="button" class="btn-dark p-2 rounded shadow-none w-25" onclick="showCal()"><i class="fa-solid fa-pencil"></i></button>
        <div id="calculator" class="bg-secondary w-25 mx-auto mt-4" style="display: none;">
            <h1>Enter Score</h1>
            <div class="calScore">
                <div class="blankSquareContainer">
                    <div class="blankSquare"><input type="hidden" name="arrow1" id="arrow1"></div>
                    <div class="blankSquare"><input type="hidden" name="arrow2" id="arrow2"></div>
                    <div class="blankSquare"><input type="hidden" name="arrow3" id="arrow3"></div>
                    <div class="blankSquare"><input type="hidden" name="arrow4" id="arrow4"></div>
                    <div class="blankSquare"><input type="hidden" name="arrow5" id="arrow5"></div>
                    <div class="blankSquare"><input type="hidden" name="arrow6" id="arrow6"></div>
                </div>
                <div id="totalContainer">
                    <div id="totalLabel">Total:</div>
                    <div id="totalValue">
                        <?php // Display total value or perform other calculations ?>
                    </div>
                </div>
                <button id="submitButton" class="w-50" type="submit" onclick="SubmitEnd()">Submit</button>
        </div>

            <div id="buttonContainer">
                <button class="score scoreX" onclick="pushValue(10)">X</button>
                <button class="score score10" onclick="pushValue(10)">10</button>
                <button class="score score9" onclick="pushValue(9)">9</button>
                <button class="score score8" onclick="pushValue(8)">8</button>
                <button class="score score7" onclick="pushValue(7)">7</button>
                <button class="score score6" onclick="pushValue(6)">6</button>
                <button class="score score5" onclick="pushValue(5)">5</button>
                <button class="score score4" onclick="pushValue(4)">4</button>
                <button class="score score3" onclick="pushValue(3)">3</button>
                <button class="score score2" onclick="pushValue(2)">2</button>
                <button class="score score1" onclick="pushValue(1)">1</button>
                <button class="score scoreM" onclick="pushValue(0)">M</button>
                <button class="score delete" onclick="pushValue('delete')">
                <i class="fas fa-arrow-left"></i>
                </button>
            </div>
        </div>
        <p id="error"></p>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    var maxEnds = 6;
    var currentEnd = 1;

    function setEndsBasedOnTargetFace() {
        var roundSelect = document.getElementById('round');
        var selectedOption = roundSelect.options[roundSelect.selectedIndex];
        var targetFace = selectedOption.getAttribute('data-target-face');

        maxEnds = targetFace === '80cm' ? 6 : 5;
        $("#currentEnd").text("Current End: " + currentEnd + "/" + maxEnds);
    }

    function showCal() {
        var x = document.getElementById('calculator');
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
            $("#calculator").show();
        }
    }

    function SubmitRound() {
        var roundID = $("#round").val();
        var competitionID = $("#competition").val();
        var archerID = $("#archer").val();
        var equipmentID = $("#defaultEquipment").val();
        // get all selected text
        var roundName = $("#round option:selected").text();
        var competitionName = $("#competition option:selected").text();
        var archerName = $("#archer option:selected").text();
        var equipmentName = $("#defaultEquipment option:selected").text();
        $.post("submitRound.php", {
            roundID: roundID,
            competitionID: competitionID,
            archerID: archerID,
            equipmentID: equipmentID,
            //name
            roundName: roundName,
            competitionName: competitionName,
            archerName: archerName,
            equipmentName: equipmentName
        }, function (data) {
            $("#updated").html(data);
            $("#calScore").show();
            currentEnd = 1; // Reset current end to 1
            setEndsBasedOnTargetFace(); // Update maxEnds based on target face
            $("#currentEnd").text("Current End: " + currentEnd + "/" + maxEnds);
        });
    }

    // Function for button score

    var currentIndex = 0; // Track the current index

    function pushValue(a) {
        var blankSquares = document.getElementsByClassName("blankSquare");

        if (a === 'delete') {
            if (currentIndex > 0) {
                currentIndex--; // Move back to the previous square
                var inputField = blankSquares[currentIndex].querySelector('input');
                if (inputField) {
                    inputField.value = ''; // Clear the value in the current blank square's hidden input field
                    blankSquares[currentIndex].innerHTML = '<input type="hidden" name="arrow' + (currentIndex + 1) + '" id="arrow' + (currentIndex + 1) + '">';
                }
                calculateSum(); // Update the sum after deletion
            }
            return; // Exit the function after handling delete
        }

        if (currentIndex >= blankSquares.length) {
            currentIndex = 0; // Reset the index if it exceeds the number of squares
        }

        var inputField = blankSquares[currentIndex].querySelector('input');
        if (inputField) {
            inputField.value = a; // Set the value in the current blank square's hidden input field
            blankSquares[currentIndex].innerHTML = a + '<input type="hidden" name="arrow' + (currentIndex + 1) + '" id="arrow' + (currentIndex + 1) + '" value="' + a + '">';
        }

        currentIndex++; // Increment the index for the next square
        calculateSum(); // Calculate and update the sum
    }

    function calculateSum() {
        var blankSquares = document.getElementsByClassName("blankSquare");
        var sum = 0;

        for (var i = 0; i < blankSquares.length; i++) {
            var value = blankSquares[i].innerText;
            value = value === 'X' ? 0 : parseInt(value);
            if (!isNaN(value)) {
                sum += value;
            }
        }

        var totalValue = document.getElementById("totalValue");
        totalValue.innerHTML = sum;
    }

    // Function for end number

    function SubmitEnd() {
        if (currentEnd > maxEnds || currentEnd == maxEnds) {
            document.getElementById("error").innerHTML = "You have reached the maximum number of ends.";
            $(".blankSquare").empty(); // Clear the content of blank squares
            $("#totalValue").text(""); // Clear the total value
            $("#currentEnd").text("Current End: " + currentEnd + "/" + maxEnds); // Update the end format
            $("#error").text("You have reached the maximum number of ends."); // Display error message
            return;
        }

        var arrow1 = $("#arrow1").val() || 0;
        var arrow2 = $("#arrow2").val() || 0;
        var arrow3 = $("#arrow3").val() || 0;
        var arrow4 = $("#arrow4").val() || 0;
        var arrow5 = $("#arrow5").val() || 0;
        var arrow6 = $("#arrow6").val() || 0;
        var totalScore = $("#totalValue").text() || 0;

        $.post("submitEnd.php", {
            arrow1: arrow1,
            arrow2: arrow2,
            arrow3: arrow3,
            arrow4: arrow4,
            arrow5: arrow5,
            arrow6: arrow6,
            totalScore: totalScore
        }, function (data) {
            $("#error").html(data);
        });

        // Increment current end if it's not already at maxEnds
        if (currentEnd < maxEnds) {
            currentEnd++;
        }

        // Display the updated current end number
        $("#currentEnd").text("Current End: " + currentEnd + "/" + maxEnds);

        // Reset all the blank spaces
        var blankSquares = document.getElementsByClassName("blankSquare");
        for (var i = 0; i < blankSquares.length; i++) {
            blankSquares[i].innerHTML = '<input type="hidden" name="arrow' + (i + 1) + '" id="arrow' + (i + 1) + '">';
        }
        currentIndex = 0; // Reset the current index

        // Clear the total value
        $("#totalValue").text("0");
    }
</script>
</body>
</html>
