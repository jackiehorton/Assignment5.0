<?php
include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
$email = "youremail@uvm.edu";
$firstName = "Bob";
$lastname = "Smith";
$size = "large";
$portrait = false;    // checked
$landscape = false; // not checked
$closeup = false;
$animal = false;
$color = "Type of print";    // pick the option
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$emailERROR = false;
$firstNameERROR = false;
$sizeERROR = false;
$lastnameERROR = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$dataRecord = array();
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    //
    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2b Sanitize (clean) data
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.


    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;
    $lastname = htmlentities($_POST["txtlastname"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastname;
    $size = htmlentities($_POST["radsize"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $size;
    $color = htmlentities($_POST["lstColor"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $color;

    if (isset($_POST["chkPortrait"])) {
        $portrait = true;
    } else {
        $portrait = false;
    }
    $dataRecord[] = $portrait;
    if (isset($_POST["chkLandscape"])) {
        $landscape = true;
    } else {
        $landscape = false;
    }
    $dataRecord[] = $landscape;
}

if (isset($_POST["chkCloseup"])) {
    $closeup = true;
} else {
    $closeup = false;
}
$dataRecord[] = $closeup;

if (isset($_POST["chkAnimal"])) {
    $animal = true;
} else {
    $animal = false;
}
$dataRecord[] = $animal;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//
// Validation section. Check each value for possible errors, empty or
// not what we expect. You will need an IF block for each element you will
// check (see above section 1c and 1d). The if blocks should also be in the
// order that the elements appear on your form so that the error messages
// will be in the order they appear. errorMsg will be displayed on the form
// see section 3b. The error flag ($emailERROR) will be used in section 3c.

if ($email == "") {
    $errorMsg[] = "Please enter your email address";
    $emailERROR = true;
} elseif (!verifyEmail($email)) {
    $errorMsg[] = "Your email address appears to be incorrect.";
    $emailERROR = true;
}
if ($firstName == "") {
    $errorMsg[] = "Please enter your first name";
    $firstNameERROR = true;
} elseif (!verifyAlphaNum($firstName)) {
    $errorMsg[] = "Your first name appears to have extra character.";
    $firstNameERROR = true;
}
if ($lastname == "") {
    $errorMsg[] = "Please enter your last name";
    $lastnameERROR = true;
} elseif (!verifyAlphaNum($lastname)) {
    $errorMsg[] = "Your last name appears to have extra character.";
    $lastnameERROR = true;
}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//
if (!$errorMsg) {
    if ($debug)
        print "<p>Form is valid</p>";
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
        // SECTION: 2e Save Data
    //
        //

        $fileExt = ".csv";

    $myFileName = "data/registration";

    $filename = $myFileName . $fileExt;

    if ($debug)
        print "\n\n<p>filename is " . $filename;

    // now we just open the file for append
    $file = fopen($filename, 'a');

    // write the forms information
    fputcsv($file, $dataRecord);

    // close the file
    fclose($file);









    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
        // SECTION: 2f Create message
    //
        //
        //

        $message = '<h2>Your information.</h2>';

    foreach ($_POST as $key => $value) {

        $message .= "<p>";

        $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

        foreach ($camelCase as $one) {
            $message .= $one . " ";
        }
        $message .= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
    }







    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
        // SECTION: 2g Mail to user
    //
        //
        //
        $to = $email; // the person who filled out the form
    $cc = "";
    $bcc = "";
    $from = "North Eastern Xposure <noreply@NEX.com>";

    // subject of mail should make sense to your form
    $todaysDate = strftime("%x");
    $subject = "NEX" . $todaysDate;

    $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
} // end form is valid
// ends if form was submitted.
//#############################################################################
//
// SECTION 3 Display Form
//
?>

<article id="main">

    <?php
//####################################
//
    // SECTION 3a.
//
    //
//
//
// If its the first time coming to the form or there are errors we are going
// to display the form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print "<h1>Your Request has ";

        if (!$mailed) {
            print "not ";
        }

        print "been processed</h1>";

        print "<p>A copy of this message has ";
        if (!$mailed) {
            print "not ";
        }
        print "been sent</p>";
        print "<p>To: " . $email . "</p>";
        print "<p>Mail Message:</p>";

        print $message;
    } else {

        //####################################
        //
        // SECTION 3b Error Messages
        //
        // display any error messages before we print out the form
        if ($errorMsg) {
            print '<div id="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print '</div>';
        }
        //####################################
        //
        // SECTION 3c html Form
        //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
          is defined in top.php
          NOTE the line:
          value="<?php print $email; ?>
          this makes the form sticky by displaying either the initial default value (line 35)
          or the value they typed in (line 84)
          NOTE this line:
          <?php if($emailERROR) print 'class="mistake"'; ?>
          this prints out a css class so that we can highlight the background etc. to
          make it stand out that a mistake happened here.
         */
        ?>

        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">

            <fieldset class="wrapper">
                <legend>Enter Contact Information</legend>
                <p>You information will allow me to contact you about my photographs.</p>

                <fieldset class="wrapperTwo">
                    <legend>Please complete the following form</legend>



                    <label for="txtFirstName" class="required">First Name
                        <input type="text" id="txtFirstName" name="txtFirstName"
                               value="<?php print $firstName; ?>"
                               tabindex="100" maxlength="45" placeholder="Enter your first name"
                               <?php if ($lastnameERROR) print 'class="mistake"'; ?>
                               onfocus="this.select()"
                               autofocus>
                    </label>
                    <label for="txtlastname" class="required">Last Name
                        <input type="text" id="txtlastname" name="txtlastname"
                               value="<?php print $lastname; ?>"
                               tabindex="100" maxlength="45" placeholder="Enter your last name"
                               <?php if ($lastnameERROR) print 'class="mistake"'; ?>
                               onfocus="this.select()"
                               autofocus>
                    </label>



                    <label for="txtEmail" class="required">Email
                        <input type="text" id="txtEmail" name="txtEmail"
                               value="<?php print $email; ?>"
                               tabindex="120" maxlength="45" placeholder="Enter a valid email address"
                               <?php if ($emailERROR) print 'class="mistake"'; ?>
                               onfocus="this.select()"
                               autofocus>
                    </label>

                </fieldset> <!-- ends wrapper Two -->



                <fieldset  class="listbox">
                    <legend>Type of Print</legend>
                    <label for="lstColor"></label>
                    <select id="lstColor"
                            name="lstColor"
                            tabindex="520" >
                        <option <?php if ($color == "B&W") print " selected "; ?>
                            value="B&W">Black and White</option>

                        <option <?php if ($color == "Color") print " selected "; ?>
                            value="Color" >Color</option>

                        <option <?php if ($color == "Sepia") print " selected "; ?>
                            value="Sepia" >Sepia</option>
                    </select>
                </fieldset>

                <fieldset class="checkbox">
                    <legend>Types of photographs you are interested in:</legend>
                    <label><input type="checkbox"
                                  id="chkPortrait"
                                  name="chkPortrait"
                                  value="Portrait"
                                  <?php if ($portrait) print ' checked '; ?>
                                  tabindex="420">Portraits</label>

                    <label><input type="checkbox"
                                  id="chkLandscape"
                                  name="chkLandscape"
                                  value="Landscape"
                                  <?php if ($landscape) print ' checked '; ?>
                                  tabindex="430">Landscapes</label>

                    <label><input type="checkbox"
                                  id="chkCloseup"
                                  name="chkCloseup"
                                  value="Closeup"
                                  <?php if ($closeup) print ' checked '; ?>
                                  tabindex="430">Close-ups</label>

                    <label><input type="checkbox"
                                  id="chkAnimal"
                                  name="chkAnimal"
                                  value="Animal"
                                  <?php if ($animal) print ' checked '; ?>
                                  tabindex="430">Animals</label>


                </fieldset>
                
                       <!-- Ends Wrapper -->
                    <fieldset class="radio">
                        <legend>What size photograph are you looking for?</legend>
                        <label><input type="radio"
                                      id="radSmall"
                                      name="radsize"
                                      value="Small"
                                      <?php if ($size == "Small") print 'checked' ?>
                                      tabindex="330">Small</label>
                        <label><input type="radio"
                                      id="radMedium"
                                      name="radsize"
                                      value="Medium"
                                      <?php if ($size == "Medium") print 'checked' ?>
                                      tabindex="340">Medium</label>
                        <label><input type="radio"
                                      id="radLarge"
                                      name="radsize"
                                      value="Large"
                                      <?php if ($size == "Large") print 'checked' ?>
                                      tabindex="340">Large</label>


                    </fieldset>

                        <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" tabindex="900" class="button">
                    </form>


                    <?php
                } // end body submit
                ?>

                </article>

                <?php include "footer.php"; ?>

                </body>
                </html> 