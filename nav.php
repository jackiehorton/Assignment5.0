<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        /* This sets the current page to not be a link. Repeat this if block for
         *  each menu item */
        if ($path_parts['filename'] == "index") {
            print '<li class="activePage">Home</li>';
        } else {
            print '<li><a href="index.php">Home</a></li>';
        }
        if ($path_parts['filename'] == "form") {
           print '<li class="activePage">Form</li>';
        } else {
            print '<li><a href="form.php">Form</a></li>';
        }
        if ($path_parts['filename'] == "animals") {
           print '<li class="activePage">Animals</li>';
        } else {
            print '<li><a href="animals.php">Animals</a></li>';
        }

        if ($path_parts['filename'] == "closeup") {
           print '<li class="activePage">Close-up</li>';
        } else {
            print '<li><a href="closeup.php">Close-ups</a></li>';
        }

        if ($path_parts['filename'] == "landscapes") {
           print '<li class="activePage">Landscapes</li>';
        } else {
            print '<li><a href="landscapes.php">Landscapes</a></li>';
        }

        if ($path_parts['filename'] == "people") {
           print '<li class="activePage">People</li>';
        } else {
            print '<li><a href="people.php">People</a></li>';
        }


        ?>
    </ol>
</nav> 