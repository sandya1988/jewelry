<?php
include 'components/header.php';

$form_error = '';
$form_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_form'])) {
    $name = htmlspecialchars($_POST['Name']);
    $email = htmlspecialchars($_POST['Email']);
    $phone_number = htmlspecialchars($_POST['Phone_Number']);
    $message = htmlspecialchars($_POST['Message']);

    if (!empty($name) && !empty($email) && !empty($phone_number) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO forms (name, email, phone_number, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone_number, $message);

        if ($stmt->execute()) {
            $form_success = "Message sent successfully.";

            $body = "Name: $name<br>Email: $email<br>Phone Number: $phone_number<br>Message: $message";
        } else {
            $form_error = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $form_error = "All fields are required.";
    }

    $conn->close();
}
?>

<div class="back_re">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h2>Contact Us</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form id="request" class="main_form" method="POST" action="/contact.php">
                    <div class="row">
                        <div class="col-md-12">
                            <input class="contactus" placeholder="Name" type="text" name="Name" required>
                        </div>
                        <div class="col-md-12">
                            <input class="contactus" placeholder="Email" type="email" name="Email" required>
                        </div>
                        <div class="col-md-12">
                            <input class="contactus" placeholder="Phone Number" type="text" name="Phone_Number" required>
                        </div>
                        <div class="col-md-12">
                            <textarea class="textarea" placeholder="Message" name="Message" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" name="submit_form" class="send_btn">Send</button>
                        </div>
                        <?php if ($form_error) : ?>
                            <div class="col-md-12 mt-4">
                                <p style="color: red;"><?php echo $form_error; ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($form_success) : ?>
                            <div class="col-md-12 mt-4">
                                <p style="color: green;"><?php echo $form_success; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>
