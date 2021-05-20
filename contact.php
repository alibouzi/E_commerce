<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $contact_title = $row['contact_title'];
    $contact_banner = $row['contact_banner'];
}
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $contact_map_iframe = $row['contact_map_iframe'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $contact_address = $row['contact_address'];
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $contact_banner; ?>);">
    <div class="inner">
        <h1><?php echo $contact_title; ?></h1>
    </div>
</div>

<P></P>
<P></P>
                            


                        <div class="service bg-gray" id="contact">
  <div class="cc-contact-information" >
    <div class="container">
      <div class="cc-contact">
        <div class="row">
          <div class="col-md-9">
            <div class="card mb-0" data-aos="zoom-in">
                <P></P>
                <P></P>
              <div class="h4 text-center title"><strong>Contact Form</strong></div>
              <P></P>
              <P></P>
              <P></P>
              <div class="row">
                <div class="col-md-6">
                  <div class="card-body">


                            <?php
// After form submit checking everything for email sending
if(isset($_POST['form_contact']))
{
    $error_message = '';
    $success_message = '';
    $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
    foreach ($result as $row) 
    {
        $receive_email = $row['receive_email'];
        $receive_email_subject = $row['receive_email_subject'];
        $receive_email_thank_you_message = $row['receive_email_thank_you_message'];
    }

    $valid = 1;

    if(empty($_POST['visitor_name']))
    {
        $valid = 0;
        $error_message .= 'Please enter your name.\n';
    }

    if(empty($_POST['visitor_phone']))
    {
        $valid = 0;
        $error_message .= 'Please enter your phone number.\n';
    }


    if(empty($_POST['visitor_email']))
    {
        $valid = 0;
        $error_message .= 'Please enter your email address.\n';
    }
    else
    {
        // Email validation check
        if(!filter_var($_POST['visitor_email'], FILTER_VALIDATE_EMAIL))
        {
            $valid = 0;
            $error_message .= 'Please enter a valid email address.\n';
        }
    }

    if(empty($_POST['visitor_message']))
    {
        $valid = 0;
        $error_message .= 'Please enter your message.\n';
    }

    if($valid == 1)
    {
        
        $visitor_name = strip_tags($_POST['visitor_name']);
        $visitor_email = strip_tags($_POST['visitor_email']);
        $visitor_phone = strip_tags($_POST['visitor_phone']);
        $visitor_message = strip_tags($_POST['visitor_message']);

        // sending email
        $to_admin = $receive_email;
        $subject = $receive_email_subject;
        $message = '
<html><body>
<table>
<tr>
<td>Name</td>
<td>'.$visitor_name.'</td>
</tr>
<tr>
<td>Email</td>
<td>'.$visitor_email.'</td>
</tr>
<tr>
<td>Phone</td>
<td>'.$visitor_phone.'</td>
</tr>
<tr>
<td>Comment</td>
<td>'.nl2br($visitor_message).'</td>
</tr>
</table>
</body></html>
';
        $headers = 'From: ' . $visitor_email . "\r\n" .
                   'Reply-To: ' . $visitor_email . "\r\n" .
                   'X-Mailer: PHP/' . phpversion() . "\r\n" . 
                   "MIME-Version: 1.0\r\n" . 
                   "Content-Type: text/html; charset=ISO-8859-1\r\n";

        // Sending email to admin                  
        mail($to_admin, $subject, $message, $headers); 
        
        $success_message = $receive_email_thank_you_message;

    }
}
?>
                
                <?php
                if($error_message != '') {
                    echo "<script>alert('".$error_message."')</script>";
                }
                if($success_message != '') {
                    echo "<script>alert('".$success_message."')</script>";
                }
                ?>


                   
<form action="contact.php" method="POST">
                    <?php $csrf->echoInputField(); ?>
                      <div class="p pb-3"><strong>Feel free to contact Us</strong></div>
                      <div class="row mb-3">
                        <div class="col">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
                            <input class="form-control" type="text"name="visitor_name"  placeholder="Name" required="required"/>
                          </div>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input class="form-control" type="text" name="visitor_phone"  placeholder="Subject" required="required"/>
                          </div>
                        </div>
                      </div>
                      <div class="row mb-3">
                         <div class="col">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input class="form-control" type="email" name="visitor_email"  placeholder="E-mail" required="required"/>
                          </div>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col">
                          <div class="form-group">
                            <textarea class="form-control"  name="visitor_message" placeholder="Your Message" required="required"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <button class="btn btn-primary" type="submit"   name="form_contact">Send</button>
                        </div>
                      </div>
                    </form>
                   
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card-body">
                      <p></p>
                      <p></p>
                    <p class="mb-0"><strong>Address : </strong></p>
                    <p class="pb-2"> <?php echo nl2br($contact_address); ?></p>
                    <p class="mb-0"><strong>Phone : </strong></p>
                    <p class="pb-2"><span><?php echo $contact_phone; ?></p>
                    <p class="mb-0"><strong>Email : </strong> <p> <span><?php echo $contact_email; ?></span></a> </p></p>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
                



                

                
            </div>
        </div>
    </div>
</div>


<div class="jumbotron">
<h3>Find Us On Map</h3>
                <?php echo $contact_map_iframe; ?>
</div>


<?php require_once('footer.php'); ?>