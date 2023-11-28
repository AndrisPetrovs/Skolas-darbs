<?php

    include('db.php');

?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Questions</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class = "mainbox">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="question">Jautajums:</label>
                <input type="text" name="question" required>
                <br>
                <label for="options">Atbildes</label>
                <input type="text" name="options" required>
                <br>
                <label for="options">Atbildes</label>
                <input type="text" name="options" required>
                <br>
                <label for="correct_answer">Pareiza:</label>
                <input type="text" name="correct_answer" required>
                <br>
                <button type="submit">Pievienot jautajumu</button>
            </form>
        </div>
    </body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $question = $_POST["question"];
    $options = $_POST["options"];
    $correct_answer = $_POST["correct_answer"];

    // Check if the question already exists
    $checkQuestionSql = "SELECT id FROM question WHERE jautajums = ?";
    $checkQuestionStmt = $conn->prepare($checkQuestionSql);
    $checkQuestionStmt->bind_param("s", $question);
    $checkQuestionStmt->execute();
    $checkQuestionStmt->store_result();

    if ($checkQuestionStmt->num_rows > 0) {
        echo "Error: Question already exists!";
    } else {
        // Insert into the question table
        $insertQuestionSql = "INSERT INTO question (jautajums) VALUES (?)";
        $insertQuestionStmt = $conn->prepare($insertQuestionSql);
        $insertQuestionStmt->bind_param("s", $question);

        if ($insertQuestionStmt->execute()) {
            echo "Question added successfully!";
        } else {
            echo "Error: " . $insertQuestionSql . "<br>" . $conn->error;
        }

        $insertQuestionStmt->close();

        // Insert into the answers table
        $insertAnswersSql = "INSERT INTO answers (atbilde, is_correct) VALUES (?, ?)";
        $insertAnswersStmt = $conn->prepare($insertAnswersSql);
        $insertAnswersStmt->bind_param("ss", $options, $correct_answer);

        if ($insertAnswersStmt->execute()) {
            echo "Answers added successfully!";
        } else {
            echo "Error: " . $insertAnswersSql . "<br>" . $conn->error;
        }

        $insertAnswersStmt->close();
    }

    $checkQuestionStmt->close();
}

$conn->close();
?>
